@extends('public_base')

@section('styles')
<style>
.casque {
    position: relative;
    top: 0 !important;
    margin-top: 0 !important;
    padding-top: 0 !important;
    /* Optionnel : ajuster la hauteur ou l'alignement si besoin */
    display: block;
}
.hero-img-wrap {
    display: flex;
    align-items: flex-start;
}
</style>
@endsection

@section('content')
   <!-- Début de la section Héros -->
   <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>À propos de nous</h1>
              <p class="mb-4">
                Découvrez notre plateforme dédiée à vous offrir des produits de
                qualité et un service exceptionnel.
              </p>
              <p>
                <a href="{{ url('/') }}" class="btn btn-secondary me-2">Se connecter</a>
                <a href="{{ url('/signup') }}" class="btn btn-white-outline">S'inscrire</a>
              </p>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="hero-img-wrap">
              <img src="https://www.pngall.com/wp-content/uploads/5/Gaming-Headset-PNG-Free-Download.png" class="img-fluid casque" alt="Gaming Headset" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de la section Héros -->

    <!-- Début de la section Équipe -->
    <div class="untree_co-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-5 mx-auto text-center">
            <h2 class="section-title">Notre Équipe</h2>
          </div>
        </div>

        <div class="row justify-content-center">
          <!-- Début Colonne 1 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5" id="equipe">
            <img src="{{ asset('images/user-img.png') }}" class="img-fluid mb-5" />
            <h3>
              <a href="#"><span class="">Abderrahman</span> Bouanani</a>
            </h3>
            <span class="d-block position mb-4">Étudiant, ENS Marrakech CLE Info S6</span>
            <p>
              Passionné par l'informatique et le développement, Abderrahman
              apporte une perspective fraîche à notre équipe.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark">En savoir plus <span class="icon-arrow_forward"></span></a>
            </p>
          </div>
          <!-- Fin Colonne 1 -->

          <!-- Début Colonne 2 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5">
            <img src="{{ asset('images/user-img.png') }}" class="img-fluid mb-5" />
            <h3>
              <a href="#"><span class="">Amine</span> Abou-Laiche</a>
            </h3>
            <span class="d-block position mb-4">Étudiant, ENS Marrakech CLE Info S6</span>
            <p>
              Amine excelle dans la résolution de problèmes et apporte une
              expertise technique précieuse à nos projets.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark">En savoir plus <span class="icon-arrow_forward"></span></a>
            </p>
          </div>
          <!-- Fin Colonne 2 -->

          <!-- Début Colonne 3 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5">
            <img src="{{ asset('images/user-img.png') }}" class="img-fluid mb-5" />
            <h3>
              <a href="#"><span class="">Prof. Oumaima</span> Stitini</a>
            </h3>
            <span class="d-block position mb-4">Professeur encadrant, Spécialiste en IA</span>
            <p>
              Le Prof. Stitini guide notre équipe avec son expertise en IA,
              inspirant l'innovation dans nos projets.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark">En savoir plus <span class="icon-arrow_forward"></span></a>
            </p>
          </div>
          <!-- Fin Colonne 3 -->

          <!-- Début Colonne 4 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5">
            <img src="{{ asset('images/user-img.png') }}" class="img-fluid mb-5" />
            <h3>
              <a href="#"><span class="">Prof. Noureddine</span> Zahid</a>
            </h3>
            <span class="d-block position mb-4">Professeur encadrant, Spécialiste en Didactique Informatique</span>
            <p>
              Le Prof. Zahid guide notre équipe dans l'intégration pédagogique et l'optimisation de l'expérience utilisateur sur le projet.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark">En savoir plus <span class="icon-arrow_forward"></span></a>
            </p>
          </div>
          <!-- Fin Colonne 4 -->
        </div>
      </div>
    </div>
    <!-- Fin de la section Équipe -->

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
                          <p>&ldquo;J'ai commandé plusieurs fois et je suis toujours satisfaite. Livraison rapide et produits bien emballés.&rdquo;</p>
                        </blockquote>
                        <div class="author-info">
                          <div class="author-pic">
                            <img src="{{ asset('images/user-img.png') }}" alt="Nadia El Idrissi" class="img-fluid" />
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
                          <p>&ldquo;Interface claire, bons prix, et service client très réactif. Je recommande à 100 %.&rdquo;</p>
                        </blockquote>
                        <div class="author-info">
                          <div class="author-pic">
                            <img src="{{ asset('images/user-img.png') }}" alt="Omar Bakkali" class="img-fluid" />
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
                          <p>&ldquo;Je suis tombé sur ShopAll par hasard et j'ai adoré l'expérience d'achat. Je reviendrai, c'est sûr !&rdquo;</p>
                        </blockquote>
                        <div class="author-info">
                          <div class="author-pic">
                            <img src="{{ asset('images/user-img.png') }}" alt="Salma Rami" class="img-fluid" />
                          </div>
                          <h3 class="font-weight-bold">Salma Rami</h3>
                          <span class="position d-block mb-3">Nouvelle cliente</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Testimonial Slider for Clients -->
@endsection 