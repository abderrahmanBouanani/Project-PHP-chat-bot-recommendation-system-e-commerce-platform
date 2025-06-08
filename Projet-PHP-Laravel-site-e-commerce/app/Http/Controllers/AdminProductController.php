<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    /**
     * Affiche la liste des produits
     */
    public function index(Request $request)
    {
        try {
            $searchTerm = $request->input('search', '');
            $category = $request->input('category', '');
            $sort = $request->input('sort', 'name');

            $query = Produit::with('vendeur');

            // Appliquer le filtre de recherche
            if (!empty($searchTerm)) {
                $query->where('nom', 'like', "%{$searchTerm}%");
            }

            // Filtrer par catégorie
            if (!empty($category)) {
                $query->where('categorie', $category);
            }

            // Appliquer le tri
            switch ($sort) {
                case 'price-asc':
                    $query->orderBy('prix_unitaire', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('prix_unitaire', 'desc');
                    break;
                default:
                    $query->orderBy('nom', 'asc');
                    break;
            }

            $produits = $query->paginate(8);
            $categories = Produit::select('categorie')->distinct()->pluck('categorie');

            // Si c'est une requête AJAX, retourner une réponse JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $produits->items(),
                    'total' => $produits->total(),
                    'current_page' => $produits->currentPage(),
                    'per_page' => $produits->perPage(),
                    'last_page' => $produits->lastPage()
                ]);
            }

            return view('admin-interface.produits', [
                'page' => 'ShopAll - Produits',
                'produits' => $produits,
                'categories' => $categories,
                'currentSearch' => $searchTerm,
                'currentCategory' => $category,
                'currentSort' => $sort
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse d'erreur en JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors du chargement des produits.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            // Rediriger avec un message d'erreur pour les requêtes normales
            return back()->with('error', 'Une erreur est survenue lors du chargement des produits.');
        }
    }

    /**
     * Affiche les détails d'un produit spécifique
     */
    public function show($id)
    {
        try {
            $produit = Produit::with('vendeur')->findOrFail($id);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $produit
                ]);
            }

            return view('admin-interface.produits.show', [
                'page' => 'ShopAll - Détails du produit',
                'produit' => $produit
            ]);
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la récupération des détails du produit.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue lors de la récupération des détails du produit.');
        }
    }

    /**
     * Filtre les produits en fonction des critères de recherche
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('search', '');
            $category = $request->input('category', '');
            $sort = $request->input('sort', 'name');
            $page = $request->input('page', 1);
            $perPage = $request->input('limit', 8);

            $query = Produit::with('vendeur');

            // Appliquer le filtre de recherche
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nom', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // Filtrer par catégorie
            if (!empty($category)) {
                $query->where('categorie', $category);
            }

            // Appliquer le tri
            switch ($sort) {
                case 'price-asc':
                    $query->orderBy('prix_unitaire', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('prix_unitaire', 'desc');
                    break;
                case 'stock-asc':
                    $query->orderBy('quantite', 'asc');
                    break;
                case 'stock-desc':
                    $query->orderBy('quantite', 'desc');
                    break;
                default:
                    $query->orderBy('nom', 'asc');
                    break;
            }

            // Exécuter la requête avec pagination
            $produits = $query->paginate($perPage, ['*'], 'page', $page);

            // Vérifier si des résultats ont été trouvés
            if ($produits->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'total' => 0,
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'last_page' => 1,
                    'message' => 'Aucun produit trouvé pour les critères de recherche spécifiés.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $produits->items(),
                'total' => $produits->total(),
                'current_page' => $produits->currentPage(),
                'per_page' => $produits->perPage(),
                'last_page' => $produits->lastPage()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la recherche des produits: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la recherche des produits.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir la liste des catégories de produits
     */
    public function getCategories()
    {
        try {
            $categories = Produit::select('categorie')->distinct()->get()->pluck('categorie');
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la récupération des catégories.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            \Log::info('Tentative de suppression du produit', ['produit_id' => $id]);

            // Récupérer le produit
            $produit = Produit::find($id);

            // Vérifier si le produit existe
            if (!$produit) {
                \Log::error('Produit non trouvé', ['produit_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ], 404);
            }

            // Vérifier si le produit est lié à des commandes
            $commandesCount = DB::table('commande_produit')->where('produit_id', $id)->count();
            if ($commandesCount > 0) {
                \Log::info('Tentative de suppression d\'un produit lié à des commandes', [
                    'produit_id' => $id,
                    'commandes_count' => $commandesCount
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit ne peut pas être supprimé car il est associé à ' . $commandesCount . ' commande(s).'
                ], 400);
            }

            // Supprimer l'image du produit si elle existe
            if ($produit->image) {
                $imagePath = 'public/' . $produit->image;
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                    \Log::info('Image du produit supprimée', ['image_path' => $imagePath]);
                }
            }

            // Supprimer le produit
            $produit->delete();
            \Log::info('Produit supprimé avec succès', ['produit_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du produit', [
                'produit_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du produit: ' . $e->getMessage()
            ], 500);
        }
    }
}
