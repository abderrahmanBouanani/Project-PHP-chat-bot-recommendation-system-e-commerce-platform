<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class CartController extends Controller
{
    public function addToCart(Request $request)
{
    $request->validate([
        'produit_id' => 'required|exists:produits,id',
        'nom_produit' => 'required|string',
        'image' => 'required|string',
        'prix' => 'required|numeric'
    ]);

    // Récupérer l'ID du client connecté
    $clientId = session('user')['id'];

    if (!$clientId) {
        return response()->json([
            'success' => false,
            'message' => 'Veuillez vous connecter pour ajouter des produits au panier'
        ], 401);
    }

    // Vérifier si le produit existe déjà dans le panier du client
    $existingCartItem = Cart::where('client_id', $clientId)
        ->where('produit_id', $request->produit_id)
        ->first();

    if ($existingCartItem) {
        // Le produit existe, on augmente la quantité
        $existingCartItem->increment('quantite', 1);
    } else {
        // Le produit n'existe pas, on l'ajoute avec quantite = 1
        Cart::create([
            'client_id' => $clientId,
            'produit_id' => $request->produit_id,
            'nom_produit' => $request->nom_produit,
            'image' => $request->image,
            'prix' => $request->prix,
            'quantite' => 1
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Produit ajouté au panier avec succès'
    ]);
}


    // Méthode pour récupérer tous les éléments du panier
    public function getCart()
    {
        try {
            \Log::info('Début de getCart');
            \Log::info('Session complète:', session()->all());
            
            // Vérifier si l'utilisateur est connecté
            if (!session('user')) {
                \Log::warning('Utilisateur non connecté dans getCart');
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez vous connecter pour accéder à votre panier'
                ], 401);
            }

            // Récupérer l'ID du client
            $clientId = session('user')['id'];
            \Log::info('ID du client: ' . $clientId);
            
            // Récupérer les éléments du panier
            $cartItems = Cart::where('client_id', $clientId)->get();
            \Log::info('Produits du panier: ' . $cartItems);
            
            return response()->json([
                'success' => true,
                'data' => $cartItems
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement du panier: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement du panier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Méthode pour mettre à jour la quantité d'un élément du panier
public function updateCartItem(Request $request, $id)
{
    try {
        $cartItem = Cart::findOrFail($id);

        // Vérifier que l'élément appartient bien au client actuel
        $clientId = session('user')['id'];
        if ($cartItem->client_id != $clientId) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // Mettre à jour la quantité
        $cartItem->quantite = $request->input('quantity');
        $cartItem->save();

        // Retourner une réponse JSON
        return response()->json([
            'success' => true,
            'message' => 'Quantité mise à jour',
            'updatedQuantity' => $cartItem->quantite,
            'total' => $cartItem->prix * $cartItem->quantite
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du panier: ' . $e->getMessage()
        ], 500);
    }
}

    
    // Méthode pour supprimer un élément du panier
    public function removeCartItem($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            
            // Vérifier que l'élément appartient bien au client actuel
            $clientId = session('user')['id'];
            if ($cartItem->client_id != $clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé'
                ], 403);
            }
            
            // Supprimer l'élément
            $cartItem->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Élément supprimé du panier avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'élément: ' . $e->getMessage()
            ], 500);
        }
    }

    // Méthode pour calculer le total du panier avec réduction
    public function getCartTotal()
    {
        try {
            \Log::info('Début de getCartTotal');
            
            // Récupérer l'ID du client
            $clientId = session('user')['id'];
            \Log::info('ID du client: ' . $clientId);
            
            // Récupérer les éléments du panier
            $cartItems = Cart::where('client_id', $clientId)->get();
            \Log::info('Produits du panier: ' . $cartItems);
            
            // Calculer le sous-total
            $subtotal = $cartItems->sum(function($item) {
                return $item->prix * $item->quantite;
            });
            \Log::info('Sous-total calculé: ' . $subtotal);
            
            // Récupérer la réduction du coupon si elle existe
            $discount = 0;
            if (session()->has('coupon')) {
                $coupon = session()->get('coupon');
                $discount = $coupon['discount_amount'];
                \Log::info('Réduction appliquée: ' . $discount);
            }
            
            // Calculer le total
            $total = $subtotal - $discount;
            \Log::info('Total calculé: ' . $total);
            
            return response()->json([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'coupon' => session()->get('coupon')
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans getCartTotal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des totaux',
                'error' => $e->getMessage()
            ], 500);
        }
    }





/**
 * Mettre à jour les totaux du panier dans la session
 * Cette méthode est appelée depuis le JavaScript pour stocker les totaux
 */
public function updateTotals(Request $request)
{
    // Valider les données
    $validated = $request->validate([
        'subtotal' => 'required|numeric',
        'discount' => 'required|numeric',
        'total' => 'required|numeric',
        'coupon_code' => 'nullable|string'
    ]);

    // Stocker les totaux dans la session
    session(['cart_subtotal' => $validated['subtotal']]);
    session(['cart_discount' => $validated['discount']]);
    session(['cart_total' => $validated['total']]);
    
    if (isset($validated['coupon_code'])) {
        session(['cart_coupon_code' => $validated['coupon_code']]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Totaux mis à jour avec succès',
        'data' => [
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'],
            'total' => $validated['total'],
            'coupon_code' => $validated['coupon_code'] ?? null
        ]
    ]);
}

/**
 * Récupérer les totaux du panier depuis la session
 * Cette méthode est appelée depuis le JavaScript pour afficher les totaux sur la page de checkout
 */
public function getTotals()
{
    return response()->json([
        'subtotal' => session('cart_subtotal', 0),
        'discount' => session('cart_discount', 0),
        'total' => session('cart_total', 0),
        'coupon_code' => session('cart_coupon_code')
    ]);
}
}