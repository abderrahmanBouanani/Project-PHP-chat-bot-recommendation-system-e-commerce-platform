@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<div class="main-content">
      <h1 class="h3 mb-4">Liste des Produits</h1>

      <div class="search-sort-container">
        <div class="row g-3">
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              id="searchInput"
              placeholder="Rechercher un produit"
            />
          </div>
          <div class="col-md-4">
            <select class="form-select" id="categoryFilter">
              <option value="">Toutes les catégories</option>
              @foreach($produits->pluck('categorie')->unique() as $categorie)
                <option value="{{ $categorie }}">{{ $categorie }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-select" id="sortSelect">
              <option value="name">Trier par nom</option>
              <option value="price-asc">Prix croissant</option>
              <option value="price-desc">Prix décroissant</option>
            </select>
          </div>
        </div>
      </div>

      <table class="table table-hover">
        <thead>
          <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Prix</th>
            <th>Vendeur</th>
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
            </tr>
            @endforeach
          @else
            <tr>
              <td colspan="5" class="text-center">Aucun produit trouvé</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        // Fonctions de filtrage et tri
        function searchProducts() {
          const searchTerm = document.getElementById("searchInput").value.toLowerCase();
          const categoryFilter = document.getElementById("categoryFilter").value;
          
          // Appel à l'API de recherche
          fetch(`/api/admin/produit/search?search=${searchTerm}&category=${categoryFilter}&sort=${document.getElementById("sortSelect").value}`)
            .then(response => response.json())
            .then(data => {
              displayProducts(data);
            })
            .catch(error => {
              console.error('Error searching products:', error);
            });
        }

        function displayProducts(products) {
          const productsList = document.getElementById("productsList");
          productsList.innerHTML = "";
          
          if (products.length === 0) {
            productsList.innerHTML = `<tr><td colspan="5" class="text-center">Aucun produit trouvé</td></tr>`;
            return;
          }
          
          products.forEach(product => {
            const imageUrl = product.image 
              ? `{{ asset('storage') }}/${product.image}` 
              : `{{ asset('images/product-placeholder.png') }}`;
              
            const row = `
              <tr>
                <td><img src="${imageUrl}" alt="${product.nom}" class="product-image" style="width: 50px; height: auto;"></td>
                <td>${product.nom}</td>
                <td>${product.categorie}</td>
                <td>${parseFloat(product.prix_unitaire).toFixed(2)} DH</td>
                <td>${product.vendeur ? product.vendeur.nom : 'Non spécifié'}</td>
              </tr>
            `;
            productsList.insertAdjacentHTML("beforeend", row);
          });
        }

        // Ajout des écouteurs d'événements
        document.getElementById("searchInput").addEventListener("input", searchProducts);
        document.getElementById("categoryFilter").addEventListener("change", searchProducts);
        document.getElementById("sortSelect").addEventListener("change", searchProducts);
      });
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
