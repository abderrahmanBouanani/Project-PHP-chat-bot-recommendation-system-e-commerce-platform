@extends('vendeur_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
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
                <a href="" class="btn btn-secondary me-2">Acheter maintenant</a
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

    <!-- Début de la section Pourquoi nous choisir -->
    <div class="why-choose-section">
      <div class="container">
        <div class="row justify-content-between align-items-center">
          <div class="col-lg-6">
            <h2 class="section-title">Pourquoi nous choisir</h2>
            <p>
              Nous nous engageons à offrir une large gamme de produits de
              qualité et une expérience d'achat fluide à nos clients.
            </p>

            <div class="row my-5">
              <div class="col-6 col-md-6">
                <div class="feature">
                  <div class="icon">
                    <img src="../images/truck.svg" alt="Image" class="imf-fluid" />
                  </div>
                  <h3>Livraison rapide et gratuite</h3>
                  <p>
                    Profitez d'une livraison rapide et gratuite pour tous vos
                    achats.
                  </p>
                </div>
              </div>

              <div class="col-6 col-md-6">
                <div class="feature">
                  <div class="icon">
                    <img src="../images/bag.svg" alt="Image" class="imf-fluid" />
                  </div>
                  <h3>Facile à acheter</h3>
                  <p>
                    Notre processus d'achat est simple et intuitif pour votre
                    commodité.
                  </p>
                </div>
              </div>

              <div class="col-6 col-md-6">
                <div class="feature">
                  <div class="icon">
                    <img
                      src="../images/support.svg"
                      alt="Image"
                      class="imf-fluid"
                    />
                  </div>
                  <h3>Support 24/7</h3>
                  <p>
                    Notre équipe de support est disponible 24/7 pour répondre à
                    vos questions.
                  </p>
                </div>
              </div>

              <div class="col-6 col-md-6">
                <div class="feature">
                  <div class="icon">
                    <img
                      src="../images/return.svg"
                      alt="Image"
                      class="imf-fluid"
                    />
                  </div>
                  <h3>Retours sans tracas</h3>
                  <p>
                    Nous offrons une politique de retour simple pour votre
                    tranquillité d'esprit.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-5">
            <div class="img-wrap">
              <img
                src="../images/why-choose-us-img.jpg"
                alt="Image"
                class="img-fluid"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de la section Pourquoi nous choisir -->

    <!-- Début de la section Équipe -->
    <div class="untree_co-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-5 mx-auto text-center">
            <h2 class="section-title">Notre Équipe</h2>
          </div>
        </div>

        <div class="row">
          <!-- Début Colonne 1 -->
          <div class="col-12 col-md-6 col-lg-4 mb-5 mb-md-0">
            <img src="../images/user-img.png" class="img-fluid mb-5" />
            <h3 clas>
              <a href="#"><span class="">Abderrahman</span> Bouanani</a>
            </h3>
            <span class="d-block position mb-4"
              >Étudiant, ENS Marrakech CLE Info S5</span
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
          <div class="col-12 col-md-6 col-lg-4 mb-5 mb-md-0">
            <img src="../images/user-img.png" class="img-fluid mb-5" />

            <h3 clas>
              <a href="#"><span class="">Amine</span> Abou-Laiche</a>
            </h3>
            <span class="d-block position mb-4"
              >Étudiant, ENS Marrakech CLE Info S5</span
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
          <div class="col-12 col-md-6 col-lg-4 mb-5 mb-md-0">
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
        </div>
      </div>
    </div>
    <!-- Fin de la section Équipe -->

    <!-- Début du Slider de Témoignages -->
    <div class="testimonial-section before-footer-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 mx-auto text-center">
            <h2 class="section-title">Témoignages</h2>
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
                            &ldquo;La diversité des produits et le service
                            client de qualité m'ont vraiment
                            impressionné.&rdquo;
                          </p>
                        </blockquote>

                        <div class="author-info">
                          <div class="author-pic">
                            <img
                              src="../images/user-img.png"
                              alt="Youssef Amrani"
                              class="img-fluid"
                            />
                          </div>
                          <h3 class="font-weight-bold">Youssef Amrani</h3>
                          <span class="position d-block mb-3"
                            >PDG, Tech Innovate Maroc</span
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- FIN item -->

                <div class="item">
                  <div class="row justify-content-center">
                    <div class="col-lg-8 mx-auto">
                      <div class="testimonial-block text-center">
                        <blockquote class="mb-5">
                          <p>
                            &ldquo;Les produits sont arrivés rapidement et en
                            parfait état. Une plateforme de confiance.&rdquo;
                          </p>
                        </blockquote>

                        <div class="author-info">
                          <div class="author-pic">
                            <img
                              src="../images/user-img.png"
                              alt="Fatima Zahra Benali"
                              class="img-fluid"
                            />
                          </div>
                          <h3 class="font-weight-bold">Fatima Zahra Benali</h3>
                          <span class="position d-block mb-3"
                            >Architecte d'intérieur, Design Élégance</span
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- FIN item -->

                <div class="item">
                  <div class="row justify-content-center">
                    <div class="col-lg-8 mx-auto">
                      <div class="testimonial-block text-center">
                        <blockquote class="mb-5">
                          <p>
                            &ldquo;J'ai trouvé exactement ce dont j'avais besoin
                            à un excellent prix. Le site est facile à naviguer,
                            et le service est impeccable.&rdquo;
                          </p>
                        </blockquote>

                        <div class="author-info">
                          <div class="author-pic">
                            <img
                              src="../images/user-img.png"
                              alt="Karim Tazi"
                              class="img-fluid"
                            />
                          </div>
                          <h3 class="font-weight-bold">Karim Tazi</h3>
                          <span class="position d-block mb-3"
                            >Propriétaire, Café Lumière</span
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- FIN item -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin du Slider de Témoignages --> 
@endsection <!-- Ici finit le contenu spécifique à cette page -->




