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
    <link href="{{ asset('assets/css/chatbot.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/accessibilité.css')}}" />
    <title>{{ $page ?? 'ShopAll - Home' }}</title>

    <style>
  .position-relative {
    position: relative !important;
  }
  .position-absolute {
    position: absolute !important;
  }
  .top-0 {
    top: 0 !important;
  }
  .start-100 {
    left: 100% !important;
  }
  .translate-middle {
    transform: translate(-50%, -50%) !important;
  }
  .badge {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
  }
  .badge.bg-danger {
    background-color: #dc3545 !important;
    color: white;
  }
  .rounded-pill {
    border-radius: 50rem !important;
  }

  
</style>
  </head>

  <body>
    <!-- Bouton d'accessibilité -->
    <button id="accessibilityReaderBtn" class="accessibility-reader-btn" title="Activer/Désactiver la lecture vocale">
        <i class="fas fa-volume-up"></i>
    </button>
    
    <!-- Indicateur de lecture -->
    <div id="readingIndicator" class="reading-indicator"></div>
    
    <!-- Tooltip -->
    <div id="tooltip" class="tooltip"></div>
    
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
        <a class="nav-link" href="{{ url('/client_home') }}" data-text="Page d'accueil du site">Accueil</a>
    </li>
    <li class="nav-item {{ request()->is('client_shop') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_shop') }}" data-text="Boutique - Voir tous nos produits">Boutique</a>
    </li>
    <li class="nav-item {{ request()->is('client_about') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_about') }}" data-text="À propos - En savoir plus sur nous">À propos</a>
    </li>
    <li class="nav-item {{ request()->is('client_service') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_service') }}" data-text="Services - Nos services disponibles">Services</a>
    </li>
    <li class="nav-item {{ request()->is('client_contact') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/client_contact') }}" data-text="Contact - Nous contacter">Contact</a>
    </li>
    <li class="nav-item {{ request()->is('client/commandes*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('client.commandes') }}" data-text="Mes commandes - Voir l'historique de vos commandes">Mes Commandes</a>
    </li>
</ul>

          <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
            <li>
              <a class="nav-link" href="{{url('/client_profile')}}" data-text="Profil utilisateur"
                ><img src="{{ asset('images/user.svg') }}"
              /></a>
            </li>
            <li>
  <a class="nav-link position-relative" href="{{url('/client_cart')}}" data-text="Panier d'achat">
    <img src="{{ asset('images/cart.svg') }}" />
    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; display: none;">
      0
    </span>
  </a>
</li>
            <li>
              <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent" style="cursor: pointer;" data-text="Se déconnecter">
                  <img
                    src="{{ asset('images/logout2.png') }}"
                    style="height: 30px; width: 30px; margin-left: 15px"
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
          <img src="{{ asset('images/laptop.png') }}" alt="Image" class="img-fluid" style="width: 800px; height: auto;" />
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
                    data-text="Champ pour entrer votre nom"
                  />
                </div>
                <div class="col-auto">
                  <input
                    type="email"
                    class="form-control"
                    placeholder="Entrez votre email"
                    data-text="Champ pour entrer votre adresse email"
                  />
                </div>
                <div class="col-auto">
                  <button class="btn btn-primary" data-text="S'abonner à la newsletter">
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
            ShopAll est une plateforme e-commerce dédiée à la vente de produits électroniques tels que des smartphones, ordinateurs, tablettes et accessoires. Elle offre une expérience d'achat simple, rapide et sécurisée, avec un large choix de produits de qualité à des prix compétitifs.
            </p>

            <ul class="list-unstyled custom-social">
              <li>
                <a href="#" data-text="Facebook"><span class="fa fa-brands fa-facebook-f"></span></a>
              </li>
              <li>
                <a href="#" data-text="Twitter"><span class="fa fa-brands fa-twitter"></span></a>
              </li>
              <li>
                <a href="#" data-text="Instagram"><span class="fa fa-brands fa-instagram"></span></a>
              </li>
              <li>
                <a href="#" data-text="LinkedIn"><span class="fa fa-brands fa-linkedin"></span></a>
              </li>
            </ul>
          </div>

          <div class="col-lg-8">
            <div class="row links-wrap">
              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                  <li><a href="{{ url('/client_about') }}" data-text="À propos de nous - En savoir plus sur notre entreprise">À propos de nous</a></li>
                  
                  
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                <li><a href="{{ url('/client_service') }}" data-text="Services - Découvrir nos services">Services</a></li>
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                  <li><a href="{{ url('/client_about') }}#equipe" data-text="Notre équipe - Découvrir les membres de notre équipe">Notre équipe</a></li>
                  
                </ul>
              </div>

              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                <li><a href="{{ url('/client_contact') }}#contact" data-text="Contactez-nous - Formulaire de contact">Contactez-nous</a></li>
                </ul>
              </div>


              <div class="col-6 col-sm-6 col-md-3">
                <ul class="list-unstyled">
                <li><a href="{{ url('/client_home') }}#blog" data-text="Blog - Lire nos derniers articles">Blog</a></li>
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
                  <a href="#" data-text="Termes et conditions d'utilisation">Termes &amp; Conditions</a>
                </li>
                <li><a href="#" data-text="Politique de confidentialité">Politique de confidentialité</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- End Footer Section -->

    <!-- Scripts -->
     <script>
      window.sessionId = {{ session('user.id', 0) }};
      console.log(window.sessionId);
     </script>
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
    @if(request()->is('client_home'))
    <script src="{{ asset('assets/js/carousel.js') }}"></script>
    @endif
    <script src="{{ asset('assets/js/chatbot.js') }}"></script>
    <script src="{{ asset('assets/js/cart-badge.js') }}"></script>
    
    <!-- Script d'accessibilité -->
    <script src="{{ asset('assets/js/accessibility-reader.js') }}"></script>
  </body>
</html>