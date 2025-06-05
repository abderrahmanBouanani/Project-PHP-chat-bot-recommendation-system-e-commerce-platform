@extends('livreur_base')

@push('styles')
    @if(!app()->environment('production'))
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .card-header, .card-footer {
            background-color: #f8f9fa;
            border: none;
        }
        .btn-group-sm > .btn, .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .form-control, .form-select {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
        .pagination {
            margin-bottom: 0;
        }
        .pagination .page-item .page-link {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }
        .modal-header {
            border-bottom: 1px solid #e9ecef;
        }
        .modal-footer {
            border-top: 1px solid #e9ecef;
        }
        .table-hover > tbody > tr:hover {
            --bs-table-accent-bg: rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush

@section('content')
<div class="main-content">
    <h1 class="h3 mb-4"><i class="fas fa-truck me-2"></i>Gestion des Livraisons</h1>

    <form id="searchForm" class="mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" 
                           class="form-control" 
                           id="searchInput" 
                           placeholder="Rechercher par ID, adresse ou client"
                           value="{{ request('search', '') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter" name="status">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                    <option value="Confirmée" {{ request('status') === 'Confirmée' ? 'selected' : '' }}>En attente</option>
                    <option value="En cours de livraison" {{ request('status') === 'En cours de livraison' ? 'selected' : '' }}>En cours de livraison</option>
                    <option value="Livrée" {{ request('status') === 'Livrée' ? 'selected' : '' }}>Livrée</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="sortSelect" name="sort">
                    <option value="date-desc" {{ request('sort', 'date-desc') === 'date-desc' ? 'selected' : '' }}>Date (récent)</option>
                    <option value="date-asc" {{ request('sort') === 'date-asc' ? 'selected' : '' }}>Date (ancien)</option>
                    <option value="total-desc" {{ request('sort') === 'total-desc' ? 'selected' : '' }}>Total (élevé)</option>
                    <option value="total-asc" {{ request('sort') === 'total-asc' ? 'selected' : '' }}>Total (bas)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-user me-1"></i>Client</th>
                            <th><i class="fas fa-map-marker-alt me-1"></i>Adresse</th>
                            <th><i class="far fa-calendar me-1"></i>Date</th>
                            <th><i class="fas fa-tag me-1"></i>Statut</th>
                            <th><i class="fas fa-cog me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="deliveriesList">
                        @forelse($commandes as $commande)
                            <tr class="{{ $commande->statut === 'Livrée' ? 'table-success' : ($commande->statut === 'En cours de livraison' ? 'table-info' : '') }}">
                                <td class="fw-bold">#{{ $commande->id }}</td>
                                <td>
                                    @if($commande->client)
                                        {{ $commande->client->prenom }} {{ $commande->client->nom }}
                                    @else
                                        Client inconnu
                                    @endif
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $commande->adresse }}">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        {{ $commande->adresse }}
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block">{{ $commande->created_at->format('d/m/Y') }}</span>
                                    <small class="text-muted">{{ $commande->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($commande->statut === 'Confirmée')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @elseif($commande->statut === 'En cours de livraison')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-truck me-1"></i>En cours
                                        </span>
                                    @elseif($commande->statut === 'Livrée')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Livrée
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $commande->statut }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary show-products" 
                                                data-commande-id="{{ $commande->id }}"
                                                data-bs-toggle="tooltip" 
                                                title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($commande->statut === 'Confirmée')
                                            <button class="btn btn-outline-success accepter-commande" 
                                                    data-id="{{ $commande->id }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Accepter la livraison">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @elseif($commande->statut === 'En cours de livraison')
                                            <button class="btn btn-outline-success livree-commande" 
                                                    data-id="{{ $commande->id }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Marquer comme livrée">
                                                <i class="fas fa-truck-loading"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <p class="mb-0">Aucune livraison trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($commandes->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de <span class="fw-bold">{{ $commandes->firstItem() }}</span> à 
                        <span class="fw-bold">{{ $commandes->lastItem() }}</span> sur 
                        <span class="fw-bold">{{ $commandes->total() }}</span> livraisons
                    </div>
                    {{ $commandes->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal pour afficher les produits d'une commande -->
<div class="modal fade" id="productsModal" tabindex="-1" aria-labelledby="productsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="productsModalLabel">
                    <i class="fas fa-boxes me-2"></i>Détails de la commande #<span id="commandeId"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productsList">
                <!-- Le contenu sera chargé dynamiquement via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Initialiser les tooltips Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Gestion du formulaire de recherche
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const params = new URLSearchParams(formData).toString();
                window.location.href = `?${params}`;
            });
        }

        // Fonction pour afficher les détails d'une commande
        function showProducts(commandeId) {
            // Afficher un indicateur de chargement
            const productsList = document.getElementById('productsList');
            productsList.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails de la commande...</p>
                </div>`;

            // Afficher l'ID de la commande dans le titre
            document.getElementById('commandeId').textContent = commandeId;

            // Afficher la modale
            const modal = new bootstrap.Modal(document.getElementById('productsModal'));
            modal.show();

            // Récupérer les détails de la commande via AJAX
            fetch(`/livreur/commande/${commandeId}/details`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des détails de la commande');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.commande) {
                    displayOrderDetails(data.commande);
                } else {
                    throw new Error(data.message || 'Erreur lors du chargement des détails de la commande');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                productsList.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${error.message || 'Une erreur est survenue lors du chargement des détails de la commande.'}
                    </div>`;
            });
        }

        // Fonction pour afficher les détails d'une commande dans la modale
        function displayOrderDetails(commande) {
            let html = `
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Client</h6>
                            <p class="mb-1">${commande.client ? commande.client.prenom + ' ' + commande.client.nom : 'Inconnu'}</p>
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

            // Log pour déboguer
            console.log('Commande complète:', commande);
            
            // Ajouter chaque produit
            commande.produits.forEach((produit, index) => {
                console.log(`Produit ${index + 1}:`, produit);
                console.log(`Pivot ${index + 1}:`, produit.pivot);
                
                // Récupérer la quantité de manière robuste
                let quantite = 1; // Valeur par défaut
                
                if (produit.pivot && produit.pivot.quantite !== undefined) {
                    quantite = parseInt(produit.pivot.quantite);
                } else if (produit.quantite !== undefined) {
                    quantite = parseInt(produit.quantite);
                }
                
                const prixUnitaire = parseFloat(produit.prix_unitaire || 0);
                const total = prixUnitaire * quantite;
                
                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${produit.image ? '/storage/' + produit.image : '/assets/images/no-image.png'}" 
                                     alt="${produit.nom}" 
                                     class="img-thumbnail me-3"
                                     style="width: 50px; height: 50px; object-fit: contain;">
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
        }

        // Fonction utilitaire pour obtenir la classe du badge de statut
        function getStatusBadgeClass(status) {
            switch (status) {
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

        // Gestion du clic sur le bouton "Voir les produits"
        document.addEventListener('click', function(e) {
            if (e.target.closest('.show-products')) {
                e.preventDefault();
                const commandeId = e.target.closest('.show-products').getAttribute('data-commande-id');
                showProducts(commandeId);
            }
        });

        // Gestion du clic sur le bouton "Accepter la commande"
        document.addEventListener('click', function(e) {
            if (e.target.closest('.accepter-commande')) {
                e.preventDefault();
                if (confirm('Êtes-vous sûr de vouloir accepter cette commande pour livraison ?')) {
                    const commandeId = e.target.closest('.accepter-commande').getAttribute('data-id');
                    updateStatus(commandeId, 'En cours de livraison');
                }
            }
        });

        // Gestion du clic sur le bouton "Marquer comme livrée"
        document.addEventListener('click', function(e) {
            if (e.target.closest('.livree-commande')) {
                e.preventDefault();
                if (confirm('Confirmez-vous que cette commande a été livrée avec succès ?')) {
                    const commandeId = e.target.closest('.livree-commande').getAttribute('data-id');
                    updateStatus(commandeId, 'Livrée');
                }
            }
        });

        // Fonction pour mettre à jour le statut d'une commande
        function updateStatus(commandeId, newStatus) {
            const button = document.querySelector(`[data-id="${commandeId}"]`);
            const originalHtml = button.innerHTML;
            
            // Afficher un indicateur de chargement
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            
            // Envoyer la requête de mise à jour
            fetch(`/admin/commande/${commandeId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    statut: newStatus,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la mise à jour du statut');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Recharger la page pour afficher les modifications
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Erreur lors de la mise à jour du statut');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert(error.message || 'Une erreur est survenue lors de la mise à jour du statut');
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        }
    });

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




