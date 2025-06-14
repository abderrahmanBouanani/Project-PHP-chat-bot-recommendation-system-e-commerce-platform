<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\User;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminOrderController extends Controller
{
    /**
     * Vérifie si l'utilisateur est en mode lecture seule
     *
     * @return bool
     */
    protected function isReadOnly()
    {
        return !session()->has('user');
    }
    
    /**
     * Vérifie si l'utilisateur a les droits d'édition
     *
     * @return \Illuminate\Http\Response|null
     */
    protected function checkEditRights()
    {
        if ($this->isReadOnly()) {
            return redirect()->back()->with('error', 'Action non autorisée en mode lecture seule. Veuillez vous connecter.');
        }
        return null;
    }
   /**
    * Affiche la liste des commandes
    */
   public function index(Request $request)
   {
       // Vérification de la session
       if (!session('user') || !isset(session('user')['id'])) {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Veuillez vous connecter pour effectuer cette action.'
               ], 401);
           }
           return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
       }

       // Vérification du type d'utilisateur
       if (session('user')['type'] !== 'admin') {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Accès non autorisé.'
               ], 403);
           }
           return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
       }

       try {
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

           $commandes = $query->paginate(6);
           
           // Si c'est une requête AJAX, retourner une réponse JSON
           if ($request->ajax() || $request->wantsJson()) {
               return response()->json([
                   'success' => true,
                   'data' => $commandes->items(),
                   'total' => $commandes->total(),
                   'current_page' => $commandes->currentPage(),
                   'per_page' => $commandes->perPage(),
                   'last_page' => $commandes->lastPage()
               ]);
           }

           return view('admin-interface.commandes', [
               'page' => 'ShopAll - Commandes',
               'commandes' => $commandes,
               'currentSearch' => $searchTerm,
               'currentStatus' => $status,
               'currentSort' => $sort
           ]);
       } catch (\Exception $e) {
           // En cas d'erreur, retourner une réponse d'erreur en JSON
           if ($request->ajax() || $request->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Une erreur est survenue lors du chargement des commandes.',
                   'error' => $e->getMessage()
               ], 500);
           }
           
           // Rediriger avec un message d'erreur pour les requêtes normales
           return back()->with('error', 'Une erreur est survenue lors du chargement des commandes.');
       }
   }

   /**
    * Récupère les produits d'une commande spécifique
    */
   public function getProducts($commandeId)
   {
       // Vérification de la session
       if (!session('user') || !isset(session('user')['id'])) {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Veuillez vous connecter pour effectuer cette action.'
               ], 401);
           }
           return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
       }

       // Vérification du type d'utilisateur
       if (session('user')['type'] !== 'admin') {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Accès non autorisé.'
               ], 403);
           }
           return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
       }

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
       // Vérification de la session
       if (!session('user') || !isset(session('user')['id'])) {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Veuillez vous connecter pour effectuer cette action.'
               ], 401);
           }
           return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
       }

       // Vérification du type d'utilisateur
       if (session('user')['type'] !== 'admin') {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Accès non autorisé.'
               ], 403);
           }
           return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
       }

       try {
           $commande = Commande::with([
               'client', 
               'facturation', 
               'paiement',
               'produits' => function($query) {
                   $query->withPivot('quantite');
               }
           ])->findOrFail($id);

           // Si c'est une requête AJAX, retourner une réponse JSON
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => true,
                   'commande' => $commande
               ]);
           }

           // Sinon, retourner la vue
           return view('admin-interface.commande-details', [
               'page' => 'ShopAll - Détails commande',
               'commande' => $commande
           ]);
       } catch (\Exception $e) {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Erreur lors de la récupération des détails de la commande: ' . $e->getMessage()
               ], 500);
           }
           return back()->with('error', 'Erreur lors de la récupération des détails de la commande.');
       }
   }

   /**
    * Met à jour le statut d'une commande
    */
   public function updateStatus(Request $request, $id)
   {
       // Vérification de la session
       if (!session('user') || !isset(session('user')['id'])) {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Veuillez vous connecter pour effectuer cette action.'
               ], 401);
           }
           return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
       }

       // Vérification du type d'utilisateur
       if (session('user')['type'] !== 'admin') {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Accès non autorisé.'
               ], 403);
           }
           return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
       }

       try {
           $commande = Commande::with('produits')->findOrFail($id);
           $nouveauStatut = $request->input('statut');

           \Log::info('Statut actuel et nouveau statut', [
               'ancien_statut' => $commande->statut, 
               'nouveau_statut' => $nouveauStatut
           ]);

           // Si la commande est confirmée, on diminue les quantités des produits
           if (strtolower($nouveauStatut) === 'confirmée' && strtolower($commande->statut) !== 'confirmée') {
               \Log::info('Mise à jour des quantités des produits pour la commande', ['commande_id' => $id]);
               
               foreach ($commande->produits as $produit) {
                   // Récupérer la quantité commandée depuis la table pivot
                   $quantiteCommandee = $produit->pivot->quantite;
                   $ancienneQuantite = $produit->quantite;
                   
                   \Log::info('Avant mise à jour du produit', [
                       'produit_id' => $produit->id,
                       'ancienne_quantite' => $ancienneQuantite,
                       'quantite_commandee' => $quantiteCommandee
                   ]);
                   
                   // Mettre à jour la quantité en stock
                   $produit->decrement('quantite', $quantiteCommandee);
                   
                   // Recharger le produit pour obtenir la nouvelle quantité
                   $produit->refresh();
                   
                   \Log::info('Après mise à jour du produit', [
                       'produit_id' => $produit->id,
                       'nouvelle_quantite' => $produit->quantite
                   ]);
                   
                   // Vérifier si le stock est épuisé
                   if ($produit->quantite < 0) {
                       $produit->quantite = 0;
                       $produit->save();
                       $produit->refresh();
                       \Log::info('Stock épuisé pour le produit', ['produit_id' => $produit->id]);
                   }
               }
           }

           // Mettre à jour le statut de la commande
           $commande->statut = $nouveauStatut;
           $commande->save();

           return redirect()->back()->with('success', 'Statut de la commande mis à jour avec succès');
       } catch (\Exception $e) {
           return redirect()->back()->with('error', 'Erreur lors de la mise à jour du statut de la commande: ' . $e->getMessage());
       }
   }

   /**
    * Recherche des commandes en fonction des critères
    */
   public function search(Request $request)
   {
       // Vérification de la session
       if (!session('user') || !isset(session('user')['id'])) {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Veuillez vous connecter pour effectuer cette action.'
               ], 401);
           }
           return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
       }

       // Vérification du type d'utilisateur
       if (session('user')['type'] !== 'admin') {
           if (request()->ajax() || request()->wantsJson()) {
               return response()->json([
                   'success' => false,
                   'message' => 'Accès non autorisé.'
               ], 403);
           }
           return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
       }

       try {
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

           $perPage = 8;

           $commandes = $query->paginate($perPage, ['*'], 'page', $request->input('page', 1));

           return response()->json([
               'data' => $commandes->items(),
               'total' => $commandes->total(),
               'current_page' => $commandes->currentPage(),
               'per_page' => $commandes->perPage(),
               'last_page' => $commandes->lastPage()
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Erreur lors de la recherche des commandes: ' . $e->getMessage()
           ], 500);
       }
   }
   
   /**
    * Supprime une commande
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
       // Vérifier les droits d'édition
       $check = $this->checkEditRights();
       if ($check) {
           return $check;
       }
       
       try {
           $commande = Commande::findOrFail($id);
           $commande->delete();
           
           return redirect()->route('admin.commandes.index')
               ->with('success', 'Commande supprimée avec succès');
               
       } catch (\Exception $e) {
           return redirect()->back()
               ->with('error', 'Erreur lors de la suppression de la commande: ' . $e->getMessage());
       }
   }
}
