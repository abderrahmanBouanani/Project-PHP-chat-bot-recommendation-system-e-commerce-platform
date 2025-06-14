<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ProduitController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session('user') || !isset(session('user')['id'])) {
            return redirect('/')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        
        // Vérifier si l'utilisateur est un vendeur
        if (session('user')['type'] !== 'vendeur') {
            return redirect('/')->with('error', 'Accès réservé aux vendeurs.');
        }
        
        $produits = Produit::where('vendeur_id', session('user')['id'])->paginate(8);
        return view('vendeur-interface.vendeurBoutique', compact('produits'), ['page' => 'ShopAll - Ma Boutique']);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    //Création
     public function store(Request $request)
     {
         // Vérifier que l'utilisateur est connecté et est un vendeur
         if (!session('user') || session('user')['type'] !== 'vendeur') {
             return redirect()->back()->with('error', 'Accès non autorisé.');
         }

         // Validation des données du formulaire
         $request->validate([
             'nom' => 'required|string|max:255',
             'prix_unitaire' => 'required|numeric',
             'categorie' => 'required|string|max:255',
             'description' => 'required|string',
             'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048'
         ]);

         // Création d'un nouveau produit
         $produit = new Produit();
         $produit->nom = $request->input('nom');
         $produit->prix_unitaire = $request->input('prix_unitaire');
         $produit->categorie = $request->input('categorie');
         $produit->description = $request->input('description');
         $produit->quantite = $request->input('quantite');

         // Gestion de l'image (si présente)
         if ($request->hasFile('image')) {
             // Stocke l'image dans le dossier 'storage/app/public/images' et récupère le chemin
             $imagePath = $request->file('image')->store('images', 'public');
             $produit->image = $imagePath;
         }

         // Sauvegarde du produit dans la base de données
         $produit->vendeur_id = session('user')['id'];
         $produit->save();

         return redirect()->back()->with('success', 'Produit ajouté avec succès !');
     }


    //Suppression
    public function destroy($id){
        $produit = Produit::findOrFail($id);
        $produit->quantite = 0;
        $produit->save();

        return redirect()->back()->with('success', 'Produit mis hors stock avec succès.');
    }

    /**
     * Met à jour la quantité d'un produit
     */
    public function updateQuantity(Request $request, $id)
    {
        try {
            $request->validate([
                'quantite' => 'required|integer|min:0'
            ]);

            $produit = Produit::findOrFail($id);
            
            // Vérifier que le produit appartient bien au vendeur connecté
            if ($produit->vendeur_id !== session('user')['id']) {
                return redirect()->back()->with('error', 'Non autorisé à modifier ce produit');
            }

            $produit->quantite = $request->quantite;
            $produit->save();

            return redirect()->back()->with('success', 'Quantité mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la quantité: ' . $e->getMessage());
        }
    }

    //Récuperation
    public function getProduits(Request $request){
        try {
            $perPage = $request->input('limit', 8);
            $page = $request->input('page', 1);

            $produits = Produit::with(['vendeur'])->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $produits->items(),
                'total' => $produits->total(),
                'current_page' => $produits->currentPage(),
                'per_page' => $produits->perPage(),
                'last_page' => $produits->lastPage()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement des produits: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des produits',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    

}
