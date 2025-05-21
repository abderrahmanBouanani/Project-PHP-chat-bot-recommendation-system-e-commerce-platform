<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Affiche le formulaire pour demander un lien de réinitialisation de mot de passe
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Envoie un lien de réinitialisation de mot de passe à l'utilisateur
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Aucun utilisateur trouvé avec cette adresse email.');
        }

        // Générer un token unique
        $token = Str::random(64);

        // Stocker le token dans la base de données
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Construire l'URL de réinitialisation
        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $request->email], false));

        // Envoyer l'email
        Mail::send('auth.emails.reset', ['resetUrl' => $resetUrl, 'user' => $user], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Réinitialisation de votre mot de passe');
        });

        return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre adresse email!');
    }
}
