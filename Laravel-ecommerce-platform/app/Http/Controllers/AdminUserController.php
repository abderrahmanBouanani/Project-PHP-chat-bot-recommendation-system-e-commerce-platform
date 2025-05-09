<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $users = User::all();
        
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
        
        $users = $query->orderBy('nom')->get();
        
        return response()->json($users);
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
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès');
    }
}
