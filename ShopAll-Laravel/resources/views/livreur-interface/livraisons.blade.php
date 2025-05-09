@extends('livreur_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<div class="main-content">
      <h1 class="h3 mb-4">Liste des Livraisons</h1>

      <div class="search-sort-container">
        <div class="row g-3">
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              id="searchInput"
              placeholder="Rechercher par ID ou adresse"
            />
          </div>
          <div class="col-md-3">
            <select class="form-select" id="statusFilter">
              <option value="all">Tous les statuts</option>
              <option value="Confirmée">En attente</option>
              <option value="En cours de livraison">En cours</option>
              <option value="Livrée">Livrée</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="sortSelect">
              <option value="date-desc">Date (plus récent)</option>
              <option value="date-asc">Date (plus ancien)</option>
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
            <th>Adresse</th>
            <th>Date de commande</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="deliveriesList">
        @foreach($commandes as $commande)
          <tr class="{{ $commande->statut === 'Livrée' ? 'table-success' : '' }}">
            <td>{{ $commande->id }}</td>
            <td>{{ $commande->adresse }}</td>
            <td>{{ $commande->created_at ? $commande->created_at->format('d/m/Y') : '' }}</td>
            <td>
              @if($commande->statut === 'Confirmée')
                <span class="badge bg-warning text-dark">En attente</span>
              @elseif($commande->statut === 'En cours de livraison')
                <span class="badge bg-primary">En cours</span>
              @elseif($commande->statut === 'Livrée')
                <span class="badge bg-success">Livrée</span>
              @else
                <span class="badge bg-secondary">{{ $commande->statut }}</span>
              @endif
            </td>
            <td>
              <button class="btn btn-sm btn-link show-products" data-commande-id="{{ $commande->id }}">
                Voir les produits
              </button>
              @if($commande->statut === 'Confirmée')
                <button class="btn btn-sm btn-primary accepter-commande" data-id="{{ $commande->id }}">
                  Accepter
                </button>
              @elseif($commande->statut === 'En cours de livraison')
                <button class="btn btn-sm btn-success livree-commande" data-id="{{ $commande->id }}">
                  Livrée
                </button>
              @endif
            </td>
          </tr>
        @endforeach
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

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("searchButton").addEventListener("click", filterDeliveries);
        document.getElementById("statusFilter").addEventListener("change", filterDeliveries);
        document.getElementById("sortSelect").addEventListener("change", filterDeliveries);
        
        // Ajouter des écouteurs d'événements pour les boutons "Voir les produits"
        document.querySelectorAll('.show-products').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-commande-id');
            showProducts(commandeId);
          });
        });

        // Ajouter des écouteurs d'événements pour les boutons d'action
        document.querySelectorAll('.accepter-commande').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-id');
            updateStatus(commandeId, 'En cours de livraison');
          });
        });

        document.querySelectorAll('.livree-commande').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-id');
            updateStatus(commandeId, 'Livrée');
          });
        });
      });

      function filterDeliveries() {
        const searchTerm = document.getElementById("searchInput").value;
        const statusFilter = document.getElementById("statusFilter").value;
        const sortValue = document.getElementById("sortSelect").value;
        
        // Appel à l'API de recherche
        fetch(`/api/livreur/livraison/search?search=${searchTerm}&status=${statusFilter}&sort=${sortValue}`)
          .then(response => response.json())
          .then(data => {
            displayDeliveries(data);
          })
          .catch(error => {
            console.error('Error searching deliveries:', error);
          });
      }

      function displayDeliveries(deliveries) {
        const deliveriesList = document.getElementById("deliveriesList");
        deliveriesList.innerHTML = "";
        
        if (deliveries.length === 0) {
          deliveriesList.innerHTML = `<tr><td colspan="5" class="text-center">Aucune livraison trouvée</td></tr>`;
          return;
        }
        
        deliveries.forEach(delivery => {
          const row = `
            <tr class="${delivery.statut === 'Livrée' ? 'table-success' : ''}">
              <td>${delivery.id}</td>
              <td>${delivery.adresse}</td>
              <td>${new Date(delivery.created_at).toLocaleDateString()}</td>
              <td>${getStatusBadge(delivery.statut)}</td>
              <td>
                <button class="btn btn-sm btn-link show-products" data-commande-id="${delivery.id}">
                  Voir les produits
                </button>
                ${getActionButtons(delivery)}
              </td>
            </tr>
          `;
          deliveriesList.insertAdjacentHTML("beforeend", row);
        });

        // Réattacher les écouteurs d'événements
        document.querySelectorAll('.show-products').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-commande-id');
            showProducts(commandeId);
          });
        });

        document.querySelectorAll('.accepter-commande').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-id');
            updateStatus(commandeId, 'En cours de livraison');
          });
        });

        document.querySelectorAll('.livree-commande').forEach(button => {
          button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-id');
            updateStatus(commandeId, 'Livrée');
          });
        });
      }

      function getStatusBadge(status) {
        switch(status) {
          case 'Confirmée':
            return '<span class="badge bg-warning text-dark">En attente</span>';
          case 'En cours de livraison':
            return '<span class="badge bg-primary">En cours</span>';
          case 'Livrée':
            return '<span class="badge bg-success">Livrée</span>';
          default:
            return `<span class="badge bg-secondary">${status}</span>`;
        }
      }

      function getActionButtons(delivery) {
        let buttons = '';
        if (delivery.statut === 'Confirmée') {
          buttons += `
            <button class="btn btn-sm btn-primary accepter-commande" data-id="${delivery.id}">
              Accepter
            </button>
          `;
        } else if (delivery.statut === 'En cours de livraison') {
          buttons += `
            <button class="btn btn-sm btn-success livree-commande" data-id="${delivery.id}">
              Livrée
            </button>
          `;
        }
        return buttons;
      }

      function updateStatus(commandeId, newStatus) {
        fetch(`/livreur/commande/${commandeId}/update-status`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ statut: newStatus })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            filterDeliveries(); // Rafraîchir la liste
          } else {
            alert('Erreur lors de la mise à jour du statut');
          }
        })
        .catch(error => {
          console.error('Error updating status:', error);
          alert('Erreur lors de la mise à jour du statut');
        });
      }

      function showProducts(commandeId) {
        fetch(`/livreur/commande/${commandeId}/produits`)
          .then(response => response.json())
          .then(data => {
            const modalProductsList = document.getElementById("modalProductsList");
            modalProductsList.innerHTML = "";
            
            if (data.length === 0) {
              modalProductsList.innerHTML = `<tr><td colspan="4" class="text-center">Aucun produit dans cette commande</td></tr>`;
            } else {
              data.forEach(item => {
                const row = `
                  <tr>
                    <td>${item.nom}</td>
                    <td>${item.quantite}</td>
                    <td>${parseFloat(item.prix_unitaire).toFixed(2)} DH</td>
                    <td>${(parseFloat(item.prix_unitaire) * parseInt(item.quantite)).toFixed(2)} DH</td>
                  </tr>
                `;
                modalProductsList.insertAdjacentHTML("beforeend", row);
              });
            }
            
            const modal = new bootstrap.Modal(document.getElementById("productsModal"));
            modal.show();
          })
          .catch(error => {
            console.error('Error loading products:', error);
            alert('Erreur lors du chargement des produits');
          });
      }
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->




