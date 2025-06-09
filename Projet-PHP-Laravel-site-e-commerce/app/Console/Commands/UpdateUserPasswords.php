<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswords extends Command
{
    protected $signature = 'users:update-passwords';
    protected $description = 'Met à jour les mots de passe des utilisateurs avec un nouveau hachage';

    public function handle()
    {
        $this->info('Début de la mise à jour des mots de passe...');

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            // Vérifier si le mot de passe est déjà haché
            if (strpos($user->password, '$2y$') === 0) {
                $this->info("Le mot de passe de {$user->email} est déjà haché.");
                continue;
            }

            // Hacher le mot de passe
            $user->password = Hash::make($user->password);
            $user->save();
            $count++;

            $this->info("Mot de passe mis à jour pour {$user->email}");
        }

        $this->info("Mise à jour terminée. {$count} mots de passe ont été mis à jour.");
    }
} 