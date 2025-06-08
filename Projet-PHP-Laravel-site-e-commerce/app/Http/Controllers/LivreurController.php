<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;

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
        try {
            $produits = DB::table('commande_produit')
                ->join('produits', 'commande_produit.produit_id', '=', 'produits.id')
                ->where('commande_produit.commande_id', $id)
                ->select('produits.*', 'commande_produit.quantite')
                ->get();

            return response()->json($produits->toArray());
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
    public function showDetails($id)
    {
        try {
            // Charger la commande avec les relations nécessaires
            $commande = Commande::with([
                'client', 
                'produits' => function($query) {
                    $query->withPivot('quantite');
                }
            ])->findOrFail($id);
            
            // Log pour déboguer les données brutes
            \Log::info('Commande chargée:', [
                'commande_id' => $commande->id,
                'produits_count' => $commande->produits->count(),
                'produits' => $commande->produits->map(function($p) {
                    return [
                        'produit_id' => $p->id,
                        'pivot' => $p->pivot ? [
                            'quantite' => $p->pivot->quantite,
                            'commande_id' => $p->pivot->commande_id,
                            'produit_id' => $p->pivot->produit_id
                        ] : null
                    ];
                })
            ]);
            
            // Calculer le sous-total si nécessaire
            if (!isset($commande->sous_total)) {
                $commande->sous_total = $commande->produits->sum(function($produit) {
                    return $produit->prix_unitaire * $produit->pivot->quantite;
                });
            }
            
            // Préparer les données pour la réponse JSON
            $response = [
                'id' => $commande->id,
                'client' => [
                    'id' => $commande->client->id,
                    'nom' => $commande->client->nom,
                    'prenom' => $commande->client->prenom,
                    'email' => $commande->client->email,
                    'telephone' => $commande->client->telephone ?? ''
                ],
                'adresse' => $commande->adresse,
                'adresse_livraison' => $commande->adresse, // Utiliser le même champ que l'adresse
                'ville_livraison' => '',
                'code_postal_livraison' => '',
                'pays_livraison' => 'Maroc',
                'statut' => $commande->statut,
                'total' => $commande->total,
                'sous_total' => $commande->sous_total ?? $commande->total,
                'frais_livraison' => 0,
                'remise' => $commande->reduction ?? 0,
                'created_at' => $commande->created_at,
                'produits' => $commande->produits->map(function($produit) {
                    return [
                        'id' => $produit->id,
                        'nom' => $produit->nom,
                        'reference' => $produit->reference ?? 'N/A',
                        'prix_unitaire' => $produit->prix_unitaire ?? 0,
                        'quantite' => $produit->pivot->quantite,
                        'image' => $produit->image ?? ''
                    ];
                })
            ];
            
            $jsonResponse = [
                'success' => true,
                'commande' => (object)$response
            ];
            
            // Log de la réponse complète
            \Log::info('Réponse JSON complète:', $jsonResponse);
            
            return response()->json($jsonResponse);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des détails de la commande: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails de la commande: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les commandes non acceptées par un livreur (juste validées par l'admin)
     */
    public function livraisonsDisponibles()
    {
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
}
