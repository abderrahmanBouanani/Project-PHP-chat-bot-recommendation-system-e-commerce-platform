<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.welcome'); // resources/views/login.blade.php
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'motdepasse');

        // Vérifier d'abord dans la base de données
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['motdepasse'], $user->password)) {
            // Vérifier si l'utilisateur est bloqué
            if ($user->blocked) {
                return back()->with('error', 'Votre compte a été bloqué par l\'administrateur. Veuillez contacter le support pour plus d\'informations.');
            }

            session([
                'user' => [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'type' => $user->type
                ]
            ]);

            switch (strtolower($user->type)) {
                case 'client':
                    return redirect('/client_home');
                case 'vendeur':
                    return redirect('/vendeur_home');
                case 'livreur':
                    return redirect('/livreur_livraison');
                case 'admin':
                    return redirect('/admin_home');
                default:
                    return back()->with('error', "Type d'utilisateur inconnu.");
            }
        }

        // Si aucun utilisateur n'est trouvé dans la base de données, vérifier les identifiants de l'admin
        if ($credentials['email'] === 'admin@gmail.com' && $credentials['motdepasse'] === 'admin123') {
            session([
                'user' => [
                    'id' => 0,
                    'nom' => 'Admin',
                    'prenom' => 'Oumaima',
                    'email' => $credentials['email'],
                    'telephone' => '0555555555',
                    'type' => 'admin'
                ]
            ]);
            return redirect('/admin_home');
        }

        return back()->with('error', 'Email ou mot de passe incorrect.');
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('status', 'Vous avez été déconnecté avec succès.');
    }
}
