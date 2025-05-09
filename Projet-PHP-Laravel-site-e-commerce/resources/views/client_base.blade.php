<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link href="{{ asset('assets/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset ('assets/css/style.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/shop.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/shopspe.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/profileclient.css')}}" />
    <title>{{ $page ?? 'ShopAll - Home' }}</title>
  </head>

  <body>
    <!-- Start Header/Navigation -->
    <nav
      class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark"
      arial-label="Furni navigation bar"
    >
      <div class="container">
        <a class="navbar-brand" href="index.html">ShopAll<span>.</span></a>

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
    <li class="nav-item {{ request()->is('client_home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_home') }}">Accueil</a>
    </li>
    <li class="nav-item {{ request()->is('client_shop') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_shop') }}">Boutique</a>
    </li>
    <li class="nav-item {{ request()->is('client_about') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_about') }}">À propos</a>
    </li>
    <li class="nav-item {{ request()->is('client_service') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_service') }}">Services</a>
    </li>
    <li class="nav-item {{ request()->is('client_contact') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_contact') }}">Contact</a>
    </li>
</ul>



          <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
            <li>
              <a class="nav-link" href="{{url('/client_profile')}}"
                ><img src="{{ asset('images/user.svg') }}"
              /></a>
            </li>
            <li>
              <a class="nav-link" href="{{url('/client_cart')}}"
                ><img src="{{ asset('images/cart.svg') }}"
              /></a>
            </li>
            <li>
              <a class="nav-link" href="{{url('/')}}"
                ><img
                  src="{{ asset('images/logout2.png') }}"
                  style="height: 30px; width: 30px; margin-left: 15px"
              /></a>
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
              Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis
              nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
              velit imperdiet dolor tempor tristique. Pellentesque habitant
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
                  <li><a href="#">À propos de nous</a></li>
                  <li><a href="#">Services</a></li>
                  <li><a href="#">Blog</a></li>
                  <li><a href="#">Contactez-nous</a></li>
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                  <li><a href="#">Support</a></li>
                  <li><a href="#">Base de connaissances</a></li>
                  <li><a href="#">Chat en direct</a></li>
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                  <li><a href="#">Emplois</a></li>
                  <li><a href="#">Notre équipe</a></li>
                  <li><a href="#">Leadership</a></li>
                  <li><a href="#">Politique de confidentialité</a></li>
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                  <li><a href="#">Nordic Chair</a></li>
                  <li><a href="#">Kruzo Aero</a></li>
                  <li><a href="#">Ergonomic Chair</a></li>
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
                <span class="text-danger">❤</span> par
                <a href="https://untree.co">Untree.co</a>
                <!-- License information: https://untree.co/license/ -->
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

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    
    <!-- Page-specific scripts -->
    @if(request()->is('client_shop'))
    <script src="{{ asset('assets/js/boutique.js') }}"></script>
    @endif
    
    @if(request()->is('client_cart'))
    <script src="{{ asset('assets/js/cart.js') }}"></script>
    @endif
    
    @if(request()->is('client/checkout'))
    <script src="{{ asset('assets/js/checkout.js') }}"></script>
    @endif
    
    @if(request()->is('client_contact'))
    <script src="{{ asset('assets/js/contact.js') }}"></script>
    @endif
  </body>
</html>
