<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Commande;

class AdminUserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs
     */

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
    public function index()
    {
        $users = User::paginate(8);

        return view('admin-interface.Utilisateur', [
            'page' => 'ShopAll - Utilisateurs',
            'users' => $users
        ]);
    }

    /**
     * Recherche des utilisateurs en fonction des critères
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $typeFilter = $request->input('type', 'all');

        $query = User::query();

        // Recherche par nom, email ou téléphone
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'like', "%{$searchTerm}%")
                  ->orWhere('prenom', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('telephone', 'like', "%{$searchTerm}%");
            });
        }

        // Filtrer par type
        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        $perPage = 8;

        $users = $query->orderBy('nom')->paginate($perPage, ['*'], 'page', $request->input('page', 1));

        return response()->json([
            'data' => $users->items(),
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'per_page' => $users->perPage(),
            'last_page' => $users->lastPage()
        ]);
    }

    /**
     * Affiche les détails d'un utilisateur spécifique
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin-interface.user-details', [
            'page' => 'ShopAll - Détails utilisateur',
            'user' => $user
        ]);
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy($id)
    {
        try {
            // Vérifier les droits d'édition
            $check = $this->checkEditRights();
            if ($check) {
                return $check;
            }

            // Récupérer l'utilisateur
            $user = User::findOrFail($id);

            // Vérifier si l'utilisateur est un client avec des commandes
            if ($user->type === 'client' && $user->commandes()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet utilisateur ne peut pas être supprimé car il a des commandes associées.'
                ], 400);
            }

            // Vérifier si l'utilisateur est un livreur avec des commandes en cours
            if ($user->type === 'livreur') {
                $commandesEnCours = Commande::where('livreur_id', $user->id)
                    ->whereIn('statut', ['En cours de livraison', 'Confirmée'])
                    ->count();
                
                if ($commandesEnCours > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cet utilisateur ne peut pas être supprimé car il a des commandes en cours de livraison.'
                    ], 400);
                }
            }

            // Vérifier si l'utilisateur est un vendeur avec des produits
            if ($user->type === 'vendeur' && $user->produits()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet utilisateur ne peut pas être supprimé car il a des produits associés.'
                ], 400);
            }

            // Supprimer l'utilisateur
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de l\'utilisateur', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage()
            ], 500);
        }
    }
}
