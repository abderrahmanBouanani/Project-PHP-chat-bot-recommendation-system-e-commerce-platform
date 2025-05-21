@extends('client_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
   <!-- Start Hero Section -->
   <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>Détails de la Commande #{{ $commande->id }}</h1>
            </div>
          </div>
          <div class="col-lg-7"></div>
        </div>
      </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section before-footer-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-12">
            <div class="order-details-card">
              <div class="card">
                <div class="card-header bg-primary text-white">
                  <h4 class="mb-0">Informations de la commande</h4>
                </div>
                <div class="card-body">
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <p><strong>Numéro de commande:</strong> {{ $commande->id }}</p>
                      <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($commande->created_at)->format('d/m/Y H:i') }}</p>
                      <p><strong>Statut:</strong> 
                        @if($commande->statut === 'En attente')
                          <span class="badge bg-warning text-dark">{{ $commande->statut }}</span>
                        @elseif($commande->statut === 'Confirmée')
                          <span class="badge bg-info">{{ $commande->statut }}</span>
                        @elseif($commande->statut === 'En cours de livraison')
                          <span class="badge bg-primary">{{ $commande->statut }}</span>
                        @elseif($commande->statut === 'Livrée')
                          <span class="badge bg-success">{{ $commande->statut }}</span>
                        @elseif($commande->statut === 'Annulée')
                          <span class="badge bg-danger">{{ $commande->statut }}</span>
                        @else
                          <span class="badge bg-secondary">{{ $commande->statut }}</span>
                        @endif
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Adresse de livraison:</strong> {{ $commande->adresse }}</p>
                      <p><strong>Méthode de paiement:</strong> {{ ucfirst($commande->methode_paiement) }}</p>
                      <p><strong>Total:</strong> {{ number_format($commande->total, 2) }} DH</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Produits commandés</h4>
              </div>
              <div class="card-body">
                <div class="site-blocks-table">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Image</th>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($produits as $produit)
                      <tr>
                        <td>
                          <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" style="width: 80px; height: auto;">
                        </td>
                        <td>{{ $produit->nom }}</td>
                        <td>{{ number_format($produit->prix_unitaire, 2) }} DH</td>
                        <td>{{ $produit->quantite }}</td>
                        <td>{{ number_format($produit->prix_unitaire * $produit->quantite, 2) }} DH</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <a href="{{ url('/client/commandes') }}" class="btn btn-outline-black btn-sm">
              Retour à mes commandes
            </a>
          </div>
          <div class="col-md-6 text-end">
            @if($commande->statut !== 'Annulée' && $commande->statut !== 'Livrée')
              <a href="{{ route('commande.facture', ['id' => $commande->id]) }}" class="btn btn-primary btn-sm" target="_blank">
                Télécharger la facture
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
@endsection <!-- Ici finit le contenu spécifique à cette page -->
