@extends('livreur_base')

@section('content')
<div class="main-content">
    <h1 class="h3 mb-4"><i class="fas fa-shipping-fast me-2"></i>Commande actuelle</h1>
    @if($commande)
        @include('livreur-interface.partials.details_commande', ['commande' => $commande])
    @else
        <div class="alert alert-info">Aucune commande en cours de livraison.</div>
    @endif
</div>
@endsection 