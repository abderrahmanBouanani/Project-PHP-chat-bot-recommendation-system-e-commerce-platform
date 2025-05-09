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
    <link rel="stylesheet" href="{{ asset('assets/css/profileclient.css')}}" />
    <title>Informations utilisateur</title>
  </head>

  <body>
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
            <li><a class="nav-link" href="{{url('/vendeur_home')}}">Accueil</a></li>
            <li>
              <a class="nav-link" href="{{url('/vendeur_shop')}}">Ma Boutique</a>
            </li>
            <li><a class="nav-link" href="{{url('/vendeur_about')}}">À propos</a></li>
            <li><a class="nav-link" href="{{url('/vendeur_service')}}">Services</a></li>
            <li><a class="nav-link" href="{{url('/vendeur_contact')}}">Contact</a></li>
          </ul>

          <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
            <li>
              <a class="nav-link" href="{{url('/vendeur_profile')}}"
                ><img src="../images/user.svg"
              /></a>
            </li>
            <li>
              <a class="nav-link" href="{{url('/')}}"
                ><img
                  src="../images/logout2.png"
                  style="height: 30px; width: 30px; margin-left: 15px"
              /></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Header/Navigation -->

    <div class="main-content">
  <div class="container">
    <div class="user-info-card">
      <h2 class="text-center mb-4">Informations de votre compte</h2>
      @if ($user)
        <p><strong>Nom :</strong> {{ $user['nom'] }}</p>
        <p><strong>Prénom :</strong> {{ $user['prenom'] }}</p>
        <p><strong>Email :</strong> {{ $user['email'] }}</p>
        <p><strong>Téléphone :</strong> {{ $user['telephone'] }}</p>
        <p><strong>Type d'utilisateur :</strong> {{ $user['type'] }}</p>
      @else
        <p>Aucune information disponible. Veuillez vous connecter d'abord.</p>
      @endif
    </div>
  </div>
</div>


  </body>
</html>
