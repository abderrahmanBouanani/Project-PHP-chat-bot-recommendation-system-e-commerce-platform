@extends('livreur_base')

@push('styles')
<style>
    .stats-card {
        background-color: #2f4f4f !important;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        color: white !important;
        padding: 1.25rem;
        border: none !important;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-icon-container {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(198, 157, 55, 0.2);
    }
    .stats-card h6 {
        color: rgba(255,255,255,0.8) !important;
    }
    .stats-card h3 {
        color: white !important;
    }
    .see-more {
        color: rgba(255,255,255,0.8) !important;
        text-decoration: none;
        font-size: 0.875rem;
    }
    .see-more:hover {
        color: white !important;
    }
    .chart-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
    }
    .recent-deliveries {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
    }
    .badge-custom {
        padding: 0.5em 1em;
        font-size: 0.75rem;
    }
    .stats-icon {
        color: #c69d37 !important;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    
    <h1 class="h3 mb-4">Tableau de Bord</h1>

    <div class="row g-3 mb-4">
        <!-- Carte Total Livraisons -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card h-100" style="background-color: #2f4f4f !important; border: none !important;">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-white mb-3">Total Livraisons</h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 text-white">{{ $stats['total_livraisons'] }}</h3>
                        <div class="stats-icon-container">
                            <i class="bi bi-truck fs-1 stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Livraisons en Cours -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card h-100" style="background-color: #2f4f4f !important; border: none !important;">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-white mb-3">Livraisons en Cours</h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 text-white">{{ $stats['livraisons_en_cours'] }}</h3>
                        <div class="stats-icon-container">
                            <i class="bi bi-clock-history fs-1 stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Livraisons Terminées -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card h-100" style="background-color: #2f4f4f !important; border: none !important;">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-white mb-3">Livraisons Terminées</h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 text-white">{{ $stats['livraisons_terminees'] }}</h3>
                        <div class="stats-icon-container">
                            <i class="bi bi-check-circle fs-1 stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Livraisons Disponibles -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stats-card h-100" style="background-color: #2f4f4f !important; border: none !important;">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-white mb-3">Livraisons Disponibles</h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 text-white">{{ $stats['livraisons_disponibles'] }}</h3>
                        <div class="stats-icon-container">
                            <i class="bi bi-box-seam fs-1 stats-icon"></i>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('livreur.livraisons.disponibles') }}" class="see-more d-inline-flex align-items-center">
                            Voir les livraisons <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Graphique des livraisons -->
        <div class="col-12 col-lg-8">
            <div class="chart-container">
                <h5 class="mb-4">Livraisons des 7 derniers jours</h5>
                <canvas id="livraisonsChart"></canvas>
            </div>
        </div>

        <!-- Livraisons récentes -->
        <div class="col-12 col-lg-4">
            <div class="recent-deliveries">
                <h5 class="mb-4">Livraisons Récentes</h5>
                @forelse($commandes_recentes as $commande)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Commande #{{ $commande->id }}</h6>
                            <small class="text-muted">{{ $commande->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <span class="badge bg-{{ $commande->statut === 'Livrée' ? 'success' : 'info' }} badge-custom">
                            {{ $commande->statut }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucune livraison récente</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('livraisonsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{
                label: 'Nombre de livraisons',
                data: @json($totals),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 