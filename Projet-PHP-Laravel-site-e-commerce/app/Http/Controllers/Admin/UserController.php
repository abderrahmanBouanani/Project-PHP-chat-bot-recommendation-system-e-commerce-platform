<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function toggleBlock(User $user, Request $request)
    {
        try {
            $user->blocked = $request->blocked;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => $request->blocked ? 'Utilisateur bloqué avec succès' : 'Utilisateur débloqué avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut de l\'utilisateur'
            ], 500);
        }
    }
} 