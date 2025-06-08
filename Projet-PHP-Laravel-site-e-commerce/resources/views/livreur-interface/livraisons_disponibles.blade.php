@extends('livreur_base')

@section('content')
<div class="main-content">
    <h1 class="h3 mb-4"><i class="fas fa-truck me-2"></i>Livraisons disponibles</h1>
    @include('livreur-interface.partials.liste_commandes', ['commandes' => $commandes, 'type' => 'disponibles'])
</div>
@endsection 