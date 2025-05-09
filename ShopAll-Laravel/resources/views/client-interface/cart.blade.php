@extends('client_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
   <!-- Start Hero Section -->
   <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>Panier</h1>
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
          <form class="col-md-12" method="post">
            <div class="site-blocks-table">
              <table class="table">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Image</th>
                    <th class="product-name">Produit</th>
                    <th class="product-price">Prix</th>
                    <th class="product-quantity">Quantité</th>
                    <th class="product-total">Total</th>
                    
                  </tr>
                </thead>
                <tbody id="cart-items">
                  <!-- Les éléments du panier seront ajoutés ici dynamiquement -->
                </tbody>
              </table>
            </div>
          </form>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-6">
                <button  class="btn btn-outline-black btn-sm btn-block">
                  <a href="{{ url('/client_shop')}}" style="color: white; text-decoration: none;">
                  Continuer les achats
                  </a>
                </button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label class="text-black h4" for="coupon">Coupon</label>
                <p>Entrez votre code coupon si vous en avez un.</p>
              </div>
              <div class="col-md-8 mb-3 mb-md-0">
                <input
                  type="text"
                  class="form-control py-3"
                  id="coupon"
                  placeholder="Code coupon"
                />
              </div>
              <div class="col-md-4">
                <button class="btn btn-black" id="applyCoupon">
                  Appliquer Coupon
                </button>
              </div>
            </div>
          </div>
          <div class="col-md-6 pl-5">
            <div class="row justify-content-end">
              <div class="col-md-7">
                <div class="row">
                  <div class="col-md-12 text-right border-bottom mb-5">
                    <h3 class="text-black h4 text-uppercase">
                      Total du panier
                    </h3>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <span class="text-black">Sous-total</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black" id="subtotal">0.00 DH</strong>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <span class="text-black">Réduction</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black" id="discount">0.00 DH</strong>
                  </div>
                </div>
                <div class="row mb-5">
                  <div class="col-md-6">
                    <span class="text-black">Total</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black" id="total">0.00 DH</strong>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <button
                      class="btn btn-black btn-lg py-3 btn-block"
                      onclick="window.location=`{{url('/client/checkout')}}`"
                    >
                      Procéder au paiement
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection <!-- Ici finit le contenu spécifique à cette page -->

