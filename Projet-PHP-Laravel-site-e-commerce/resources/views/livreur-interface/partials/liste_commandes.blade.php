<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Adresse</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                        <tr>
                            <td>#{{ $commande->id }}</td>
                            <td>{{ $commande->client->prenom ?? '' }} {{ $commande->client->nom ?? '' }}</td>
                            <td>{{ $commande->adresse }}</td>
                            <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $commande->statut }}</td>
                            <td>
                                @if($type === 'disponibles' && $commande->statut === 'Confirmée')
                                    <form method="POST" action="{{ route('livreur.accepter', $commande->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                                    </form>
                                @elseif($type === 'mes')
                                    <span class="badge bg-success">Livrée</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p class="mb-0">Aucune commande trouvée</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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