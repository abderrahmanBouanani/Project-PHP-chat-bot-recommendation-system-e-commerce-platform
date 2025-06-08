@extends('admin_base')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Détails de la commande #{{ $commande->id }}</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.commandes') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux commandes
                            </a>
                            <span class="badge {{ $commande->statut === 'Confirmée' ? 'bg-warning' : 
                                                ($commande->statut === 'En cours de livraison' ? 'bg-primary' : 
                                                ($commande->statut === 'Livrée' ? 'bg-success' : 
                                                ($commande->statut === 'Annulée' ? 'bg-danger' : 'bg-secondary'))) }}">
                                {{ $commande->statut }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Informations client -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Informations client</h5>
                                @if($commande->client)
                                    <p><strong>Nom:</strong> {{ $commande->client->nom }} {{ $commande->client->prenom }}</p>
                                    <p><strong>Email:</strong> {{ $commande->client->email }}</p>
                                    <p><strong>Téléphone:</strong> {{ $commande->client->telephone }}</p>
                                @else
                                    <p class="text-muted">Client inconnu</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Informations de livraison</h5>
                                <p><strong>Adresse:</strong> {{ $commande->adresse }}</p>
                                <p><strong>Date de commande:</strong> {{ $commande->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Méthode de paiement:</strong> {{ $commande->methode_paiement }}</p>
                            </div>
                        </div>

                        <!-- Liste des produits -->
                        <h5 class="mb-3">Produits commandés</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">Prix unitaire</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commande->produits as $produit)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($produit->image)
                                                    <img src="{{ asset('storage/' . $produit->image) }}" 
                                                         alt="{{ $produit->nom }}" 
                                                         class="img-thumbnail me-3"
                                                         style="width: 60px; height: 60px; object-fit: contain;">
                                                @else
                                                    <div class="img-thumbnail me-3 d-flex align-items-center justify-content-center bg-light"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $produit->nom }}</h6>
                                                    <small class="text-muted">Réf: {{ $produit->reference ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $produit->pivot->quantite }}</td>
                                        <td class="text-end">{{ number_format($produit->prix_unitaire, 2) }} DH</td>
                                        <td class="text-end">{{ number_format($produit->prix_unitaire * $produit->pivot->quantite, 2) }} DH</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Sous-total:</th>
                                        <th class="text-end">{{ number_format($commande->total - ($commande->frais_livraison ?? 0), 2) }} DH</th>
                                    </tr>
                                    @if($commande->frais_livraison > 0)
                                        <tr>
                                            <th colspan="3" class="text-end">Frais de livraison:</th>
                                            <th class="text-end">{{ number_format($commande->frais_livraison, 2) }} DH</th>
                                        </tr>
                                    @endif
                                    @if($commande->reduction > 0)
                                        <tr>
                                            <th colspan="3" class="text-end">Réduction:</th>
                                            <th class="text-end text-danger">-{{ number_format($commande->reduction, 2) }} DH</th>
                                        </tr>
                                    @endif
                                    <tr class="table-active">
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">{{ number_format($commande->total, 2) }} DH</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Actions -->
                        @if($commande->statut === 'En attente')
                            <div class="mt-4">
                                <form action="{{ route('admin.commande.status', $commande->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="statut" value="Confirmée">
                                    <button type="submit" class="btn btn-success me-2">
                                        <i class="fas fa-check me-2"></i>Confirmer la commande
                                    </button>
                                </form>
                                <form action="{{ route('admin.commande.status', $commande->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="statut" value="Annulée">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-2"></i>Annuler la commande
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 