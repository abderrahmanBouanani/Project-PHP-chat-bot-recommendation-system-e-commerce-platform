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

      <!-- Pagination Links (pour l'affichage initial) -->
      <div class="d-flex justify-content-center mt-4">
        {{ $produits->links() }}
      </div>

      <!-- Conteneur pour la pagination dynamique (pour les résultats de recherche) -->
      <div class="pagination-container d-flex justify-content-center mt-4"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        // Fonctions de filtrage et tri
        let currentPage = 1;

        function searchProducts(page = 1) {
          currentPage = page;
          const searchTerm = document.getElementById("searchInput").value.toLowerCase();
          const categoryFilter = document.getElementById("categoryFilter").value;

          // Appel à l'API de recherche avec pagination
          fetch(`/api/admin/produit/search?search=${searchTerm}&category=${categoryFilter}&sort=${document.getElementById("sortSelect").value}&page=${page}`)
            .then(response => response.json())
            .then(data => {
              displayProducts(data);
              updatePagination(data);
            })
            .catch(error => {
              console.error('Error searching products:', error);
            });
        }

        function displayProducts(data) {
          const productsList = document.getElementById("productsList");
          productsList.innerHTML = "";

          const products = data.data || [];

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

        function updatePagination(data) {
          const paginationContainer = document.querySelector('.pagination-container');
          if (!paginationContainer) return;

          const { current_page, last_page, total } = data;

          if (last_page <= 1) {
            paginationContainer.innerHTML = '';
            return;
          }

          let paginationHtml = '<nav aria-label="Pagination"><ul class="pagination">';

          // Bouton précédent
          paginationHtml += `
            <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
              <a class="page-link" href="#" data-page="${current_page - 1}" aria-label="Précédent">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          `;

          // Pages numérotées
          const startPage = Math.max(1, current_page - 2);
          const endPage = Math.min(last_page, current_page + 2);

          for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
              <li class="page-item ${i === current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
              </li>
            `;
          }

          // Bouton suivant
          paginationHtml += `
            <li class="page-item ${current_page === last_page ? 'disabled' : ''}">
              <a class="page-link" href="#" data-page="${current_page + 1}" aria-label="Suivant">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          `;

          paginationHtml += '</ul></nav>';

          paginationContainer.innerHTML = paginationHtml;

          // Ajouter les écouteurs d'événements pour les liens de pagination
          document.querySelectorAll('.pagination .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
              e.preventDefault();
              const page = parseInt(this.getAttribute('data-page'));
              if (page && page !== current_page && page > 0 && page <= last_page) {
                searchProducts(page);
              }
            });
          });
        }

        // Ajout des écouteurs d'événements
        document.getElementById("searchInput").addEventListener("input", searchProducts);
        document.getElementById("categoryFilter").addEventListener("change", searchProducts);
        document.getElementById("sortSelect").addEventListener("change", searchProducts);
      });
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
