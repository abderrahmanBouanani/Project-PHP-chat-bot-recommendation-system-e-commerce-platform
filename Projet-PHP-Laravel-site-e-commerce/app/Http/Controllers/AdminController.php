<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur
     */
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
        if (session('user')['type'] !== 'admin') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
        }

        // Récupérer les statistiques pour le tableau de bord
        $totalClients = User::where('type', 'client')->count();
        $totalVendeurs = User::where('type', 'vendeur')->count();
        $totalLivreurs = User::where('type', 'livreur')->count();
        $totalProduits = Produit::count();
        $totalCommandes = Commande::count();
        
        return view('admin-interface.dashboard', [
            'page' => 'ShopAll - Tableau de Bord',
            'stats' => [
                'clients' => $totalClients,
                'vendeurs' => $totalVendeurs,
                'livreurs' => $totalLivreurs,
                'produits' => $totalProduits,
                'commandes' => $totalCommandes
            ]
        ]);
    }

    /**
     * Affiche le profil de l'administrateur
     */
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
        if (session('user')['type'] !== 'admin') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
        }

        // Récupérer les informations de l'administrateur connecté
        $admin = session('user');
        
        return view('admin-interface.about', [
            'page' => 'ShopAll - Profil',
            'admin' => $admin
        ]);
    }

    /**
     * Met à jour le profil de l'administrateur
     */
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
        if (session('user')['type'] !== 'admin') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }
            return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
        }

        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        // Mise à jour de l'admin
        $admin = User::where('email', session('user')['email'])->first();
        
        if ($admin) {
            $admin->nom = $validated['nom'];
            $admin->prenom = $validated['prenom'];
            $admin->email = $validated['email'];
            $admin->telephone = $validated['telephone'];
            
            if (!empty($validated['password'])) {
                $admin->password = $validated['password'];
            }
            
            $admin->save();
            
            // Mettre à jour la session
            session(['user' => [
                'id' => $admin->id,
                'nom' => $admin->nom,
                'prenom' => $admin->prenom,
                'email' => $admin->email,
                'telephone' => $admin->telephone,
                'type' => $admin->type
            ]]);
            
            return redirect()->back()->with('success', 'Profil mis à jour avec succès');
        }
        
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour du profil');
    }
}
