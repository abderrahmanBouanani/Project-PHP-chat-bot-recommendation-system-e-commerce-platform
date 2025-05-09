<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />
    <link href="{{ asset('assets/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset ('assets/css/style.css')}}" rel="stylesheet" />
    <title>ShopAll - Connexion</title>
    <style>
      :root {
        --primary-color: #3b5d50;
        --secondary-color: #f9bf29;
        --text-color: #2f2f2f;
        --background-color: #ffffff;
      }

      body {
        font-family: "Inter", sans-serif;
        background-color: #f8f9fa;
        color: var(--text-color);
      }

      .custom-navbar {
        margin-bottom: 30px;
        background: #3b5d50 !important;
        padding: 20px 0;
      }

      .custom-navbar .navbar-brand {
        font-size: 32px;
        font-weight: 600;
      }

      .custom-navbar .navbar-brand > span {
        opacity: 0.4;
      }

      .custom-navbar .navbar-toggler {
        border-color: transparent;
      }

      .custom-navbar .navbar-toggler:active,
      .custom-navbar .navbar-toggler:focus {
        box-shadow: none;
        outline: none;
      }

      @media (min-width: 992px) {
        .custom-navbar .custom-navbar-nav li {
          margin-left: 15px;
          margin-right: 15px;
        }
      }

      .custom-navbar .custom-navbar-nav li a {
        font-weight: 500;
        color: #ffffff !important;
        opacity: 0.5;
        transition: 0.3s all ease;
        position: relative;
      }

      @media (min-width: 768px) {
        .custom-navbar .custom-navbar-nav li a:before {
          content: "";
          position: absolute;
          bottom: 0;
          left: 8px;
          right: 8px;
          background: #f9bf29;
          height: 5px;
          opacity: 1;
          visibility: visible;
          width: 0;
          transition: 0.15s all ease-out;
        }
      }

      .custom-navbar .custom-navbar-nav li a:hover {
        opacity: 1;
      }

      .custom-navbar .custom-navbar-nav li a:hover:before {
        width: calc(100% - 16px);
      }

      .custom-navbar .custom-navbar-nav li.active a {
        opacity: 1;
      }

      .custom-navbar .custom-navbar-nav li.active a:before {
        width: calc(100% - 16px);
      }

      /* Style du formulaire de connexion */
      .login-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 40px;
        background-color: var(--background-color);
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      }

      .login-container h2 {
        color: var(--primary-color);
        margin-bottom: 30px;
        text-align: center;
        font-weight: 600;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-label {
        color: var(--primary-color);
        font-weight: 500;
      }

      .form-control {
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
      }

      .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(59, 93, 80, 0.25);
      }

      .btn-login {
        background-color: var(--primary-color);
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        width: 100%;
        font-weight: 500;
        transition: all 0.3s ease;
      }

      .btn-login:hover {
        background-color: #2c4a3e;
        transform: translateY(-1px);
      }

      .login-footer {
        text-align: center;
        margin-top: 20px;
      }

      .login-footer a {
        color: var(--primary-color);
        text-decoration: none;
      }

      .login-footer a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <!-- Start Header/Navigation -->
    <nav
      class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark"
      arial-label="ShopAll navigation bar"
    >
      <div class="container">
        <a class="navbar-brand" href="index.html">ShopAll<span>.</span></a>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarsShopAll"
          aria-controls="navbarsShopAll"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsShopAll">
          <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
            <li><a class="nav-link" href="about.html">À Propos</a></li>
            <li><a class="nav-link" href="services.html">Services</a></li>
            <li><a class="nav-link" href="blog.html">Blog</a></li>
            <li><a class="nav-link" href="contact.html">Contactez-nous</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Header/Navigation -->

    <div class="container">
      <div class="login-container">
        <h2>Connexion</h2>
        <form id="loginForm" method="POST" action="{{ url('/login') }}">
          @csrf
          <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input
              type="email"
              class="form-control"
              id="email"
              name="email"
              placeholder="Entrez votre email"
              required
            />
          </div>
          <div class="form-group">
            <label for="motdepasse" class="form-label">Mot de passe</label>
            <input
              type="password"
              class="form-control"
              id="motdepasse"
              name="motdepasse"
              placeholder="Entrez votre mot de passe"
              required
            />
          </div>
          <button type="submit" class="btn-login">Se connecter</button>
        </form>
        
        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
          </div>
        @endif



        <div class="login-footer">
          <p>
            Vous n'avez pas de compte ?
            <a href="{{ url('/signup') }}">Créer un compte</a>
          </p>
        </div>
      </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
  </body>
</html>
