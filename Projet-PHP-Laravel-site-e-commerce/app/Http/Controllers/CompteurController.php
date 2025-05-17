<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compteur;
use Illuminate\Support\Facades\Log;

class CompteurController extends Controller
{
    public function trackClick(Request $request)
    {
        try {
            // Récupérer l'ID du client depuis la requête
            $clientId = $request->input('client_id', 0);
            
            // Log pour déboguer
            Log::info('ID client reçu dans la requête', ['client_id' => $clientId]);
            
            // Récupérer la catégorie de la requête
            $categorie = $request->input('categorie', 'categorie_inconnue');
            
            // Vérifier si un enregistrement existe déjà
            $compteur = Compteur::where('client_id', $clientId)
                ->where('categorie', $categorie)
                ->first();
            
            if ($compteur) {
                // Incrémenter le compteur existant
                $compteur->nombre_clique += 1;
                $compteur->save();
                
                Log::info('Compteur incrémenté', [
                    'client_id' => $clientId,
                    'categorie' => $categorie,
                    'nombre_clique' => $compteur->nombre_clique
                ]);
            } else {
                // Créer un nouveau compteur
                $compteur = new Compteur();
                $compteur->client_id = $clientId;
                $compteur->categorie = $categorie;
                $compteur->nombre_clique = 1;
                $compteur->save();
                
                Log::info('Nouveau compteur créé', [
                    'client_id' => $clientId,
                    'categorie' => $categorie,
                    'nombre_clique' => 1
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Clic enregistré avec succès',
                'client_id' => $clientId,
                'categorie' => $categorie,
                'nombre_clique' => $compteur->nombre_clique
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement du clic', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}
