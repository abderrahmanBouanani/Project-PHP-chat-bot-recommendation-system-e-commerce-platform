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
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="{{ asset('assets/css/admindash.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/adminproduits.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/adminusers.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admincommandes.css')}}" />

    @yield('head')
  </head>
  <body>
    <div class="sidebar">
      <nav class="custom-navbar">
        <a class="navbar-brand" href="{{url('/admin_home')}}">ShopAll<span>.</span></a>
      </nav>

      <nav class="side-bar-content">
    <a href="{{ url('/admin_home') }}" class="nav-link {{ request()->is('admin_home') ? 'active' : '' }} d-flex align-items-center">
        <i class="bi bi-grid me-2"></i>
        Tableau de Bord
    </a>
    <a href="{{ url('/admin_utilisateur') }}" class="nav-link {{ request()->is('admin_utilisateur') ? 'active' : '' }} d-flex align-items-center">
        <i class="bi bi-person-lines-fill me-2"></i>
        Utilisateur
    </a>
    <a href="{{ url('/admin_produit') }}" class="nav-link {{ request()->is('admin_produit') ? 'active' : '' }} d-flex align-items-center">
        <i class="bi bi-box-seam me-2"></i>
        Produits
    </a>
    <a href="{{ url('/admin_commande') }}" class="nav-link {{ request()->is('admin_commande') ? 'active' : '' }} d-flex align-items-center">
        <i class="bi bi-cart me-2"></i>
        Commandes
    </a>
    <a href="{{ url('/admin_about') }}" class="nav-link {{ request()->is('admin_about') ? 'active' : '' }} d-flex align-items-center">
        <i class="bi bi-person me-2"></i>
        Profile
    </a>
    <a class="nav-link" href="{{ url('/') }}">
        <img src="../images/logout2.png" style="height: 30px; width: 30px; margin-left: 15px"/>
    </a>
</nav>

    </div>
    @yield('content')
    
  </body>
</html>
