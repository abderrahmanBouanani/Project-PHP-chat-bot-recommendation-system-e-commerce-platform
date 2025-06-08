@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .product-image {
    position: relative;
    display: inline-block;
  }

  .stock-badge {
    position: absolute;
    top: 0;
    right: 0;
    padding: 2px 6px;
    font-size: 0.75rem;
    font-weight: bold;
    border-radius: 3px;
    color: white;
  }

  .stock-badge.out-of-stock {
    background-color: #dc3545;
  }

  .stock-badge.low-stock {
    background-color: #ffc107;
    color: #000;
  }

  .grayscale {
    filter: grayscale(100%);
    opacity: 0.7;
  }

  .btn-group {
    white-space: nowrap;
  }

  .btn-group .btn {
    padding: 0.25rem 0.5rem;
  }

  .alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    min-width: 300px;
    max-width: 500px;
  }
</style>

<div class="main-content">
      <h1 class="h3 mb-4">Liste des Produits</h1>

      <form id="searchForm" method="GET" action="{{ route('admin.produits') }}" class="mb-4">
        <div class="row g-3">
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              id="searchInput"
              name="search"
              value="{{ $currentSearch ?? '' }}"
              placeholder="Rechercher un produit"
            />
          </div>
          <div class="col-md-3">
            <select class="form-select" id="categoryFilter" name="category">
              <option value="">Toutes les catégories</option>
              @foreach($categories as $categorie)
                <option value="{{ $categorie }}" {{ $currentCategory == $categorie ? 'selected' : '' }}>{{ $categorie }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="sortSelect" name="sort">
              <option value="name" {{ ($currentSort ?? 'name') == 'name' ? 'selected' : '' }}>Trier par nom</option>
              <option value="price-asc" {{ ($currentSort ?? '') == 'price-asc' ? 'selected' : '' }}>Prix croissant</option>
              <option value="price-desc" {{ ($currentSort ?? '') == 'price-desc' ? 'selected' : '' }}>Prix décroissant</option>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Appliquer</button>
          </div>
        </div>
      </form>

      <table class="table table-hover">
        <thead>
          <tr>
            <th><i class="bi bi-image me-1"></i>Image</th>
            <th><i class="bi bi-tag me-1"></i>Nom</th>
            <th><i class="bi bi-grid me-1"></i>Catégorie</th>
            <th><i class="bi bi-currency-dollar me-1"></i>Prix</th>
            <th><i class="bi bi-shop me-1"></i>Vendeur</th>
            <th><i class="bi bi-gear me-1"></i>Actions</th>
          </tr>
        </thead>
        <tbody id="productsList">
          @if(count($produits) > 0)
            @foreach($produits as $produit)
            <tr>
              <td>
                @if($produit->image)
                <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="product-image" style="width: 50px; height: auto;">
                @else
                <img src="{{ asset('images/product-placeholder.png') }}" alt="{{ $produit->nom }}" class="product-image" style="width: 50px; height: auto;">
                @endif
              </td>
              <td>{{ $produit->nom }}</td>
              <td>{{ $produit->categorie }}</td>
              <td>{{ number_format($produit->prix_unitaire, 2) }} DH</td>
              <td>{{ optional($produit->vendeur)->nom ?? 'Non spécifié' }}</td>
              <td>
                <div class="btn-group" role="group">
                  <i class="fas fa-trash-alt text-danger delete-btn" 
                     style="cursor: pointer; font-size: 1.2rem; padding: 8px;"
                     onclick="deleteProduct({{ $produit->id }})"
                     title="Supprimer"></i>
                </div>
              </td>
            </tr>
            @endforeach
          @else
            <tr>
              <td colspan="6" class="text-center">Aucun produit trouvé</td>
            </tr>
          @endif
        </tbody>
      </table>

      <!-- Conteneur pour la pagination dynamique -->
      <div class="pagination-container d-flex justify-content-center mt-4"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/admin/admin-boutique.js') }}"></script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
