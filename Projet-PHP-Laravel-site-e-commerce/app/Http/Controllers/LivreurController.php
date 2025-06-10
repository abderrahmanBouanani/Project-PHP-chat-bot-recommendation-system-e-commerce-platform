<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LivreurController extends Controller
{   
    protected function isReadOnly()
     {
         return !session()->has('user');
     }

     protected function checkEditRights()
     {
         if ($this->isReadOnly()) {
             return redirect()->back()->with('error', 'Action non autorisée en mode lecture seule. Veuillez vous connecter.');
         }
         return null;
     } 
    // Affiche la liste des commandes à livrer pour le livreur
    public function index()
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        // Récupérer toutes les commandes, y compris celles qui sont livrées
        $commandes = Commande::whereIn('statut', ['Confirmée', 'En cours de livraison', 'Livrée'])
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('livreur-interface.livraisons', [
            'commandes' => $commandes
        ]);
    }

    // Le livreur accepte la commande (statut -> En cours de livraison)
    public function accepter($id)
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        try {
            // Vérifier les droits d'édition
            $check = $this->checkEditRights();
            if ($check) {
                return $check;
            }
            $commande = Commande::findOrFail($id);
            if ($commande->statut === 'Confirmée') {
                // Vérifier si le livreur a déjà une commande en cours de livraison
                $livreurId = session('user')['id'] ?? null;
                if (!$livreurId) {
                    return redirect()->route('login')->with('error', 'Livreur non connecté.');
                }
                $commandeEnCours = Commande::where('livreur_id', $livreurId)
                    ->where('statut', 'En cours de livraison')
                    ->first();
                if ($commandeEnCours) {
                    return redirect()->route('livreur.commande.actuelle')->with('error', 'Vous avez déjà une commande en cours de livraison.');
                }
                $commande->statut = 'En cours de livraison';
                $commande->livreur_id = $livreurId;
                $commande->save();

                // Log the status change
                \Log::info("Commande {$id} acceptée par le livreur, nouveau statut: En cours de livraison");

                return redirect()->route('livreur.commande.actuelle')->with('success', 'Commande acceptée avec succès.');
            }
            return redirect()->route('livreur.livraisons.disponibles')->with('error', 'Commande non disponible pour acceptation. Statut actuel: ' . $commande->statut);
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'acceptation de la commande {$id}: " . $e->getMessage());
            return redirect()->route('livreur.livraisons.disponibles')->with('error', 'Erreur lors de l\'acceptation de la commande: ' . $e->getMessage());
        }
    }

    // Le livreur marque la commande comme livrée (statut -> Livrée)
    public function livree($id)
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        try {
            // Vérifier les droits d'édition
            $check = $this->checkEditRights();
            if ($check) {
                return $check;
            }
            $commande = Commande::findOrFail($id);
            if ($commande->statut === 'En cours de livraison') {
                $commande->statut = 'Livrée';
                $commande->save();

                // Log the status change
                \Log::info("Commande {$id} marquée comme livrée, nouveau statut: Livrée");

                return redirect()->route('livreur.commande.actuelle')->with('success', 'Commande marquée comme livrée avec succès.');
            }
            return redirect()->route('livreur.commande.actuelle')->with('error', 'Commande non disponible pour livraison. Statut actuel: ' . $commande->statut);
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la mise à jour du statut de livraison pour la commande {$id}: " . $e->getMessage());
            return redirect()->route('livreur.commande.actuelle')->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }

    // Mettre à jour le statut d'une commande
    public function updateStatus(Request $request, $id)
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $request->validate([
            'status' => 'required|in:Confirmée,En cours de livraison,Livrée'
        ]);

        $commande = Commande::findOrFail($id);

        // Vérifier les transitions de statut valides
        $validTransitions = [
            'Confirmée' => ['En cours de livraison'],
            'En cours de livraison' => ['Livrée'],
            'Livrée' => []
        ];

        if (!in_array($request->status, $validTransitions[$commande->statut] ?? [])) {
            return response()->json([
                'success' => false,
                'message' => 'Transition de statut non autorisée. Statut actuel: ' . $commande->statut
            ], 400);
        }

        try {
            $commande->statut = $request->status;
            $commande->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut de la commande mis à jour avec succès.',
                'newStatus' => $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage()
            ], 500);
        }
    }

    // Récupérer les produits d'une commande
    public function getProducts($id)
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        try {
            $commande = Commande::with(['produits' => function($query) {
                $query->select('produits.*')
                    ->addSelect(['commande_produit.quantite']);
            }])->findOrFail($id);

            if (!$commande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non trouvée'
                ], 404);
            }

            $produits = $commande->produits->map(function($produit) {
                return [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'image' => $produit->image,
                    'prix_unitaire' => $produit->prix_unitaire,
                    'quantite' => $produit->pivot->quantite,
                    'total' => $produit->prix_unitaire * $produit->pivot->quantite
                ];
            });

            return response()->json([
                'success' => true,
                'produits' => $produits,
                'commande' => [
                    'id' => $commande->id,
                    'total' => $commande->total,
                    'statut' => $commande->statut
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getProducts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        try {
            $user = session('user');
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non connecté'
                ], 401);
            }

            // Validation des données
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telephone' => 'required|string|max:20',
                'motdepasse' => 'nullable|string|min:6'
            ]);

            // Mettre à jour les informations dans la base de données
            DB::table('users')
                ->where('id', $user['id'])
                ->update([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'telephone' => $validated['telephone'],
                    'updated_at' => now()
                ]);

            // Mettre à jour les informations dans la session
            $user['nom'] = $validated['nom'];
            $user['prenom'] = $validated['prenom'];
            $user['email'] = $validated['email'];
            $user['telephone'] = $validated['telephone'];

            // Si un nouveau mot de passe est fourni, le mettre à jour
            if (!empty($validated['motdepasse'])) {
                $user['motdepasse'] = bcrypt($validated['motdepasse']);
                DB::table('users')
                    ->where('id', $user['id'])
                    ->update(['motdepasse' => bcrypt($validated['motdepasse'])]);
            }

            // Mettre à jour la session
            session(['user' => $user]);

            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du profil: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recherche des livraisons en fonction des critères
     */
    public function search(Request $request)
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $searchTerm = $request->input('search', '');
        $statusFilter = $request->input('status', 'all');

        $query = Commande::query();

        // Recherche par ID ou adresse
        if (!empty($searchTerm)) {
            if (is_numeric($searchTerm)) {
                $query->where('id', $searchTerm);
            } else {
                $query->where('adresse', 'like', "%{$searchTerm}%");
            }
        }

        // Filtrer par statut
        if ($statusFilter !== 'all') {
            $query->where('statut', $statusFilter);
        }

        $perPage = 8;

        $commandes = $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $request->input('page', 1));

        return response()->json([
            'data' => $commandes->items(),
            'total' => $commandes->total(),
            'current_page' => $commandes->currentPage(),
            'per_page' => $commandes->perPage(),
            'last_page' => $commandes->lastPage()
        ]);
    }
    
    /**
     * Afficher les détails d'une commande
     *
     * @param int $id ID de la commande
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommandeDetails($id)
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        try {
            $commande = Commande::with(['client', 'produits' => function($query) {
                $query->withPivot('quantite');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'commande' => $commande
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans getCommandeDetails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails de la commande'
            ], 500);
        }
    }

    /**
     * Affiche les commandes non acceptées par un livreur (juste validées par l'admin)
     */
    public function livraisonsDisponibles()
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $commandes = \App\Models\Commande::where('statut', 'Confirmée')
            ->whereNull('livreur_id')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        return view('livreur-interface.livraisons_disponibles', [
            'commandes' => $commandes
        ]);
    }

    /**
     * Affiche les commandes livrées par le livreur connecté
     */
    public function mesLivraisons()
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $livreurId = session('user')['id'];
        $commandes = \App\Models\Commande::where('livreur_id', $livreurId)
            ->where('statut', 'Livrée')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        return view('livreur-interface.mes_livraisons', [
            'commandes' => $commandes
        ]);
    }

    /**
     * Affiche la commande actuelle en cours de livraison du livreur
     */
    public function commandeActuelle()
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $livreurId = session('user')['id'] ?? null;
        if (!$livreurId) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        $commande = \App\Models\Commande::where('livreur_id', $livreurId)
            ->where('statut', 'En cours de livraison')
            ->orderBy('created_at', 'desc')
            ->first();
        return view('livreur-interface.commande_actuelle', [
            'commande' => $commande
        ]);
    }

    public function dashboard()
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $livreur = Auth::user();
        
        // Statistiques générales
        $stats = [
            'total_livraisons' => Commande::where('livreur_id', $livreur->id)->count(),
            'livraisons_en_cours' => Commande::where('livreur_id', $livreur->id)
                ->where('statut', 'En cours de livraison')
                ->count(),
            'livraisons_terminees' => Commande::where('livreur_id', $livreur->id)
                ->where('statut', 'Livrée')
                ->count(),
            'livraisons_disponibles' => Commande::whereNull('livreur_id')
                ->where('statut', 'Confirmée')
                ->count()
        ];

        // Données pour le graphique des 7 derniers jours
        $dates = [];
        $totals = [];
        
        // Obtenir la date d'aujourd'hui
        $endDate = now()->startOfDay();
        $startDate = $endDate->copy()->subDays(6); // 7 jours au total (aujourd'hui + 6 jours précédents)

        // Générer les dates et compter les livraisons
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $count = Commande::where('livreur_id', $livreur->id)
                ->whereDate('created_at', $currentDate->format('Y-m-d'))
                ->count();
            
            $dates[] = $currentDate->format('d/m');
            $totals[] = $count;
            
            $currentDate->addDay();
        }

        // Livraisons récentes (triées par date de mise à jour)
        $commandes_recentes = Commande::where('livreur_id', $livreur->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('livreur-interface.dashboard', compact('stats', 'dates', 'totals', 'commandes_recentes'));
    }

    public function chartData()
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        $livreurId = session('user')['id'];
        $dates = [];
        $totals = [];
        
        $livraisons_par_jour = Commande::where('livreur_id', $livreurId)
            ->where('statut', 'Livrée')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $formattedDate = Carbon::now()->subDays($i)->format('d/m');
            
            $dates[] = $formattedDate;
            $totals[] = $livraisons_par_jour->get($date, (object)['total' => 0])->total;
        }

        return response()->json([
            'dates' => $dates,
            'totals' => $totals
        ]);
    }

    public function profile()
    {
        // Vérification de la session
        if (!session('user') || !isset(session('user')['id'])) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour effectuer cette action.'
                ], 401);
            }
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérification du type d'utilisateur
        if (session('user')['type'] !== 'livreur') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux livreurs.');
        }

        return view('livreur-interface.profil-livreur', [
            'user' => session('user')
        ]);
    }
}
