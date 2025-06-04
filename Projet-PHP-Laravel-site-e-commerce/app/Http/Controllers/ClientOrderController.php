<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;

class ClientOrderController extends Controller
{
    /**
     * Affiche l'historique des commandes du client
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session('user') || !isset(session('user')['id'])) {
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à votre historique de commandes.');
        }
        
        // Vérifier si l'utilisateur est un client
        if (session('user')['type'] !== 'client') {
            return redirect('/')->with('error', 'Accès réservé aux clients.');
        }
        
        // Récupérer l'ID du client connecté
        $clientId = session('user')['id'];
        
        // Récupérer les commandes du client avec pagination (8 par page)
        $commandes = Commande::where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        
        return view('client-interface.commandes', [
            'page' => 'ShopAll - Mes Commandes',
            'commandes' => $commandes
        ]);
    }
    
    /**
     * Affiche les détails d'une commande spécifique
     */
    public function show($id)
    {
        // Vérifier si l'utilisateur est connecté
        if (!session('user') || !isset(session('user')['id'])) {
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à vos commandes.');
        }
        
        // Vérifier si l'utilisateur est un client
        if (session('user')['type'] !== 'client') {
            return redirect('/')->with('error', 'Accès réservé aux clients.');
        }
        
        // Récupérer l'ID du client connecté
        $clientId = session('user')['id'];
        
        // Récupérer la commande avec vérification qu'elle appartient bien au client
        $commande = Commande::where('id', $id)
            ->where('client_id', $clientId)
            ->firstOrFail();
        
        // Récupérer les produits de la commande
        $produits = DB::table('commande_produit')
            ->join('produits', 'commande_produit.produit_id', '=', 'produits.id')
            ->where('commande_produit.commande_id', $id)
            ->select('produits.*', 'commande_produit.quantite')
            ->get();
        
        return view('client-interface.commande-details', [
            'page' => 'ShopAll - Détails de ma commande',
            'commande' => $commande,
            'produits' => $produits
        ]);
    }
}
