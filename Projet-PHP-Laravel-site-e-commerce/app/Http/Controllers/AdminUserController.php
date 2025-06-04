<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        // Vérifier les droits d'édition
       $check = $this->checkEditRights();
       if ($check) {
           return $check;
       }
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès');
    }
}
