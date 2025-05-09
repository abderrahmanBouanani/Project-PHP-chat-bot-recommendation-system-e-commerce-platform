@extends('client_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
  <!-- Start Hero Section -->
  <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>Shop</h1>
            </div>
          </div>
          <div class="col-lg-7" style="margin-right: 200px">
            <label for="search" class="form-label text-light">Rechercher</label>
            <input
              type="text"
              id="search"
              class="form-control w-50"
              placeholder="Tapez pour chercher"
            />
            <br />
            <label for="filtrer" class="form-label text-light">Filtrer</label>
            <select id="filtrer" class="form-control w-50">
              <option value="">Tous les produits</option>
              <option value="prix">Prix (croissant)</option>
              <option value="categorie">Catégorie</option>
            </select>
            <label
              for="categorie"
              class="form-label text-light mt-3"
              style="display: none"
              >Choisissez une catégorie</label
            >
            <select
              id="categorie"
              class="form-control w-50"
              style="display: none"
            >
              <option value="">Toutes les catégories</option>
              <option value="Écrans">Écrans</option>
              <option value="Ordinateurs">Ordinateurs</option>
              <option value="Montres">Montres</option>
              <option value="Chaises">Chaises</option>
              <option value="Claviers">Claviers</option>
              <option value="Téléphones">Téléphone</option>
              <option value="MontresTactiles">Montres tactiles</option>
            </select>
          </div>

          <div class="col-lg-7">
            <div class="hero-img-wrap">
              <img
                src="https://assets.corsair.com/image/upload/c_pad,q_85,h_1100,w_1100,f_auto/products/Cases/2500D/CC-9011263-WW/Gallery/2500D_AIRFLOW_BLACK_HERO_01.webp"
                class="img-fluid pc-gamer"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Products Section -->
    <div class="untree_co-section product-section before-footer-section">
      <div class="container">
        <div class="row" id="product-list">
          <!-- Les produits seront générés dynamiquement ici -->
        </div>
      </div>
    </div>
    <!-- End Products Section -->

   




@endsection <!-- Ici finit le contenu spécifique à cette page -->






