<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\User;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
   /**
    * Affiche la liste des commandes
    */
   public function index()
   {
       $commandes = Commande::with(['client'])->get();
       
       return view('admin-interface.commandes', [
           'page' => 'ShopAll - Commandes',
           'commandes' => $commandes
       ]);
   }

   /**
    * Récupère les produits d'une commande spécifique
    */
   public function getProducts($commandeId)
   {
       try {
           $produits = DB::table('commande_produit')
               ->join('produits', 'commande_produit.produit_id', '=', 'produits.id')
               ->where('commande_produit.commande_id', $commandeId)
               ->select('produits.*', 'commande_produit.quantite')
               ->get();

           return response()->json($produits->toArray());
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'error' => 'Erreur lors du chargement des produits'
           ], 500);
       }
   }
   
   /**
    * Affiche les détails d'une commande spécifique
    */
   public function show($id)
   {
       $commande = Commande::with(['client', 'facturation', 'paiement'])->findOrFail($id);
       
       // Récupérer les produits de la commande sans utiliser les timestamps
       $produits = DB::table('commande_produit')
           ->join('produits', 'commande_produit.produit_id', '=', 'produits.id')
           ->where('commande_produit.commande_id', $id)
           ->select('produits.*', 'commande_produit.quantite')
           ->get();
       
       return view('admin-interface.commande-details', [
           'page' => 'ShopAll - Détails commande',
           'commande' => $commande,
           'produits' => $produits
       ]);
   }
   
   /**
    * Met à jour le statut d'une commande
    */
   public function updateStatus(Request $request, $id)
   {
       $commande = Commande::findOrFail($id);
       
       // Mettre à jour le statut
       $commande->statut = $request->input('statut');
       $commande->save();
       
       return redirect()->back()->with('success', 'Statut de la commande mis à jour avec succès');
   }
   
   /**
    * Recherche des commandes en fonction des critères
    */
   public function search(Request $request)
   {
       $searchTerm = $request->input('search', '');
       $sort = $request->input('sort', 'date-desc');
       $status = $request->input('status', 'all');
       
       $query = Commande::with(['client']);
       
       // Recherche par ID ou nom du client
       if (!empty($searchTerm)) {
           if (is_numeric($searchTerm)) {
               $query->where('id', $searchTerm);
           } else {
               $query->whereHas('client', function($q) use ($searchTerm) {
                   $q->where('nom', 'like', "%{$searchTerm}%")
                     ->orWhere('prenom', 'like', "%{$searchTerm}%");
               });
           }
       }
       
       // Filtrer par statut
       if ($status !== 'all') {
           $query->where('statut', $status);
       }
       
       // Appliquer le tri
       switch ($sort) {
           case 'date-asc':
               $query->orderBy('created_at', 'asc');
               break;
           case 'total-desc':
               $query->orderBy('total', 'desc');
               break;
           case 'total-asc':
               $query->orderBy('total', 'asc');
               break;
           default: // date-desc
               $query->orderBy('created_at', 'desc');
               break;
       }
       
       $commandes = $query->get();
       
       return response()->json($commandes);
   }
}
