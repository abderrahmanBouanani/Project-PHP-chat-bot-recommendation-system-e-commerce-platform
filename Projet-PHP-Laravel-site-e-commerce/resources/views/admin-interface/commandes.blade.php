@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<div class="main-content">
      <h1 class="h3 mb-4">Liste des Commandes</h1>

      <form id="searchForm" method="GET" action="{{ route('admin.commandes') }}" class="mb-4">
        <div class="row g-3">
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              id="searchInput"
              name="search"
              value="{{ $currentSearch ?? '' }}"
              placeholder="Rechercher par ID ou nom du client"
            />
          </div>
          <div class="col-md-3">
            <select class="form-select" id="statusFilter" name="status">
              <option value="all" {{ ($currentStatus ?? 'all') === 'all' ? 'selected' : '' }}>Tous les statuts</option>
              <option value="En attente" {{ ($currentStatus ?? '') === 'En attente' ? 'selected' : '' }}>En attente</option>
              <option value="Confirmée" {{ ($currentStatus ?? '') === 'Confirmée' ? 'selected' : '' }}>Confirmée</option>
              <option value="En cours de livraison" {{ ($currentStatus ?? '') === 'En cours de livraison' ? 'selected' : '' }}>En cours de livraison</option>
              <option value="Livrée" {{ ($currentStatus ?? '') === 'Livrée' ? 'selected' : '' }}>Livrée</option>
              <option value="Annulée" {{ ($currentStatus ?? '') === 'Annulée' ? 'selected' : '' }}>Annulée</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="sortSelect" name="sort">
              <option value="date-desc" {{ ($currentSort ?? 'date-desc') === 'date-desc' ? 'selected' : '' }}>Date (plus récent)</option>
              <option value="date-asc" {{ ($currentSort ?? '') === 'date-asc' ? 'selected' : '' }}>Date (plus ancien)</option>
              <option value="total-desc" {{ ($currentSort ?? '') === 'total-desc' ? 'selected' : '' }}>Total (décroissant)</option>
              <option value="total-asc" {{ ($currentSort ?? '') === 'total-asc' ? 'selected' : '' }}>Total (croissant)</option>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
              Appliquer
            </button>
          </div>
        </div>
      </form>

      <table class="table table-hover">
        <thead>
          <tr>
            <th><i class="bi bi-hash me-1"></i>ID Commande</th>
            <th><i class="bi bi-person me-1"></i>Client</th>
            <th><i class="bi bi-box-seam me-1"></i>Produits</th>
            <th><i class="bi bi-calendar3 me-1"></i>Date</th>
            <th><i class="bi bi-currency-dollar me-1"></i>Total</th>
            <th><i class="bi bi-info-circle me-1"></i>Statut</th>
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

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-4 pagination-container">
        <!-- La pagination sera générée dynamiquement en JavaScript -->
      </div>
    </div>

    <!-- Modal pour afficher les produits d'une commande -->
    <div class="modal fade" id="productsModal" tabindex="-1" aria-labelledby="productsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productsModalLabel">Produits de la commande #<span id="commandeId"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="productsList">
            <!-- Les produits seront chargés ici dynamiquement -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/admin/commandes.js')}}"></script>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const sortSelect = document.getElementById('sortSelect');
        const ordersList = document.getElementById('ordersList');
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

        // Fonction pour charger les commandes via AJAX
        function loadOrders(page = 1) {
          const formData = new FormData(searchForm);
          formData.append('page', page);
          
          // Mettre à jour l'URL avec les paramètres actuels
          const params = {
            search: searchInput.value,
            status: statusFilter.value,
            sort: sortSelect.value,
            page: page > 1 ? page : undefined
          };
          updateURL(params);
          
          // Afficher un indicateur de chargement
          ordersList.innerHTML = '<tr><td colspan="7" class="text-center">Chargement en cours...</td></tr>';
          
          // Envoyer la requête AJAX avec les en-têtes appropriés
          fetch(`{{ route('admin.commandes') }}?${new URLSearchParams(formData).toString()}`, {
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
            displayOrders(data);
            updatePagination(data);
          })
          .catch(error => {
            console.error('Erreur lors du chargement des commandes:', error);
            ordersList.innerHTML = `
              <tr>
                <td colspan="7" class="text-center text-danger">
                  Erreur lors du chargement des commandes. Veuillez réessayer.
                  <div class="text-muted small">${error.message}</div>
                </td>
              </tr>`;
          });
        }

        // Afficher les commandes dans le tableau
        function displayOrders(data) {
          ordersList.innerHTML = "";
          const orders = data.data || [];

          if (orders.length === 0) {
            ordersList.innerHTML = '<tr><td colspan="7" class="text-center">Aucune commande trouvée</td></tr>';
            return;
          }

          orders.forEach(order => {
            const statusBadge = getStatusBadge(order.statut);
            const actions = getOrderActions(order);
            
            const row = `
              <tr>
                <td>${order.id}</td>
                <td>${order.client ? `${order.client.nom} ${order.client.prenom}` : 'Client inconnu'}</td>
                <td>
                  <button class="btn btn-sm btn-link show-products" data-commande-id="${order.id}">
                    Voir les produits
                  </button>
                </td>
                <td>${new Date(order.created_at).toLocaleDateString('fr-FR')}</td>
                <td>${parseFloat(order.total || 0).toFixed(2)} DH</td>
                <td>${statusBadge}</td>
                <td>${actions}</td>
              </tr>`;
              
            ordersList.insertAdjacentHTML('beforeend', row);
          });
          
          // Réattacher les écouteurs d'événements pour les boutons "Voir les produits"
          document.querySelectorAll('.show-products').forEach(button => {
            button.addEventListener('click', function() {
              const commandeId = this.getAttribute('data-commande-id');
              showProducts(commandeId);
            });
          });
        }
        
        // Obtenir le badge de statut formaté
        function getStatusBadge(status) {
          const statusClasses = {
            'Confirmée': 'bg-warning text-dark',
            'En cours de livraison': 'bg-primary',
            'Livrée': 'bg-success',
            'Annulée': 'bg-danger',
            'En attente': 'bg-secondary'
          };
          
          const className = statusClasses[status] || 'bg-secondary';
          return `<span class="badge ${className}">${status || 'Inconnu'}</span>`;
        }
        
        // Obtenir les actions disponibles pour une commande
        function getOrderActions(order) {
          if (order.statut === 'En attente') {
            return `
              <form action="{{ url('/admin/commande/') }}/${order.id}/status" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="statut" value="Confirmée">
                <button type="submit" class="btn btn-sm btn-success me-2">Confirmer</button>
              </form>
              <form action="{{ url('/admin/commande/') }}/${order.id}/status" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="statut" value="Annulée">
                <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
              </form>`;
          }
          return '-';
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
            </li>`;
          
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
              </li>`;
            if (startPage > 2) {
              paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
          }
          
          // Pages numérotées
          for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `
              <li class="page-item ${i === current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
              </li>`;
          }
          
          // Afficher la dernière page si nécessaire
          if (endPage < last_page) {
            if (endPage < last_page - 1) {
              paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            paginationHtml += `
              <li class="page-item">
                <a class="page-link" href="#" data-page="${last_page}">${last_page}</a>
              </li>`;
          }
          
          // Bouton suivant
          paginationHtml += `
            <li class="page-item ${current_page === last_page ? 'disabled' : ''}">
              <a class="page-link" href="#" data-page="${current_page + 1}" aria-label="Suivant">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>`;
          
          paginationHtml += '</ul></nav>';
          paginationContainer.innerHTML = paginationHtml;
          
          // Ajouter les écouteurs d'événements pour les liens de pagination
          document.querySelectorAll('.pagination .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
              e.preventDefault();
              const page = parseInt(this.getAttribute('data-page'));
              if (page && page > 0 && page <= last_page) {
                loadOrders(page);
              }
            });
          });
        }
        
        // Gérer la soumission du formulaire
        searchForm.addEventListener('submit', function(e) {
          e.preventDefault();
          loadOrders(1); // Revenir à la première page lors d'une nouvelle recherche
        });
        
        // Charger les commandes initiales si nécessaire (pour la navigation arrière/avant)
        window.addEventListener('popstate', function() {
          const urlParams = new URLSearchParams(window.location.search);
          const page = urlParams.get('page') || 1;
          loadOrders(parseInt(page));
        });
        
        // Charger les commandes initiales
        loadOrders({{ request('page', 1) }});
      });
      
      function showProducts(commandeId) {
        // Afficher un indicateur de chargement
        document.getElementById('productsList').innerHTML = '<p class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Chargement des détails de la commande...</p>';
        
        // Afficher l'ID de la commande dans le titre
        document.getElementById('commandeId').textContent = commandeId;
        
        // Afficher la modale
        const modal = new bootstrap.Modal(document.getElementById('productsModal'));
        modal.show();
        
        // Récupérer les détails de la commande via AJAX
        fetch(`/admin/commande/${commandeId}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.commande) {
            const commande = data.commande;
            let html = `
              <div class="mb-4 p-3 bg-light rounded">
                <div class="row">
                  <div class="col-md-6">
                    <h6><i class="fas fa-user me-2"></i>Client</h6>
                    <p class="mb-1">${commande.client ? commande.client.nom + ' ' + commande.client.prenom : 'Inconnu'}</p>
                    <p class="mb-1"><small>${commande.client?.email || ''}</small></p>
                    <p class="mb-0"><small>${commande.client?.telephone || ''}</small></p>
                  </div>
                  <div class="col-md-6">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>Adresse de livraison</h6>
                    <p class="mb-1">${commande.adresse_livraison || 'Non spécifiée'}</p>
                    <p class="mb-0">${commande.ville_livraison || ''} ${commande.code_postal_livraison || ''}</p>
                    <p class="mb-0">${commande.pays_livraison || ''}</p>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-4">
                    <h6><i class="far fa-calendar-alt me-2"></i>Date</h6>
                    <p class="mb-0">${new Date(commande.created_at).toLocaleString('fr-FR')}</p>
                  </div>
                  <div class="col-md-4">
                    <h6><i class="fas fa-tag me-2"></i>Statut</h6>
                    <p class="mb-0"><span class="badge ${getStatusBadgeClass(commande.statut)}">${commande.statut}</span></p>
                  </div>
                  <div class="col-md-4">
                    <h6><i class="fas fa-receipt me-2"></i>Total</h6>
                    <p class="mb-0"><strong>${parseFloat(commande.total).toFixed(2)} DH</strong></p>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Produit</th>
                      <th class="text-end">Prix unitaire</th>
                      <th class="text-center">Quantité</th>
                      <th class="text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>`;
            
            // Ajouter chaque produit
            commande.produits.forEach(produit => {
              const prixUnitaire = parseFloat(produit.prix_unitaire);
              const quantite = parseInt(produit.pivot.quantite);
              const total = prixUnitaire * quantite;
              
              html += `
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="${produit.image ? '/storage/' + produit.image : '/assets/images/no-image.png'}" 
                           alt="${produit.nom}" 
                           class="img-thumbnail me-3"
                           style="width: 60px; height: 60px; object-fit: contain;">
                      <div>
                        <h6 class="mb-1">${produit.nom}</h6>
                        <small class="text-muted">${produit.reference || 'N/A'}</small>
                      </div>
                    </div>
                  </td>
                  <td class="text-end">${prixUnitaire.toFixed(2)} DH</td>
                  <td class="text-center">${quantite}</td>
                  <td class="text-end">${total.toFixed(2)} DH</td>
                </tr>`;
            });
            
            // Ajouter le total de la commande
            html += `
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="3" class="text-end">Sous-total:</th>
                      <th class="text-end">${parseFloat(commande.sous_total || commande.total).toFixed(2)} DH</th>
                    </tr>`;
            
            if (commande.frais_livraison > 0) {
              html += `
                    <tr>
                      <th colspan="3" class="text-end">Frais de livraison:</th>
                      <th class="text-end">${parseFloat(commande.frais_livraison).toFixed(2)} DH</th>
                    </tr>`;
            }
            
            if (commande.remise > 0) {
              html += `
                    <tr>
                      <th colspan="3" class="text-end">Remise:</th>
                      <th class="text-end text-danger">-${parseFloat(commande.remise).toFixed(2)} DH</th>
                    </tr>`;
            }
            
            html += `
                    <tr class="table-active">
                      <th colspan="3" class="text-end">Total:</th>
                      <th class="text-end">${parseFloat(commande.total).toFixed(2)} DH</th>
                    </tr>
                  </tfoot>
                </table>
              </div>`;
            
            // Ajouter les notes si elles existent
            if (commande.notes) {
              html += `
              <div class="mt-4 p-3 bg-light rounded">
                <h6><i class="fas fa-sticky-note me-2"></i>Notes:</h6>
                <p class="mb-0">${commande.notes}</p>
              </div>`;
            }
            
            document.getElementById('productsList').innerHTML = html;
          } else {
            throw new Error(data.message || 'Erreur lors du chargement des détails de la commande');
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          document.getElementById('productsList').innerHTML = `
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-triangle me-2"></i>
              ${error.message || 'Une erreur est survenue lors du chargement des détails de la commande.'}
            </div>`;
        });
      }

      function getStatusBadgeClass(status) {
        switch(status) {
          case 'Confirmée':
            return 'bg-warning text-dark';
          case 'En cours de livraison':
            return 'bg-primary';
          case 'Livrée':
            return 'bg-success';
          case 'Annulée':
            return 'bg-danger';
          default:
            return 'bg-secondary';
        }
      }
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
