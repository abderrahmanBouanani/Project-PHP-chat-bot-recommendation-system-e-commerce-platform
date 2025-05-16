@php
    $commande = $commande ?? null;
    $facturation = $facturation ?? null;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $commande->id ?? '' }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .facture-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        h2 { text-align: center; }
        .details, .produits { width: 100%; margin-bottom: 20px; }
        .details td { padding: 5px; }
        .produits th, .produits td { border: 1px solid #ddd; padding: 8px; }
        .produits th { background: #f5f5f5; }
        .total { text-align: right; font-size: 1.2em; }
    </style>
</head>
<body>
<div class="facture-box">
    <h2>Facture #{{ $commande->id ?? '' }}</h2>
    <table class="details">
        <tr>
            <td><strong>Nom:</strong> {{ $facturation->prenom ?? '' }} {{ $facturation->nom ?? '' }}</td>
            <td><strong>Date:</strong> {{ $commande->created_at->format('d/m/Y') ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Adresse:</strong> {{ $facturation->adresse ?? '' }}, {{ $facturation->ville ?? '' }}, {{ $facturation->region ?? '' }}, {{ $facturation->code_postal ?? '' }}</td>
            <td><strong>Email:</strong> {{ $facturation->email ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Téléphone:</strong> {{ $facturation->telephone ?? '' }}</td>
            <td><strong>Note:</strong> {{ $facturation->note_commande ?? '' }}</td>
        </tr>
    </table>
    <h3>Produits commandés</h3>
    <table class="produits">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($produits as $produit)
            <tr>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->quantite }}</td>
                <td>{{ number_format($produit->prix_unitaire, 2, ',', ' ') }} DH</td>
                <td>{{ number_format($produit->prix_unitaire * $produit->quantite, 2, ',', ' ') }} DH</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p class="total"><strong>Total :</strong> {{ number_format($commande->total, 2, ',', ' ') }} DH</p>
</div>
</body>
</html>
