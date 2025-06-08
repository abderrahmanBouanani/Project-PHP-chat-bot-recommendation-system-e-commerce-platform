@extends('client_base')

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
                  >Acheter maintenant</a
                ><a href="#" class="btn btn-white-outline">Explorer</a>
              </p>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="hero-img-wrap">
              <img
                src="../images/SETUP-4-Photoroom.png"
                class="img-fluid"
                style="width: 1200px; max-height: 700px; margin-top: 20px; margin-bottom: 20px; object-fit: contain;"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Product Section -->
    <div class="product-section">
      <div class="container">
        <div class="row">
          <!-- Start Column 1 -->
          <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
            <h2 class="mb-4 section-title">
              Conçu avec des matériaux d'excellence.
            </h2>
            <p class="mb-4">
              Nos produits sont soigneusement fabriqués à partir de matériaux de
              qualité supérieure, sélectionnés pour leur durabilité, leur
              performance et leur élégance. Chaque détail est pensé pour offrir
              une expérience exceptionnelle, alliant esthétique et fiabilité.
            </p>
            <p><a href="{{ url('/client_shop') }}" class="btn">Explorer</a></p>
          </div>
          <!-- End Column 1 -->

          <!-- Start Column 2 -->
          <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
            <a class="product-item" href="{{ url('/client_shop') }}">
              <img
                src="../images/pc1.jpg"
                class="img-fluid product-thumbnail"
                style="mix-blend-mode: multiply"
              />
              <h3 class="product-title">MSI</h3>
              <strong class="product-price">11500.00 DH</strong>

              <span class="icon-cross">
                <img src="../images/cross.svg" class="img-fluid" />
              </span>
            </a>
          </div>
          <!-- End Column 2 -->

          <!-- Start Column 3 -->
          <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
            <a class="product-item" href="{{ url('/client_shop') }}">
              <img
                src="../images/chaise3.jpg"
                class="img-fluid product-thumbnail"
                style="mix-blend-mode: multiply"
              />
              <h3 class="product-title">Chaise gamer</h3>
              <strong class="product-price">3000.00 DH</strong>

              <span class="icon-cross">
                <img src="../images/cross.svg" class="img-fluid" />
              </span>
            </a>
          </div>
          <!-- End Column 3 -->

          <!-- Start Column 4 -->
          <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
            <a class="product-item" href="{{ url('/client_shop') }}">
              <img
                src="../images/montre1.jpg"
                class="img-fluid product-thumbnail"
                style="mix-blend-mode: multiply"
              />
              <h3 class="product-title">World Time</h3>
              <strong class="product-price">5000.00 DH</strong>

              <span class="icon-cross">
                <img src="../images/cross.svg" class="img-fluid" />
              </span>
            </a>
          </div>
          <!-- End Column 4 -->
        </div>
      </div>
    </div>
    <!-- End Product Section -->

    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-6">
            <h2 class="section-title">Pourquoi nous choisir</h2>
            <p>Vivez dans la simplicité pour atteindre vos objectifs..</p>

            <div class="row my-5">
              <div class="col-6 col-md-6">
                <div class="feature">
                  <div class="icon">
                    <img src="../images/truck.svg" alt="Image" class="imf-fluid" />
                  </div>
                  <h3>Rapide &amp; Livraison gratuite</h3>
                  <p>
                    Vivez avec de la simplicité pour atteindre vos objectifs,
                    tout en gardant la motivation et une relation de respect.
                  </p>
                </div>
              </div>

              <div class="col-6 col-md-6">
                <div class="feature">
                  <div class="icon">
                    <img src="../images/bag.svg" alt="Image" class="imf-fluid" />
                  </div>
                  <h3>Facile à magasiner</h3>
                  <p>
                    Vivez avec des besoins simples d'amélioration positive de
                    votre communauté directe.
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
                  <h3>24/7 Support</h3>
                  <p>
                    Vivez avec de la simplicité pour atteindre vos objectifs.
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
                  <p>Adoptez la simplicité pour favoriser votre progression.</p>
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
            <h2 class="section-title">Produits Vedettes</h2>
          </div>
        </div>

        <div class="row mb-5">
          <div class="col-12 text-center">
            <p class="section-subtitle">Découvrez notre sélection de produits premium pour votre intérieur</p>
          </div>
        </div>

        <div class="simple-carousel-wrapper">
          <div class="simple-carousel-container">
            <div class="simple-carousel-track" id="simpleCarouselTrack">
              <!-- Produit 1 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="../images/banner2.jpg" alt="Chaise Design Moderne">
                  <div class="simple-product-badge">SmartTV</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Apple TV 4K</h3>
                  <div class="simple-product-price">1299.00 DH</div>
                </div>
              </div>

              <!-- Produit 2 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="../images/article2.jpg" alt="Canapé Luxe Confort">
                  <div class="simple-product-badge">Accessoire</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Casque JBL</h3>
                  <div class="simple-product-price">1200 DH</div>
                </div>
              </div>

              <!-- Produit 3 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="../images/item2.jpg" alt="Table à Manger Élégante">
                  <div class="simple-product-badge">Accessoire</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Apple watch</h3>
                  <div class="simple-product-price">3599.00 DH</div>
                </div>
              </div>

              <!-- Produit 4 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="../images/samsung S23.webp" alt="Lampe Design Premium" style="mix-blend-mode: multiply">
                  <div class="simple-product-badge">Smartphone</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Samsung S23</h3>
                  <div class="simple-product-price">10000 DH</div>
                </div>
              </div>

              <!-- Produit 5 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="../images/item4.jpg" alt="Bibliothèque Moderne">
                  <div class="simple-product-badge">Accessoire</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Apple watch Series 9</h3>
                  <div class="simple-product-price">8000 DH</div>
                </div>
              </div>

              <!-- Produit 6 -->
              <div class="simple-product-card">
                <div class="simple-product-image">
                  <img src="../images/chaise3.jpg" alt="Fauteuil Confort Plus" style="mix-blend-mode: multiply">
                  <div class="simple-product-badge">Chaise</div>
                </div>
                <div class="simple-product-info">
                  <h3 class="simple-product-name">Chaise Gamer</h3>
                  <div class="simple-product-price">4000 DH</div>
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
                            &ldquo;J'ai pu écouler toute ma collection de montres connectées en quelques semaines. La gestion des stocks est super intuitive.&rdquo;
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Testimonial Slider -->
@endsection
