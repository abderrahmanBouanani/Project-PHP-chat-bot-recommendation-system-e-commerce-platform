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
        $produits = Produit::where('vendeur_id', session('user')['id'])->get();
        return view('vendeur-interface.vendeurBoutique', compact('produits'),['page' => 'ShopAll - Ma Boutique']);
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
             'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048'
         ]);
     
         // Création d'un nouveau produit
         $produit = new Produit();
         $produit->nom = $request->input('nom');
         $produit->prix_unitaire = $request->input('prix_unitaire');
         $produit->categorie = $request->input('categorie');
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
    $produit->delete();

    return redirect()->back()->with('success', 'Produit supprimé avec succès.');
}
    //Récuperation
    public function getProduits(){
        try {
            $produits = Produit::with(['vendeur'])->get();
            return response()->json([
                'success' => true,
                'data' => $produits
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
