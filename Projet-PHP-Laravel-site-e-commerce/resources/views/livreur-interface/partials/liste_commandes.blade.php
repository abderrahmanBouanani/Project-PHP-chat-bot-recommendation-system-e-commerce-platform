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
                                <span class="badge bg-{{ $commande->statut === 'Livrée' ? 'success' : ($commande->statut === 'En cours de livraison' ? 'info' : 'warning') }}">
                                    {{ $commande->statut }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary show-products" 
                                            data-commande-id="{{ $commande->id }}"
                                            data-bs-toggle="tooltip" 
                                            title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($type === 'disponibles' && $commande->statut === 'Confirmée')
                                        <form method="POST" action="{{ route('livreur.accepter', $commande->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Accepter la livraison">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
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

<!-- Modal pour afficher les détails d'une commande -->
<div class="modal fade" id="commandeDetailsModal" tabindex="-1" aria-labelledby="commandeDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commandeDetailsModalLabel">Détails de la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="commandeDetailsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        font-size: 0.875rem;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
</style> 