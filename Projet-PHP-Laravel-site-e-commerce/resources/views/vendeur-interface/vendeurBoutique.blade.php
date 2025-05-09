@extends('vendeur_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
  <!-- Start Hero Section -->
  <form action="{{ route('vendeur.addProduct') }}" method="post" enctype="multipart/form-data">
       @csrf
       <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
         <div class="intro-excerpt">
           <h1>Ma Boutique</h1>
           <div class="product-form mb-5">
          <h2 style="color: white">Ajouter un Produit</h2>
          <input
            type="text"
            id="product-name"
            class="form-control mb-2"
            placeholder="Nom du produit"
            name="nom"
          />
          <input
            type="number"
            id="product-price"
            class="form-control mb-2"
            placeholder="Prix du produit"
            name="prix_unitaire"
          />
          <select id="product-category" class="form-control mb-2" name="categorie">
            <option value="">Sélectionnez une catégorie</option>
            <option value="Ordinateurs">Ordinateurs</option>
            <option value="Écrans">Écrans</option>
            <option value="Montres">Montres</option>
            <option value="Chaises">Chaises</option>
            <option value="Claviers">Claviers</option>
            <option value="Téléphones">Téléphones</option>
            <option value="MontresTactiles">Montres tactiles</option>
          </select>
          <input
            type="number"
            id="product-quantite"
            class="form-control mb-2"
            placeholder="Quantité du produit"
            name="quantite"
          />
          <input
            type="file"
            id="product-image"
            class="form-control mb-2"
            name="image"
          />
          <input type="hidden" name="vendeur_id" value="{{ Auth::id() }}" />
          <button
            id="add-product"
            class="btn btn-primary"
            style="background-color: rgb(55, 142, 55)"
            name="add-product"
            type="submit"
          >
            Ajouter le produit
          </button>
           </div>
         </div>
          </div>
        </div>
      </div>
       </div>
    </form>
    
    <!-- End Hero Section -->

    <!-- Start Product Table Section -->
    <div class="container">
      <div class="row mb-5">
        <form class="col-md-12" method="post">
          <div class="site-blocks-table">
            <table class="table table-bordered" id="product-table">
              <thead>
              <tr>
              <th class="product-thumbnail">Id</th>
                <th class="product-thumbnail">Image</th>
                <th class="product-name">Produit</th>
                <th class="product-price">Prix</th>
                <th class="product-category">Catégorie</th>
                <th class="product-quantite">Quantité</th>
                <th class="product-remove">Supprimer</th>
              </tr>
              </thead>
              <tbody>
              @foreach($produits as $produit)
              <tr>
              <td>{{ $produit->id }}</td>
                <td>
                @if($produit->image)
                <img src="{{ asset('storage/'.$produit->image) }}" alt="{{ $produit->nom }}" style="width: 100px; height: auto; mix-blend-mode: multiply;">
                @endif
                </td>
                <td>{{ $produit->nom }}</td>
                <td>{{ number_format($produit->prix_unitaire) }} DH</td>
                <td>{{ $produit->categorie }}</td>
                <td>{{ $produit->quantite }}</td>
                <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" onsubmit="return confirm('Es-tu sûr de vouloir supprimer ce produit ?');">
                @csrf
                @method('DELETE')
                <td><button class="btn btn-danger btn-sm remove-product">Supprimer</button></td>
                </form>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
    <!-- End Product Table Section -->

@endsection <!-- Ici finit le contenu spécifique à cette page -->









