@extends('client_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
@if(!session('user'))
    <div class="container mt-5">
        <div class="alert alert-warning text-center">
            <h4>Veuillez vous connecter pour voir vos commandes</h4>
            <a href="{{ url('/') }}" class="btn btn-primary mt-3">Se connecter</a>
        </div>
    </div>
@else
   <!-- Start Hero Section -->
   <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>Mes Commandes</h1>
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
            <div class="site-blocks-table">
              <table class="table">
                <thead>
                  <tr>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($commandes) > 0)
                    @foreach($commandes as $commande)
                    <tr>
                      <td>{{ $commande->id }}</td>
                      <td>{{ \Carbon\Carbon::parse($commande->created_at)->format('d/m/Y') }}</td>
                      <td>{{ number_format($commande->total, 2) }} DH</td>
                      <td>
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
                      </td>
                      <td>
                        <a href="{{ url('/client/commandes/' . $commande->id) }}" class="btn btn-sm btn-primary">Détails</a>
                        @if($commande->statut !== 'Annulée' && $commande->statut !== 'Livrée')
                          <a href="{{ route('commande.facture', ['id' => $commande->id]) }}" class="btn btn-sm btn-secondary" target="_blank">Facture</a>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="5" class="text-center">Vous n'avez pas encore passé de commande.</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
          {{ $commandes->links() }}
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="row mb-5">
              <div class="col-md-6">
                <button class="btn btn-outline-black btn-sm btn-block">
                  <a href="{{ url('/client_shop')}}" style="color: white; text-decoration: none;">
                    Continuer les achats
                  </a>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endif
@endsection <!-- Ici finit le contenu spécifique à cette page -->
