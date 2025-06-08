@extends('vendeur_base')

@section('content')
  <!-- Start Hero Section -->
  <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>
                Espaces Élégants <span clsas="d-block">d'Outils Modernes</span>
              </h1>
              <p class="mb-4">
                votre destination en ligne pour découvrir une large gamme de
                produits de qualité à des prix compétitifs. Profitez d'une
                expérience d'achat simple, rapide et sécurisée, avec des
                promotions exclusives et une livraison à votre porte.
              </p>
              <p>
                <a href="shop.html" class="btn btn-secondary me-2"
                  >Vendre maintenant</a
                ><a href="#" class="btn btn-white-outline">Explorer</a>
              </p>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="hero-img-wrap">
              <img
                src="../images/tablette.png"
                class="img-fluid"
                style="width: 1200px; max-height: 700px; margin-top: 20px; margin-bottom: 20px; object-fit: contain;"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Hero Section -->

    

    <!-- Start Why Choose Us Section -->
<div class="why-choose-section">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-6">
        <h2 class="section-title">Pourquoi choisir notre plateforme</h2>
        <p>Boostez vos ventes en toute simplicité grâce à notre solution pensée pour les vendeurs.</p>

        <div class="row my-5">
          <div class="col-6 col-md-6">
            <div class="feature">
              <div class="icon">
                <img src="../images/truck.svg" alt="Image" class="imf-fluid" />
              </div>
              <h3>Livraison rapide & gratuite</h3>
              <p>
                Offrez à vos clients une expérience d'achat fluide avec une livraison rapide et sans frais.
              </p>
            </div>
          </div>

          <div class="col-6 col-md-6">
            <div class="feature">
              <div class="icon">
                <img src="../images/bag.svg" alt="Image" class="imf-fluid" />
              </div>
              <h3>Gestion simple des ventes</h3>
              <p>
                Gérez facilement vos produits, commandes et promotions depuis une interface intuitive.
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
              <h3>Support dédié 24/7</h3>
              <p>
                Bénéficiez d'un accompagnement continu pour répondre à toutes vos questions rapidement.
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
              <h3>Processus de retour simplifié</h3>
              <p>
                Facilitez la gestion des retours et augmentez la satisfaction de vos clients.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="img-wrap">
          <img
            src="../images/gaming_room.png"
            alt="Image"
            class="img-fluid"
          />
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Why Choose Us Section -->


    <!-- Start Simple Product Carousel Section -->
    <div class="simple-product-carousel-section before-footer-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 mx-auto text-center">
            <h2 class="section-title">Vos Produits Vedettes</h2>
          </div>
        </div>

        <div class="simple-carousel-wrapper">
          <div class="simple-carousel-container">
            <div class="simple-carousel-track" id="simpleCarouselTrack">
              <!-- Produit 1 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="https://cdn.affilizz.com/__s__/images/products/fr_FR/67912a80/5ab03c/21d0/2856a9_main.webp?p=wc_picture" alt="Téléphone Haut de Gamme" style="mix-blend-mode: multiply">
                  <div class="simple-product-badge">Téléphone</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Téléphone Haut de Gamme</h3>
                  <div class="simple-product-price">23000.00 DH</div>
                </div>
              </div>

              <!-- Produit 2 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop" alt="Montre Classique">
                  <div class="simple-product-badge">Montre</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Montre Classique</h3>
                  <div class="simple-product-price">1299.00 DH</div>
                </div>
              </div>

              <!-- Produit 3 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=400&fit=crop" alt="Montre Tactile">
                  <div class="simple-product-badge">Montre Tactile</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Montre Tactile Sport</h3>
                  <div class="simple-product-price">1799.00 DH</div>
                </div>
              </div>

              <!-- Produit 4 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop" alt="PC Portable Ultra-Puissant">
                  <div class="simple-product-badge">PC</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">PC Portable Ultra-Puissant</h3>
                  <div class="simple-product-price">8999.00 DH</div>
                </div>
              </div>

              <!-- Produit 5 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                <img src="https://m.media-amazon.com/images/I/715RcGknqwL.jpg" alt="Chaise Gamer Ergonomique">


                  <div class="simple-product-badge">Chaise Gamer</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Chaise Gamer Ergonomique</h3>
                  <div class="simple-product-price">2599.00 DH</div>
                </div>
              </div>
            </div>
          </div>

          <div class="simple-carousel-navigation">
            <button class="simple-nav-btn" id="simplePrevBtn">‹</button>
            <div class="simple-carousel-dots" id="simpleDots">
              <div class="simple-dot active" data-slide="0"></div>
              <div class="simple-dot" data-slide="1"></div>
              <div class="simple-dot" data-slide="2"></div>
            </div>
            <button class="simple-nav-btn" id="simpleNextBtn">›</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Simple Product Carousel Section -->

    <!-- Start Blog Section -->
    <div class="blog-section" id="blog">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-6">
            <h2 class="section-title">Blog</h2>
          </div>
          <div class="col-md-6 text-start text-md-end"></div>
        </div>

        <div class="row">
          <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
            <div class="post-entry">
              <a href="#" class="post-thumbnail"
                ><img
                  src="../images/article4.jpg"
                  alt="Image"
                  class="img-fluid"
                  style="mix-blend-mode: multiply"
              /></a>
              <div class="post-content-entry">
                <h3>
                  <a href="#"
                    >Découvrez nos ordinateurs spécialement conçus pour les
                    primo-accédants
                  </a>
                </h3>
                <div class="meta">
                  <span>by <a href="#">Kristin Watson</a></span>
                  <span>on <a href="#">Dec 19, 2021</a></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
            <div class="post-entry">
              <a href="#" class="post-thumbnail"
                ><img
                  src="../images/article3.jpg"
                  alt="Image"
                  class="img-fluid"
                  style="
                    mix-blend-mode: multiply;
                    height: 300px;
                    width: 600px;"
              /></a>
              <div class="post-content-entry">
                <h3>
                  <a href="#"
                    >Des chaises confortables et stylées, parfaites pour un
                    premier aménagement !</a
                  >
                </h3>
                <div class="meta">
                  <span>by <a href="#">Robert Fox</a></span>
                  <span>on <a href="#">Dec 15, 2021</a></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
            <div class="post-entry">
              <a href="#" class="post-thumbnail"
                ><img
                  src="../images/article6.jpg"
                  alt="Image"
                  class="img-fluid"
                  style="mix-blend-mode: multiply"
              /></a>
              <div class="post-content-entry">
                <h3>
                  <a href="#"
                    >Des montres élégantes et abordables pour votre première
                    collection !</a
                  >
                </h3>
                <div class="meta">
                  <span>by <a href="#">Kristin Watson</a></span>
                  <span>on <a href="#">Dec 12, 2021</a></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Blog Section -->

    <!-- Start Testimonial Slider for Clients -->
    <div class="testimonial-section">
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
                  ><span class="icon-arrow_back"></span
                ></span>
                <span class="next" data-controls="next"
                  ><span class="icon-arrow_forward"></span
                ></span>
              </div>

              <div class="testimonial-slider">
                <div class="item">
                  <div class="row justify-content-center">
                    <div class="col-lg-8 mx-auto">
                      <div class="testimonial-block text-center">
                        <blockquote class="mb-5">
                          <p>
                            &ldquo;J'ai pu écouler toute ma collection de montres connectées en quelques semaines. La gestion des stocks est super intuitive.&rdquo;
                          </p>
                        </blockquote>

                        <div class="author-info">
                          <div class="author-pic">
                            <img
                              src="../images/person-1.jpg"
                              alt="Maria Jones"
                              class="img-fluid"
                            />
                          </div>
                          <h3 class="font-weight-bold">Maria Jones</h3>
                          <span class="position d-block mb-3"
                            >Vendeur, Apple Watch</span
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- END item -->

                <div class="item">
                  <div class="row justify-content-center">
                    <div class="col-lg-8 mx-auto">
                      <div class="testimonial-block text-center">
                        <blockquote class="mb-5">
                          <p>
                            &ldquo;Une plateforme exceptionnelle pour vendre mes produits électroniques. Le support client est réactif et professionnel.&rdquo;
                          </p>
                        </blockquote>

                        <div class="author-info">
                          <div class="author-pic">
                            <img
                              src="../images/person-2.jpg"
                              alt="John Smith"
                              class="img-fluid"
                            />
                          </div>
                          <h3 class="font-weight-bold">John Smith</h3>
                          <span class="position d-block mb-3"
                            >Vendeur, Accessoires Tech</span
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- END item -->

                <div class="item">
                  <div class="row justify-content-center">
                    <div class="col-lg-8 mx-auto">
                      <div class="testimonial-block text-center">
                        <blockquote class="mb-5">
                          <p>
                            &ldquo;La meilleure décision que j'ai prise pour mon business en ligne. Les ventes ont augmenté de 200% depuis que j'utilise cette plateforme.&rdquo;
                          </p>
                        </blockquote>

                        <div class="author-info">
                          <div class="author-pic">
                            <img
                              src="../images/person-3.jpg"
                              alt="Sarah Johnson"
                              class="img-fluid"
                            />
                          </div>
                          <h3 class="font-weight-bold">Sarah Johnson</h3>
                          <span class="position d-block mb-3"
                            >Vendeur, Smartphones</span
                          >
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
@endsection
