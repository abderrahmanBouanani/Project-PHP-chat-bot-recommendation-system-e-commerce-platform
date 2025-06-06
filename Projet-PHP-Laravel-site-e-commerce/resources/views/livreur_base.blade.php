<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>ShopAll - Livraisons</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="{{ asset('assets/css/livreur.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css')}}" />

  </head>
  <body>
    @if(!session('user'))
    <div class="alert alert-warning mb-0 rounded-0 text-center" role="alert">
        <i class="bi bi-eye-fill me-2"></i> Vous êtes en mode consultation seule. <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour accéder à toutes les fonctionnalités.
    </div>
    @endif
    <div class="sidebar">
      <div class="sidebar-header px-3 py-4">
        <div class="livreur-interface-badge mb-2">LIVREUR INTERFACE</div>
        <a class="navbar-brand" href="{{ url('/livreur_livraison') }}">
          <span class="shopall-text">ShopAll</span><span class="dot">.</span>
        </a>
      </div>

      <nav class="side-bar-content">
        <a href="{{ url('/livreur_livraison') }}" class="nav-link {{ request()->is('livreur_livraison') ? 'active' : '' }} d-flex align-items-center">
          <i class="bi bi-truck me-3"></i>
          <span>Livraisons</span>
        </a>
        <a href="{{ url('/livreur_profile') }}" class="nav-link {{ request()->is('livreur_profile') ? 'active' : '' }} d-flex align-items-center">
          <i class="bi bi-person me-3"></i>
          <span>Profil</span>
        </a>
        <div class="logout-container">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn d-flex align-items-center w-100">
              <i class="bi bi-box-arrow-right me-3"></i>
              <span>Déconnexion</span>
            </button>
          </form>
        </div>
      </nav>
    </div>
    @yield('content')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/livraisons.js')}}" type="module"></script>
    <script src="{{ asset('assets/js/profil-livreur.js')}}" type="module"></script>
    <script src="{{ asset('assets/js/livreur/commandes.js')}}" type="module"></script>
  </body>
</html>

