<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Affiche le formulaire de réinitialisation du mot de passe
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Réinitialise le mot de passe
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Vérifier si le token est valide
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['email' => 'Token invalide ou expiré.']);
        }

        // Vérifier si le token n'est pas expiré (1 heure)
        $createdAt = Carbon::parse($tokenData->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 60) {
            return back()->withErrors(['email' => 'Le token a expiré. Veuillez demander un nouveau lien de réinitialisation.']);
        }

        // Mettre à jour le mot de passe
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Aucun utilisateur trouvé avec cette adresse email.']);
        }

        $user->password = $request->password;
        $user->save();

        // Supprimer le token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/')->with('status', 'Votre mot de passe a été réinitialisé avec succès!');
    }
}
