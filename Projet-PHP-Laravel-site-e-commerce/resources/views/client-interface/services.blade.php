@extends('client_base') <!-- Cette ligne indique d'utiliser le layout de base -->

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
                <a href="{{url('/client_shop')}}" class="btn btn-secondary me-2">Acheter maintenant</a
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

    <!-- Début de la section Pourquoi nous choisir -->
    <div class="why-choose-section">
      <div class="container">
        <div class="row my-5">
          <div class="col-6 col-md-6 col-lg-3 mb-4">
            <div class="feature">
              <div class="icon">
                <img src="../images/truck.svg" alt="Image" class="imf-fluid" />
              </div>
              <h3>Livraison rapide et gratuite</h3>
              <p>
                Profitez d'une livraison rapide et gratuite pour tous vos achats
                sur ShopAll.
              </p>
            </div>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-4">
            <div class="feature">
              <div class="icon">
                <img src="../images/bag.svg" alt="Image" class="imf-fluid" />
              </div>
              <h3>Achat facile</h3>
              <p>
                Notre plateforme intuitive rend vos achats simples et agréables.
              </p>
            </div>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-4">
            <div class="feature">
              <div class="icon">
                <img src="../images/support.svg" alt="Image" class="imf-fluid" />
              </div>
              <h3>Support 24/7</h3>
              <p>
                Notre équipe de support est disponible à tout moment pour
                répondre à vos questions.
              </p>
            </div>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-4">
            <div class="feature">
              <div class="icon">
                <img src="../images/return.svg" alt="Image" class="imf-fluid" />
              </div>
              <h3>Retours sans tracas</h3>
              <p>
                Politique de retour simple pour votre tranquillité d'esprit.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de la section Pourquoi nous choisir -->

    <!-- Start Testimonial Slider for Clients -->
<div class="testimonial-section before-footer-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 mx-auto text-center">
        <h2 class="section-title">Ce que disent nos clients</h2>
        <p class="mb-5">Des avis authentiques de nos utilisateurs satisfaits</p>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="testimonial-slider-wrap text-center">
          <div id="testimonial-nav">
            <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
            <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
          </div>

          <div class="testimonial-slider">
            <div class="item">
              <div class="row justify-content-center">
                <div class="col-lg-8 mx-auto">
                  <div class="testimonial-block text-center">
                    <blockquote class="mb-4">
                      <p>
                        &ldquo;J'ai commandé plusieurs fois et je suis toujours satisfaite. Livraison rapide et produits bien emballés.&rdquo;
                      </p>
                    </blockquote>
                    <div class="author-info">
                      <div class="author-pic">
                        <img src="../images/user-img.png" alt="Nadia El Idrissi" class="img-fluid" />
                      </div>
                      <h3 class="font-weight-bold">Nadia El Idrissi</h3>
                      <span class="position d-block mb-3">Cliente fidèle</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item">
              <div class="row justify-content-center">
                <div class="col-lg-8 mx-auto">
                  <div class="testimonial-block text-center">
                    <blockquote class="mb-4">
                      <p>
                        &ldquo;Interface claire, bons prix, et service client très réactif. Je recommande à 100 %.&rdquo;
                      </p>
                    </blockquote>
                    <div class="author-info">
                      <div class="author-pic">
                        <img src="../images/user-img.png" alt="Omar Bakkali" class="img-fluid" />
                      </div>
                      <h3 class="font-weight-bold">Omar Bakkali</h3>
                      <span class="position d-block mb-3">Acheteur régulier</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item">
              <div class="row justify-content-center">
                <div class="col-lg-8 mx-auto">
                  <div class="testimonial-block text-center">
                    <blockquote class="mb-4">
                      <p>
                        &ldquo;Je suis tombé sur ShopAll par hasard et j'ai adoré l'expérience d'achat. Je reviendrai, c'est sûr !&rdquo;
                      </p>
                    </blockquote>
                    <div class="author-info">
                      <div class="author-pic">
                        <img src="../images/user-img.png" alt="Salma Rami" class="img-fluid" />
                      </div>
                      <h3 class="font-weight-bold">Salma Rami</h3>
                      <span class="position d-block mb-3">Nouvelle cliente</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- END item -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Testimonial Slider for Clients -->


@endsection <!-- Ici finit le contenu spécifique à cette page -->





