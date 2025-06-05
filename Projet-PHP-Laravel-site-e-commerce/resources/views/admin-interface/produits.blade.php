@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
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
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const sortSelect = document.getElementById('sortSelect');
        const productsList = document.getElementById('productsList');
        const paginationContainer = document.querySelector('.pagination-container');
        
        // Fonction pour mettre à jour l'URL avec les paramètres actuels
        function updateURL(params = {}) {
          const url = new URL(window.location.href);
          Object.keys(params).forEach(key => {
            if (params[key]) {
              url.searchParams.set(key, params[key]);
            } else {
              url.searchParams.delete(key);
            }
          });
          window.history.pushState({}, '', url);
        }

        // Fonction pour charger les produits via AJAX
        function loadProducts(page = 1) {
          const formData = new FormData(searchForm);
          formData.append('page', page);
          
          // Mettre à jour l'URL avec les paramètres actuels
          const params = {
            search: searchInput.value,
            category: categoryFilter.value,
            sort: sortSelect.value,
            page: page > 1 ? page : undefined
          };
          updateURL(params);
          
          // Afficher un indicateur de chargement
          productsList.innerHTML = '<tr><td colspan="5" class="text-center">Chargement en cours...</td></tr>';
          
          // Envoyer la requête AJAX avec les en-têtes appropriés
          fetch(`{{ route('admin.produits.search') }}?${new URLSearchParams(formData).toString()}`, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          })
          .then(response => {
            if (!response.ok) {
              return response.text().then(text => {
                throw new Error(`Erreur HTTP: ${response.status}`);
              });
            }
            return response.json();
          })
          .then(data => {
            if (data.success === false) {
              throw new Error(data.message || 'Une erreur est survenue');
            }
            displayProducts(data);
            updatePagination(data);
          })
          .catch(error => {
            console.error('Erreur lors du chargement des produits:', error);
            productsList.innerHTML = `
              <tr>
                <td colspan="5" class="text-center text-danger">
                  Erreur lors du chargement des produits. Veuillez réessayer.
                  <div class="text-muted small">${error.message}</div>
                </td>
              </tr>`;
          });
        }

        // Afficher les produits dans le tableau
        function displayProducts(data) {
          productsList.innerHTML = "";
          const products = data.data || [];

          if (products.length === 0) {
            productsList.innerHTML = '<tr><td colspan="5" class="text-center">Aucun produit trouvé</td></tr>';
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


        // Mettre à jour la pagination
        function updatePagination(data) {
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
          let startPage = Math.max(1, current_page - 2);
          let endPage = Math.min(last_page, current_page + 2);

          // Ajuster si on est proche du début ou de la fin
          if (endPage - startPage < 4) {
            if (current_page < 3) {
              endPage = Math.min(5, last_page);
            } else {
              startPage = Math.max(1, endPage - 4);
            }
          }


          // Afficher la première page si nécessaire
          if (startPage > 1) {
            paginationHtml += `
              <li class="page-item">
                <a class="page-link" href="#" data-page="1">1</a>
              </li>
            `;
            if (startPage > 2) {
              paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
          }


          // Pages numérotées
          for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
              <li class="page-item ${i === current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
              </li>
            `;
          }


          // Afficher la dernière page si nécessaire
          if (endPage < last_page) {
            if (endPage < last_page - 1) {
              paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            paginationHtml += `
              <li class="page-item">
                <a class="page-link" href="#" data-page="${last_page}">${last_page}</a>
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
              if (page && page > 0 && page <= last_page) {
                loadProducts(page);
              }
            });
          });
        }


        // Gérer la soumission du formulaire
        searchForm.addEventListener('submit', function(e) {
          e.preventDefault();
          loadProducts(1); // Revenir à la première page lors d'une nouvelle recherche
        });

        // Charger les produits initiaux si nécessaire (pour la navigation arrière/avant)
        window.addEventListener('popstate', function() {
          const urlParams = new URLSearchParams(window.location.search);
          const page = urlParams.get('page') || 1;
          loadProducts(parseInt(page));
        });
      });
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
