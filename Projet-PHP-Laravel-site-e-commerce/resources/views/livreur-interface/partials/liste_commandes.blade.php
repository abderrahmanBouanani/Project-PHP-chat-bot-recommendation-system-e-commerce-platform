<div class="commande-container">
    <div class="card commande-card">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-list me-2"></i>
                <span class="h5 mb-0">Liste des commandes disponibles</span>
            </div>
            <span class="badge bg-light text-dark">
                {{ $commandes->total() }} commande(s) trouvée(s)
            </span>
        </div>
        
        <div class="card-body">
            <div class="row g-4" id="deliveriesList">
                @forelse($commandes as $commande)
                <div class="col-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Commande #{{ $commande->id }}
                                </h5>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $commande->created_at->format('d/m/Y à H:i') }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $commande->statut === 'Livrée' ? 'success' : ($commande->statut === 'En cours de livraison' ? 'info' : 'warning') }}">
                                {{ $commande->statut }}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h6 class="section-title">
                                        <i class="fas fa-user-circle me-2"></i>
                                        Client
                                    </h6>
                                    <div class="ms-4">
                                        <p class="mb-1">
                                            <i class="fas fa-user-tag me-2 text-muted"></i>
                                            {{ $commande->client ? $commande->client->prenom . ' ' . $commande->client->nom : 'Client inconnu' }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                            {{ $commande->adresse }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="section-title">
                                        <i class="fas fa-box-open me-2"></i>
                                        Résumé
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">Articles :</span>
                                            <span class="fw-bold ms-2">{{ $commande->produits->count() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-muted">Total :</span>
                                            <span class="fw-bold ms-2" style="color: #28a745;">{{ $commande->total }} DH</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <button class="btn btn-outline-primary btn-sm show-products" 
                                    data-commande-id="{{ $commande->id }}">
                                <i class="fas fa-eye me-1"></i> Voir les détails
                            </button>
                            
                            @if($type === 'disponibles' && $commande->statut === 'Confirmée')
                            <form method="POST" action="{{ route('livreur.accepter', $commande->id) }}" class="ms-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check-circle me-1"></i> Accepter la livraison
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center p-5 bg-light rounded-3">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune commande disponible</h5>
                        <p class="text-muted mb-0">Il n'y a actuellement aucune commande à afficher.</p>
                    </div>
                </div>
                @endforelse
            </div>
            
            @if($commandes->hasPages())
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de <span class="fw-bold">{{ $commandes->firstItem() }}</span> à 
                        <span class="fw-bold">{{ $commandes->lastItem() }}</span> sur 
                        <span class="fw-bold">{{ $commandes->total() }}</span> commandes
                    </div>
                    <div class="d-flex">
                        {{ $commandes->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails d'une commande -->
<div class="modal fade" id="commandeDetailsModal" tabindex="-1" aria-labelledby="commandeDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="commandeDetailsModalLabel">
                    <i class="fas fa-boxes me-2"></i>Détails de la commande #<span id="commandeId"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="commandeDetailsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des détails de la commande...</p>
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

<style>
.commande-container {
    max-width: 1200px;
    margin: 0 auto;
}

.commande-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
}

.card {
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

.card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #28a745;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.8em;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du clic sur le bouton "Voir les détails"
    document.addEventListener('click', function(e) {
        if (e.target.closest('.show-products')) {
            e.preventDefault();
            const commandeId = e.target.closest('.show-products').getAttribute('data-commande-id');
            showProducts(commandeId);
        }
    });

    function showProducts(commandeId) {
        // Afficher l'ID de la commande dans le titre
        document.getElementById('commandeId').textContent = commandeId;

        // Afficher la modale
        const modal = new bootstrap.Modal(document.getElementById('commandeDetailsModal'));
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
            document.getElementById('commandeDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${error.message}
                </div>`;
        });
    }

    function displayOrderDetails(commande) {
        let html = `
            <div class="commande-details">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user me-2"></i>Client</h6>
                        <p class="mb-0">${commande.client ? commande.client.prenom + ' ' + commande.client.nom : 'Client inconnu'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-map-marker-alt me-2"></i>Adresse</h6>
                        <p class="mb-0">${commande.adresse}</p>
                    </div>
                </div>
                <div class="row mb-4">
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
            const quantite = produit.pivot ? produit.pivot.quantite : 1;
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

        document.getElementById('commandeDetailsContent').innerHTML = html;
    }

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
});
</script>
@endpush 