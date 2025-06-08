@extends('vendeur_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
@if(!session('user'))
    <div class="container mt-5">
        <div class="alert alert-warning text-center">
            <h4>Veuillez vous connecter pour accéder à cette page</h4>
            <a href="{{ url('/') }}" class="btn btn-primary mt-3">Se connecter</a>
        </div>
    </div>
@else
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
                <a href="{{url('/vendeur_shop')}}" class="btn btn-secondary me-2">Vendre maintenant</a
                ><a href="#" class="btn btn-white-outline">Explorer</a>
              </p>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="hero-img-wrap">
              <img src="../images/couch.png" class="img-fluid" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de la section Héros -->

    

    <!-- Début de la section Équipe -->
    <div class="untree_co-section" >
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-5 mx-auto text-center">
            <h2 class="section-title">Notre Équipe</h2>
          </div>
        </div>

        <div class="row justify-content-center">
          <!-- Début Colonne 1 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5" id="equipe">
            <img src="../images/user-img.png" class="img-fluid mb-5" />
            <h3 clas>
              <a href="#"><span class="">Abderrahman</span> Bouanani</a>
            </h3>
            <span class="d-block position mb-4"
              >Étudiant, ENS Marrakech CLE Info S6</span
            >
            <p>
              Passionné par l'informatique et le développement, Abderrahman
              apporte une perspective fraîche à notre équipe.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark"
                >En savoir plus <span class="icon-arrow_forward"></span
              ></a>
            </p>
          </div>
          <!-- Fin Colonne 1 -->

          <!-- Début Colonne 2 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5">
            <img src="../images/user-img.png" class="img-fluid mb-5" />

            <h3 clas>
              <a href="#"><span class="">Amine</span> Abou-Laiche</a>
            </h3>
            <span class="d-block position mb-4"
              >Étudiant, ENS Marrakech CLE Info S6</span
            >
            <p>
              Amine excelle dans la résolution de problèmes et apporte une
              expertise technique précieuse à nos projets.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark"
                >En savoir plus <span class="icon-arrow_forward"></span
              ></a>
            </p>
          </div>
          <!-- Fin Colonne 2 -->

          <!-- Début Colonne 3 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5">
            <img src="../images/user-img.png" class="img-fluid mb-5" />
            <h3 clas>
              <a href="#"><span class="">Prof. Oumaima</span> Stitini</a>
            </h3>
            <span class="d-block position mb-4"
              >Professeur encadrant, Spécialiste en IA</span
            >
            <p>
              Le Prof. Stitini guide notre équipe avec son expertise en IA,
              inspirant l'innovation dans nos projets.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark"
                >En savoir plus <span class="icon-arrow_forward"></span
              ></a>
            </p>
          </div>
          <!-- Fin Colonne 3 -->


          <!-- Début Colonne 4 -->
          <div class="col-6 col-md-3 col-lg-3 mb-5">
            <img src="../images/user-img.png" class="img-fluid mb-5" />
            <h3 clas>
              <a href="#"><span class="">Prof. Noureddine</span> Zahid</a>
            </h3>
            <span class="d-block position mb-4"
              >Professeur encadrant, Spécialiste en Didactique Informatique</span
            >
            <p>
            Le Prof. Zahid guide notre équipe dans l’intégration pédagogique et l’optimisation de l’expérience utilisateur sur le projet.
            </p>
            <p class="mb-0">
              <a href="#" class="more dark"
                >En savoir plus <span class="icon-arrow_forward"></span
              ></a>
            </p>
          </div>
          <!-- Fin Colonne 4 -->
        </div>
      </div>
    </div>
    <!-- Fin de la section Équipe -->

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
    @endif
@endsection <!-- Ici finit le contenu spécifique à cette page -->