@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<div class="main-content">
      <h1 class="h3 mb-4">Liste des Commandes</h1>

      <div class="search-sort-container">
        <div class="row g-3">
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              id="searchInput"
              placeholder="Rechercher par ID, nom ou date (jj/mm/aaaa)"
            />
          </div>
          <div class="col-md-3">
            <select class="form-select" id="statusFilter">
              <option value="all">Tous les statuts</option>
              <option value="En attente">En attente</option>
              <option value="Confirmée">Confirmée</option>
              <option value="En cours de livraison">En cours de livraison</option>
              <option value="Livrée">Livrée</option>
              <option value="Annulée">Annulée</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="sortSelect">
              <option value="date-desc">Date (plus récent)</option>
              <option value="date-asc">Date (plus ancien)</option>
              <option value="total-desc">Total (décroissant)</option>
              <option value="total-asc">Total (croissant)</option>
            </select>
          </div>
          <div class="col-md-2">
            <button class="btn btn-outline-primary w-100" id="searchButton">
              Rechercher
            </button>
          </div>
        </div>
      </div>

      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID Commande</th>
            <th>Client</th>
            <th>Produits</th>
            <th>Date</th>
            <th>Total</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="ordersList">
          @if(count($commandes) > 0)
            @foreach($commandes as $commande)
            <tr>
              <td>{{ $commande->id }}</td>
              <td>
                @if($commande->client)
                  {{ $commande->client->nom }} {{ $commande->client->prenom }}
                @else
                  Client inconnu
                @endif
              </td>
              <td>
                <button class="btn btn-sm btn-link show-products" data-commande-id="{{ $commande->id }}">
                  Voir les produits
                </button>
              </td>
              <td>{{ \Carbon\Carbon::parse($commande->created_at)->format('d/m/Y') }}</td>
              <td>{{ number_format($commande->total ?? 0, 2) }} DH</td>
              <td>
                @if($commande->statut === 'Confirmée')
                  <span class="badge bg-warning text-dark">Confirmée</span>
                @elseif($commande->statut === 'En cours de livraison')
                  <span class="badge bg-primary">En cours de livraison</span>
                @elseif($commande->statut === 'Livrée')
                  <span class="badge bg-success">Livrée</span>
                @elseif($commande->statut === 'Annulée')
                  <span class="badge bg-danger">Annulée</span>
                @else
                  <span class="badge bg-secondary">En attente</span>
                @endif
              </td>
              <td>
                @if($commande->statut === 'En attente')
                  <form action="{{ url('/admin/commande/' . $commande->id . '/status') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="statut" value="Confirmée">
                    <button type="submit" class="btn btn-sm btn-success me-2">Confirmer</button>
                  </form>
                  <form action="{{ url('/admin/commande/' . $commande->id . '/status') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="statut" value="Annulée">
                    <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                  </form>
                @endif
              </td>
            </tr>
            @endforeach
          @else
            <tr>
              <td colspan="7" class="text-center">Aucune commande trouvée</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    <!-- Modal pour afficher les produits d'une commande -->
    <div class="modal fade" id="productsModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Produits de la commande</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody id="modalProductsList">
                  <!-- Les produits seront ajoutés ici dynamiquement -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/admin/commandes.js')}}"></script>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("searchButton").addEventListener("click", filterOrders);
        document.getElementById("sortSelect").addEventListener("change", filterOrders);
        document.getElementById("statusFilter").addEventListener("change", filterOrders);
        
        // Ajouter des écouteurs d'événements pour les boutons "Voir les produits"
        document.querySelectorAll('.show-products').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-commande-id');
            showProducts(commandeId);
          });
        });
      });

      function filterOrders() {
        const searchTerm = document.getElementById("searchInput").value;
        const sortValue = document.getElementById("sortSelect").value;
        const statusFilter = document.getElementById("statusFilter").value;
        
        // Appel à l'API de recherche
        fetch(`/api/admin/commande/search?search=${searchTerm}&sort=${sortValue}&status=${statusFilter}`)
          .then(response => response.json())
          .then(data => {
            displayOrders(data);
          })
          .catch(error => {
            console.error('Error searching orders:', error);
          });
      }

      function displayOrders(orders) {
        const ordersList = document.getElementById("ordersList");
        ordersList.innerHTML = "";
        
        if (orders.length === 0) {
          ordersList.innerHTML = `<tr><td colspan="7" class="text-center">Aucune commande trouvée</td></tr>`;
          return;
        }
        
        orders.forEach(order => {
          const row = `
            <tr>
              <td>${order.id}</td>
              <td>
                ${order.client ? `${order.client.nom} ${order.client.prenom}` : 'Client inconnu'}
              </td>
              <td>
                <button class="btn btn-sm btn-link show-products" data-commande-id="${order.id}">
                  Voir les produits
                </button>
              </td>
              <td>${new Date(order.created_at).toLocaleDateString()}</td>
              <td>${parseFloat(order.total || 0).toFixed(2)} DH</td>
              <td>${getStatusBadge(order.statut)}</td>
              <td>${getActionButtons(order)}</td>
            </tr>
          `;
          ordersList.insertAdjacentHTML("beforeend", row);
        });

        // Réattacher les écouteurs d'événements aux nouveaux boutons
        document.querySelectorAll('.show-products').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-commande-id');
            showProducts(commandeId);
          });
        });
      }

      function getStatusBadge(status) {
        switch(status) {
          case 'Confirmée':
            return '<span class="badge bg-warning text-dark">Confirmée</span>';
          case 'En cours de livraison':
            return '<span class="badge bg-primary">En cours de livraison</span>';
          case 'Livrée':
            return '<span class="badge bg-success">Livrée</span>';
          case 'Annulée':
            return '<span class="badge bg-danger">Annulée</span>';
          default:
            return '<span class="badge bg-secondary">En attente</span>';
        }
      }

      function getActionButtons(order) {
        if (order.statut === 'En attente') {
          return `
            <form action="/admin/commande/${order.id}/status" method="POST" class="d-inline">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="statut" value="Confirmée">
              <button type="submit" class="btn btn-sm btn-success me-2">Confirmer</button>
            </form>
            <form action="/admin/commande/${order.id}/status" method="POST" class="d-inline">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="statut" value="Annulée">
              <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
            </form>
          `;
        }
        return '';
      }

      function showProducts(commandeId) {
        // Add CSRF token to the request headers
        const headers = {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        };

        // Show loading indicator in the modal
        const modalProductsList = document.getElementById("modalProductsList");
        modalProductsList.innerHTML = `<tr><td colspan="4" class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
          <p>Chargement des produits...</p>
        </td></tr>`;
        
        // Show the modal immediately with loading indicator
        const modal = new bootstrap.Modal(document.getElementById("productsModal"));
        modal.show();

        fetch(`/admin/api/products/${commandeId}`, {
          method: 'GET',
          headers: headers,
          credentials: 'same-origin'
        })
          .then(response => {
            if (!response.ok) {
              return response.json().then(err => {
                throw new Error(err.error || 'Erreur serveur');
              });
            }
            return response.json();
          })
          .then(data => {
            console.log('Products data:', data); // Debug log
            
            modalProductsList.innerHTML = "";
            
            if (!data || data.length === 0) {
              modalProductsList.innerHTML = `<tr><td colspan="4" class="text-center">Aucun produit dans cette commande</td></tr>`;
            } else {
              // Ensure we're working with an array
              const products = Array.isArray(data) ? data : [data];
              products.forEach(item => {
                const prix = parseFloat(item.prix_unitaire || 0);
                const quantite = parseInt(item.quantite || 0);
                const total = prix * quantite;
                
                const row = `
                  <tr>
                    <td>${item.nom || 'Produit inconnu'}</td>
                    <td>${quantite}</td>
                    <td>${prix.toFixed(2)} DH</td>
                    <td>${total.toFixed(2)} DH</td>
                  </tr>
                `;
                modalProductsList.insertAdjacentHTML("beforeend", row);
              });
            }
          })
          .catch(error => {
            console.error('Error loading products:', error);
            modalProductsList.innerHTML = `<tr><td colspan="4" class="text-center text-danger">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              Erreur lors du chargement des produits: ${error.message}
              <p class="mt-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="showProducts(${commandeId})">
                  <i class="bi bi-arrow-clockwise me-1"></i> Réessayer
                </button>
              </p>
            </td></tr>`;
          });
      }
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
