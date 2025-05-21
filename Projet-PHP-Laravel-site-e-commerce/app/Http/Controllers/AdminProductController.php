<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class AdminProductController extends Controller
{
    /**
     * Affiche la liste des produits
     */
    public function index(Request $request)
    {
        $produits = Produit::paginate(8);

        return view('admin-interface.produits', [
            'page' => 'ShopAll - Produits',
            'produits' => $produits
        ]);
    }

    /**
     * Filtre les produits en fonction des critères de recherche
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $category = $request->input('category', '');
        $sort = $request->input('sort', 'name');

        $query = Produit::query();

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

        $perPage = 8;

        $produits = $query->paginate($perPage, ['*'], 'page', $request->input('page', 1));

        return response()->json([
            'data' => $produits->items(),
            'total' => $produits->total(),
            'current_page' => $produits->currentPage(),
            'per_page' => $produits->perPage(),
            'last_page' => $produits->lastPage()
        ]);
    }

    /**
     * Obtenir la liste des catégories de produits
     */
    public function getCategories()
    {
        $categories = Produit::select('categorie')->distinct()->get()->pluck('categorie');

        return response()->json($categories);
    }
}
