<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Mettre à jour les informations du profil client
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Récupérer l'utilisateur connecté depuis la session
        $user = session('user');
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vous connecter pour effectuer cette action.'
            ], 401);
        }
        
        // Débogage : Afficher le contenu de la session
        \Log::info('Session user data:', ['user' => $user]);
        
        // Vérifier si l'utilisateur est un modèle Eloquent
        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            $userId = $user->id;
        } 
        // Vérifier si c'est un objet stdClass
        elseif (is_object($user) && isset($user->id)) {
            $userId = $user->id;
        }
        // Vérifier si c'est un tableau
        elseif (is_array($user) && isset($user['id'])) {
            $userId = $user['id'];
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Données utilisateur invalides. Veuillez vous reconnecter.'
            ], 422);
        }

        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'telephone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Mettre à jour l'utilisateur dans la base de données
            $userModel = User::find($userId);
            $userModel->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
            ]);

            // Mettre à jour les informations dans la session
            $updatedUser = User::find($userId);
            // Ne stocker que les données nécessaires dans la session
            $userSessionData = [
                'id' => $updatedUser->id,
                'nom' => $updatedUser->nom,
                'prenom' => $updatedUser->prenom,
                'email' => $updatedUser->email,
                'telephone' => $updatedUser->telephone,
                'type' => $updatedUser->type
            ];
            // Mettre à jour la session avec les nouvelles données
            session(['user' => $userSessionData]);

            return response()->json([
                'success' => true,
                'message' => 'Vos informations ont été mises à jour avec succès.'
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour de votre profil.'
            ], 500);
        }
    }
}