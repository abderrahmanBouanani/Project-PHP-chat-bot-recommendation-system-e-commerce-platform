<div class="commande-container">
    <div class="card commande-card">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-shopping-bag me-2"></i>
                <span class="h5 mb-0">Commande #{{ $commande->id }}</span>
            </div>
            <span class="badge bg-{{ $commande->statut === 'En cours de livraison' ? 'warning' : 'success' }} text-dark">
                {{ $commande->statut }}
            </span>
        </div>
        
        <div class="card-body">
            <div class="row">
                <!-- Informations client -->
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="client-info p-4 rounded-3 shadow-sm">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-user-circle me-2 text-primary"></i>
                            Informations client
                        </h5>
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user-tag me-2 text-muted"></i>
                                <span class="fw-bold me-2">Client :</span>
                                <span>{{ $commande->client->prenom ?? '' }} {{ $commande->client->nom ?? '' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                <span class="fw-bold me-2">Adresse :</span>
                                <span>{{ $commande->adresse }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="far fa-calendar-alt me-2 text-muted"></i>
                                <span class="fw-bold me-2">Date :</span>
                                <span>{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>
                        
                        <div class="livraison-info mt-4 p-3 rounded-2" style="background-color: #f8f9fa;">
                            <h6 class="d-flex align-items-center mb-2">
                                <i class="fas fa-truck me-2 text-primary"></i>
                                Livraison
                            </h6>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <i class="fas {{ $commande->statut === 'En cours de livraison' ? 'fa-spin fa-sync-alt' : 'fa-check-circle' }}" style="color: #28a745;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" style="background-color: #28a745; width: {{ $commande->statut === 'En cours de livraison' ? '50%' : '100%' }}" 
                                             role="progressbar" 
                                             aria-valuenow="{{ $commande->statut === 'En cours de livraison' ? '50' : '100' }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small>Confirmée</small>
                                        <small>{{ $commande->statut === 'En cours de livraison' ? 'En cours' : 'Livrée' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Détails de la commande -->
                <div class="col-lg-7">
                    <div class="produits-commande">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-box-open me-2 text-primary"></i>
                            Détails de la commande
                        </h5>
                        
                        <div class="produits-list">
                            @foreach($commande->produits as $produit)
                            <div class="produit-item d-flex align-items-center p-3 mb-3 rounded-3 shadow-sm">
                                <div class="produit-image me-3">
                                    <img src="{{ asset('storage/' . $produit->image) }}" 
                                         alt="{{ $produit->nom }}" 
                                         class="img-fluid rounded"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                                <div class="produit-details flex-grow-1">
                                    <h6 class="mb-1">{{ $produit->nom }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Quantité: {{ $produit->pivot->quantite }}</span>
                                        <span class="fw-bold">{{ $produit->prix_unitaire * $produit->pivot->quantite }} DH</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="total-section mt-4 p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Total</h5>
                                <h4 class="mb-0" style="color: #28a745;">{{ $commande->total }} DH</h4>
                            </div>
                            @if($commande->statut === 'En cours de livraison')
                            <form method="POST" action="{{ route('livreur.livree', $commande->id) }}" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Marquer comme livrée
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
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