<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />
    <link href="{{ asset('assets/css/tiny-slider.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/shop.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css')}}" />
    <title>{{ $page ?? 'ShopAll - Home' }}</title>
  </head>

  <body>
    @if(!session('user'))
    <div class="alert alert-warning mb-0 rounded-0 text-center" role="alert">
        <i class="bi bi-eye-fill me-2"></i> Vous êtes en mode consultation seule. <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour accéder à toutes les fonctionnalités.
    </div>
    @endif
    <!-- Start Header/Navigation -->
    <nav
      class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark"
      arial-label="Furni navigation bar"
    >
      <div class="container">
        <a class="navbar-brand" href="{{url('/vendeur_home')}}">ShopAll<span>.</span></a>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarsFurni"
          aria-controls="navbarsFurni"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
    <li class="nav-item {{ request()->is('vendeur_home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/vendeur_home') }}">Accueil</a>
    </li>
    <li class="nav-item {{ request()->is('vendeur_shop') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/vendeur_shop') }}">Ma Boutique</a>
    </li>
    <li class="nav-item {{ request()->is('vendeur_about') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/vendeur_about') }}">À propos</a>
    </li>
    <li class="nav-item {{ request()->is('vendeur_service') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/vendeur_service') }}">Services</a>
    </li>
    <li class="nav-item {{ request()->is('vendeur_contact') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/vendeur_contact') }}">Contact</a>
    </li>
</ul>


          <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
            <li>
              <a class="nav-link" href="{{url('/vendeur_profile')}}"
                ><img src="{{ asset('images/user.svg') }}"
              /></a>
            </li>
            <li>
              <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent p-0" style="cursor: pointer;">
                  <img
                    src="{{ asset('images/logout2.png') }}"
                    style="height: 30px; width: 30px; margin-left: 15px ; margin-top: 7px"
                    alt="Déconnexion"
                  />
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Header/Navigation -->
    @yield('content')


   <!-- Start Footer Section -->
   <footer class="footer-section">
      <div class="container relative">
        <div class="sofa-img">
          <img src="{{ asset('images/sofa.png') }}" alt="Image" class="img-fluid" />
        </div>

        <div class="row">
          <div class="col-lg-8">
            <div class="subscription-form">
              <h3 class="d-flex align-items-center">
                <span class="me-1"
                  ><img
                    src="{{ asset('images/envelope-outline.svg') }}"
                    alt="Image"
                    class="img-fluid"
                /></span>
                <span>Abonnez-vous à la Newsletter</span>
              </h3>

              <form action="#" class="row g-3">
                <div class="col-auto">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Entrez votre nom"
                  />
                </div>
                <div class="col-auto">
                  <input
                    type="email"
                    class="form-control"
                    placeholder="Entrez votre email"
                  />
                </div>
                <div class="col-auto">
                  <button class="btn btn-primary">
                    <span class="fa fa-paper-plane"></span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="row g-5 mb-5">
          <div class="col-lg-4">
            <div class="mb-4 footer-logo-wrap">
              <a href="#" class="footer-logo">ShopAll<span>.</span></a>
            </div>
            <p class="mb-4">
            ShopAll est une plateforme e-commerce dédiée à la vente de produits électroniques tels que des smartphones, ordinateurs, tablettes et accessoires. Elle offre une expérience d’achat simple, rapide et sécurisée, avec un large choix de produits de qualité à des prix compétitifs.
            </p>

            <ul class="list-unstyled custom-social">
              <li>
                <a href="#"><span class="fa fa-brands fa-facebook-f"></span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-brands fa-twitter"></span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-brands fa-instagram"></span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-brands fa-linkedin"></span></a>
              </li>
            </ul>
          </div>

          <div class="col-lg-8">
            <div class="row links-wrap">
              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                  <li><a href="{{ url('/vendeur_about') }}">À propos de nous</a></li>
                  
                  
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                <li><a href="{{ url('/vendeur_service') }}#service">Services</a></li>
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                  <li><a href="{{ url('/vendeur_about') }}#equipe">Notre équipe</a></li>
                  
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                <li><a href="{{ url('/vendeur_contact') }}#contact">Contactez-nous</a></li>
                </ul>
              </div>


              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                <li><a href="{{ url('/vendeur_home') }}#blog">Blog</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="border-top copyright">
          <div class="row pt-4">
            <div class="col-lg-6">
              <p class="mb-2 text-center text-lg-start">
                Copyright &copy;
                <script>
                  document.write(new Date().getFullYear());
                </script>
                . Tous droits réservés. &mdash; Conçu avec
                <span class="text-danger">❤</span>
              </p>
            </div>

            <div class="col-lg-6 text-center text-lg-end">
              <ul class="list-unstyled d-inline-flex ms-auto">
                <li class="me-4">
                  <a href="#">Termes &amp; Conditions</a>
                </li>
                <li><a href="#">Politique de confidentialité</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- End Footer Section -->

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('assets/js/contact.js')}}"></script>
    @if(request()->is('vendeur_home'))
    <script src="{{ asset('assets/js/carousel.js') }}"></script>
    @endif
  </body>
</html>
