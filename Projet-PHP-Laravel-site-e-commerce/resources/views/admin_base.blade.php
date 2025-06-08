<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page ?? 'ShopAll - Tableau de Bord' }}</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="{{ asset('assets/css/admindash.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/adminproduits.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/adminusers.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admincommandes.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-tables.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css')}}" />

    @yield('head')
  </head>
  <body>
    @if(!session('user'))
    <div class="alert alert-warning mb-0 rounded-0 text-center" role="alert">
        <i class="bi bi-eye-fill me-2"></i> Vous êtes en mode consultation seule. <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour accéder à toutes les fonctionnalités.
    </div>
    @endif
    <div class="sidebar">
      <div class="sidebar-header px-3 py-4">
        <div class="admin-interface-badge mb-2">ADMIN INTERFACE</div>
        <a class="navbar-brand" href="{{url('/admin_home')}}">
          <span class="shopall-text">ShopAll</span><span class="dot">.</span>
        </a>
      </div>

      <nav class="side-bar-content">
        <a href="{{ url('/admin_home') }}" class="nav-link {{ request()->is('admin_home') ? 'active' : '' }} d-flex align-items-center">
          <i class="bi bi-grid me-3"></i>
          <span>Tableau de Bord</span>
        </a>
        <a href="{{ url('/admin_utilisateur') }}" class="nav-link {{ request()->is('admin_utilisateur') ? 'active' : '' }} d-flex align-items-center">
          <i class="bi bi-people me-3"></i>
          <span>Utilisateurs</span>
        </a>
        <a href="{{ url('/admin_produit') }}" class="nav-link {{ request()->is('admin_produit') ? 'active' : '' }} d-flex align-items-center">
          <i class="bi bi-box-seam me-3"></i>
          <span>Produits</span>
        </a>
        <a href="{{ url('/admin_commande') }}" class="nav-link {{ request()->is('admin_commande') ? 'active' : '' }} d-flex align-items-center">
          <i class="bi bi-cart me-3"></i>
          <span>Commandes</span>
        </a>
        <a href="{{ url('/admin_about') }}" class="nav-link {{ request()->is('admin_about') ? 'active' : '' }} d-flex align-items-center">
          <i class="bi bi-person me-3"></i>
          <span>Profil</span>
        </a>
        <div class="logout-container mt-auto">
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

  </body>
</html>
