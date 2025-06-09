<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswords extends Command
{
    protected $signature = 'users:reset-passwords';
    protected $description = 'Réinitialise les mots de passe des utilisateurs selon le format: partie_email@ + 123';

    public function handle()
    {
        $this->info('Début de la réinitialisation des mots de passe...');

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            // Extraire la partie avant @ de l'email
            $emailParts = explode('@', $user->email);
            if (count($emailParts) !== 2) {
                $this->error("Format d'email invalide pour {$user->email}");
                continue;
            }

            // Créer le nouveau mot de passe
            $newPassword = $emailParts[0] . '123';
            
            // Hacher le nouveau mot de passe
            $user->password = Hash::make($newPassword);
            $user->save();
            $count++;

            $this->info("Mot de passe réinitialisé pour {$user->email} : {$newPassword}");
        }

        $this->info("Réinitialisation terminée. {$count} mots de passe ont été mis à jour.");
    }
} 