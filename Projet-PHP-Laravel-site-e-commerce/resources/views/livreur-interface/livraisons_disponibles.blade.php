@extends('livreur_base')

@push('styles')
    <style>
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
    <h1 class="h3 mb-4"><i class="fas fa-truck me-2"></i>Livraisons disponibles</h1>

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
        </div>
    </form>

    @include('livreur-interface.partials.liste_commandes', ['commandes' => $commandes, 'type' => 'disponibles'])
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');

        function filterDeliveries() {
            const searchTerm = searchInput.value;
            const status = statusFilter.value;
            
            fetch(`/api/livreur/livraison/search?search=${searchTerm}&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour la liste des livraisons
                    const deliveriesList = document.getElementById('deliveriesList');
                    // Mettre à jour le contenu de la liste
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        searchInput.addEventListener('input', filterDeliveries);
        statusFilter.addEventListener('change', filterDeliveries);
    });
</script>
@endpush
@endsection 