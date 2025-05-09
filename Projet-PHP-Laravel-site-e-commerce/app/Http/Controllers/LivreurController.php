<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;

class LivreurController extends Controller
{
    // Affiche la liste des commandes à livrer pour le livreur
    public function index()
    {
        // Récupérer toutes les commandes, y compris celles qui sont livrées
        $commandes = Commande::whereIn('statut', ['Confirmée', 'En cours de livraison', 'Livrée'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livreur-interface.livraisons', [
            'commandes' => $commandes
        ]);
    }

    // Le livreur accepte la commande (statut -> En cours de livraison)
    public function accepter($id)
    {
        try {
            $commande = Commande::findOrFail($id);
            if ($commande->statut === 'Confirmée') {
                $commande->statut = 'En cours de livraison';
                $commande->save();
                
                // Log the status change
                \Log::info("Commande {$id} acceptée par le livreur, nouveau statut: En cours de livraison");
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Commande acceptée.',
                    'newStatus' => 'En cours de livraison'
                ]);
            }
            return response()->json([
                'success' => false, 
                'message' => 'Commande non disponible pour acceptation. Statut actuel: ' . $commande->statut
            ], 400);
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'acceptation de la commande {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'acceptation de la commande: ' . $e->getMessage()
            ], 500);
        }
    }

    // Le livreur marque la commande comme livrée (statut -> Livrée)
    public function livree($id)
    {
        try {
            $commande = Commande::findOrFail($id);
            if ($commande->statut === 'En cours de livraison') {
                $commande->statut = 'Livrée';
                $commande->save();
                
                // Log the status change
                \Log::info("Commande {$id} marquée comme livrée, nouveau statut: Livrée");
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Commande livrée.',
                    'newStatus' => 'Livrée'
                ]);
            }
            return response()->json([
                'success' => false, 
                'message' => 'Commande non disponible pour livraison. Statut actuel: ' . $commande->statut
            ], 400);
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la mise à jour du statut de livraison pour la commande {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage()
            ], 500);
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
        
        $commandes = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($commandes);
    }
}
