<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Facturation;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Créer une nouvelle commande avec les détails de facturation
     */
    public function createOrder(Request $request)
    {
        // Valider les données reçues
        $validator = Validator::make($request->all(), [
            'customer.first_name' => 'required|string|max:255',
            'customer.last_name' => 'required|string|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.phone' => 'required|string|max:20',
            'customer.address' => 'required|string|max:255',
            'customer.city' => 'required|string|max:100',
            'customer.state' => 'required|string|max:100',
            'customer.postal_code' => 'required|string|max:20',
            'payment_method' => 'sometimes|string|in:carte,espece',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Récupérer l'ID du client connecté
            $clientId = session('user')['id'];
            
            if (!$clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non connecté'
                ], 401);
            }

            // Récupérer les totaux du panier depuis la session
            $subtotal = session('cart_subtotal', 0);
            $discount = session('cart_discount', 0);
            $total = session('cart_total', 0);

            // Créer la commande
            $commande = Commande::create([
                'client_id' => $clientId,
                'adresse' => $request->input('customer.address'),
                'statut' => 'En attente',
                'methode_paiement' => $request->input('payment_method', 'espece'),
                'total' => $total,
                'reduction' => $discount
            ]);

            // Créer la facturation liée à la commande
            $facturation = Facturation::create([
                'commande_id' => $commande->id,
                'nom' => $request->input('customer.last_name'),
                'prenom' => $request->input('customer.first_name'),
                'ville' => $request->input('customer.city'),
                'adresse' => $request->input('customer.address'),
                'region' => $request->input('customer.state'),
                'code_postal' => $request->input('customer.postal_code'),
                'email' => $request->input('customer.email'),
                'telephone' => $request->input('customer.phone'),
                'note_commande' => $request->input('customer.notes') ?? '',
            ]);

            // Créer le paiement lié à la commande
            $typePaiement = $request->input('payment_method', 'espece');
            $paiementData = [
                'commande_id' => $commande->id,
                'montant' => $total,
                'type' => $typePaiement,
                'numero_carte' => null,
                'date_expiration' => null,
                'email_paypal' => null
            ];
            // Si le type est 'carte', on peut ajouter la logique pour récupérer les infos carte si besoin
            if ($typePaiement === 'carte') {
                $paiementData['numero_carte'] = $request->input('numero_carte');
                $paiementData['date_expiration'] = $request->input('date_expiration');
            }
            // Si le type est 'paypal', on peut ajouter la logique pour récupérer l'email paypal si besoin
            if ($typePaiement === 'paypal') {
                $paiementData['email_paypal'] = $request->input('email_paypal');
            }
            \App\Models\Paiement::create($paiementData);

            // Récupérer les articles du panier pour les associer à la commande
            $cartItems = Cart::where('client_id', $clientId)->get();
            
            Log::info('Creating order with ' . count($cartItems) . ' items for client ' . $clientId);
            
            foreach ($cartItems as $item) {
                // Ajouter chaque produit à la commande
                try {
                    DB::table('commande_produit')->insert([
                        'commande_id' => $commande->id,
                        'produit_id' => $item->produit_id,
                        'quantite' => $item->quantite ?? 1
                    ]);
                    
                    Log::info('Added product ' . $item->produit_id . ' to order ' . $commande->id);
                } catch (\Exception $e) {
                    Log::error('Error adding product to order: ' . $e->getMessage());
                }
            }
            
            // Vider le panier après la commande
            Cart::where('client_id', $clientId)->delete();
            
            // Nettoyer les données de session liées au panier
            session()->forget(['cart_subtotal', 'cart_discount', 'cart_total', 'cart_coupon_code']);
            
            // Stocker l'ID de la commande dans la session pour la page de remerciement
            session(['last_order_id' => $commande->id]);

            return response()->json([
                'success' => true,
                'message' => 'Commande créée avec succès',
                'order_id' => $commande->id
            ]);
        } catch (\Exception $e) {
            Log::error('Order creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()
            ], 500);
        }
    }
}
