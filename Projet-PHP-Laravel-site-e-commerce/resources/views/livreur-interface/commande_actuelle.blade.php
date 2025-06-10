@extends('livreur_base')

@push('styles')
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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .commande-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
        font-size: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-green);
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: #28a745;
        border-radius: 2px;
    }
    
    .client-info {
        background: #fff;
        height: 100%;
    }
    
    .info-item {
        margin-bottom: 1rem;
    }
    
    .info-item i {
        width: 20px;
        text-align: center;
    }
    
    .produit-item {
        background: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .produit-item:hover {
        transform: translateX(5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
    }
    
    .total-section {
        background: #f8f9fa;
    }
    
    .btn-primary {
        background: #28a745;
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        color: white;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #28a745, #1e7e34) !important;
    }
    
    @media (max-width: 991.98px) {
        .client-info {
            margin-bottom: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <h1 class="h3 mb-4"><i class="fas fa-shipping-fast me-2"></i>Commande actuelle</h1>
    @if($commande)
        @include('livreur-interface.partials.details_commande', ['commande' => $commande])
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Aucune commande en cours de livraison.
        </div>
    @endif
</div>
@endsection 