@extends('vendeur_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
  <!-- Début de la section Héros -->
  <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>Nos Services</h1>
              <p class="mb-4">
                Découvrez comment ShopAll peut vous aider à trouver les
                meilleurs produits et à améliorer votre expérience d'achat en
                ligne.
              </p>
              <p>
                <a href="" class="btn btn-secondary me-2">Acheter maintenant</a
                ><a href="#" class="btn btn-white-outline">Explorer</a>
              </p>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="hero-img-wrap">
              <img
                src="https://www.pngall.com/wp-content/uploads/5/Gaming-Headset-PNG-Free-Download.png"
                class="img-fluid casque"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de la section Héros -->

    <!-- Début de la section Pourquoi devenir vendeur -->
<div class="why-choose-section" id="service">
  <div class="container">
    <h2 class="section-title text-center mb-5">Pourquoi devenir vendeur sur ShopAll ?</h2>
    <div class="row my-5">
      <div class="col-6 col-md-6 col-lg-3 mb-4">
        <div class="feature">
          <div class="icon">
            <img src="../images/truck.svg" alt="Livraison gérée" class="imf-fluid" />
          </div>
          <h3>Livraison gérée par ShopAll</h3>
          <p>
            Nous nous occupons de la logistique pour que vous puissiez vous concentrer sur vos ventes.
          </p>
        </div>
      </div>

      <div class="col-6 col-md-6 col-lg-3 mb-4">
        <div class="feature">
          <div class="icon">
            <img src="../images/bag.svg" alt="Interface simple" class="imf-fluid" />
          </div>
          <h3>Interface de gestion simple</h3>
          <p>
            Ajoutez, modifiez ou supprimez vos produits facilement via votre espace vendeur.
          </p>
        </div>
      </div>

      <div class="col-6 col-md-6 col-lg-3 mb-4">
        <div class="feature">
          <div class="icon">
            <img src="../images/support.svg" alt="Support dédié" class="imf-fluid" />
          </div>
          <h3>Support vendeur dédié</h3>
          <p>
            Une équipe à votre écoute pour vous accompagner dans votre activité.
          </p>
        </div>
      </div>

      <div class="col-6 col-md-6 col-lg-3 mb-4">
        <div class="feature">
          <div class="icon">
            <img src="../images/return.svg" alt="Paiements rapides" class="imf-fluid" />
          </div>
          <h3>Paiements rapides</h3>
          <p>
            Recevez vos paiements en toute sécurité, sans délai inutile.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin de la section Pourquoi devenir vendeur -->


   
 <!-- Start Testimonial Slider -->
 <div class="testimonial-section before-footer-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 mx-auto text-center">
        <h2 class="section-title">Témoignages de nos vendeurs</h2>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="testimonial-slider-wrap text-center">
          <div id="testimonial-nav">
            <span class="prev" data-controls="prev"
              ><span class="fa fa-chevron-left"></span
            ></span>
            <span class="next" data-controls="next"
              ><span class="fa fa-chevron-right"></span
            ></span>
          </div>

          <div class="testimonial-slider">

            <div class="item">
              <div class="row justify-content-center">
                <div class="col-lg-8 mx-auto">
                  <div class="testimonial-block text-center">
                    <blockquote class="mb-5">
                      <p>
                        &ldquo;Je vends des smartphones depuis des années, mais c'est la première fois que je trouve une plateforme aussi fluide, avec une visibilité exceptionnelle.&rdquo;
                      </p>
                    </blockquote>
                    <div class="author-info">
                      <div class="author-pic">
                        <img src="../images/user-img.png" alt="Omar Benhassine" class="img-fluid" />
                      </div>
                      <h3 class="font-weight-bold">Omar Benhassine</h3>
                      <span class="position d-block mb-3">Vendeur de téléphones mobiles</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item">
              <div class="row justify-content-center">
                <div class="col-lg-8 mx-auto">
                  <div class="testimonial-block text-center">
                    <blockquote class="mb-5">
                      <p>
                        &ldquo;J’ai pu écouler toute ma collection de montres connectées en quelques semaines. La gestion des stocks est super intuitive.&rdquo;
                      </p>
                    </blockquote>
                    <div class="author-info">
                      <div class="author-pic">
                        <img src="../images/user-img.png" alt="Sanae Kabbaj" class="img-fluid" />
                      </div>
                      <h3 class="font-weight-bold">Sanae Kabbaj</h3>
                      <span class="position d-block mb-3">Spécialiste montres & accessoires</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item">
              <div class="row justify-content-center">
                <div class="col-lg-8 mx-auto">
                  <div class="testimonial-block text-center">
                    <blockquote class="mb-5">
                      <p>
                        &ldquo;En tant que revendeur de PC, je suis impressionné par la rapidité des commandes et la satisfaction des clients.&rdquo;
                      </p>
                    </blockquote>
                    <div class="author-info">
                      <div class="author-pic">
                        <img src="../images/user-img.png" alt="Reda El Idrissi" class="img-fluid" />
                      </div>
                      <h3 class="font-weight-bold">Reda El Idrissi</h3>
                      <span class="position d-block mb-3">Distributeur PC & accessoires</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item">
              <div class="row justify-content-center">
                <div class="col-lg-8 mx-auto">
                  <div class="testimonial-block text-center">
                    <blockquote class="mb-5">
                      <p>
                        &ldquo;Les fans de gaming adorent mes chaises ergonomiques, et grâce à cette plateforme, mes ventes ont décollé.&rdquo;
                      </p>
                    </blockquote>
                    <div class="author-info">
                      <div class="author-pic">
                        <img src="../images/user-img.png" alt="Yassine Akdim" class="img-fluid" />
                      </div>
                      <h3 class="font-weight-bold">Yassine Akdim</h3>
                      <span class="position d-block mb-3">Vendeur de chaises gamer</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div> <!-- end .testimonial-slider -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin du Slider de Témoignages -->
@endsection <!-- Ici finit le contenu spécifique à cette page -->




