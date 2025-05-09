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
    <title>Inscription - ShopAll</title>
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

      /* Utilisation du style navbar original */
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

      /* Style du formulaire */
      .signup-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 40px;
        background-color: var(--background-color);
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      }

      .signup-container h2 {
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

      .form-control,
      .form-select {
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
      }

      .form-control:focus,
      .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(59, 93, 80, 0.25);
      }

      .btn-signup {
        background-color: var(--primary-color);
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        width: 100%;
        font-weight: 500;
        transition: all 0.3s ease;
      }

      .btn-signup:hover {
        background-color: #2c4a3e;
        transform: translateY(-1px);
      }

      .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        text-align: center;
        z-index: 1000;
      }

      .popup h3 {
        color: var(--primary-color);
        margin-bottom: 20px;
      }

      .popup button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .popup button:hover {
        background-color: #2c4a3e;
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
        <a class="navbar-brand" href="signup.html">ShopAll<span>.</span></a>

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
            <li><a class="nav-link" href="about.html">A Propos</a></li>
            <li><a class="nav-link" href="services.html">Services</a></li>
            <li><a class="nav-link" href="blog.html">Blog</a></li>
            <li><a class="nav-link" href="contact.html">Contacter nous</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Header/Navigation -->

    <div class="container">
      <div class="signup-container">
        <h2>Créer un compte</h2>
        <form id="signupForm" method="POST" action="{{ url('/signup') }}">
        @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="nom">Nom</label>
                <input
                  type="text"
                  class="form-control"
                  id="nom"
                  name="nom"
                  placeholder="Entrez votre nom"
                  required
                />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="prenom">Prénom</label>
                <input
                  type="text"
                  class="form-control"
                  id="prenom"
                  name="prenom"
                  placeholder="Entrez votre prénom"
                  required
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email</label>
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
            <label class="form-label" for="motdepasse">Mot de passe</label>
            <input
              type="password"
              class="form-control"
              id="motdepasse"
              name="motdepasse"
              placeholder="Entrez votre mot de passe"
              required
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="telephone"
              >Numéro de téléphone</label
            >
            <input
              type="tel"
              class="form-control"
              id="telephone"
              name="telephone"
              placeholder="Entrez votre numéro de téléphone"
              pattern="^\+?\d{10,15}$"
              title="Le numéro de téléphone doit comporter entre 10 et 15 chiffres"
              required
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="type_utilisateur"
              >Type d'utilisateur</label
            >
            <select
              class="form-select"
              id="type_utilisateur"
              name="type_utilisateur"
              required
            >
              <option value="">Choisir le type d'utilisateur</option>
              <option value="client">Client</option>
              <option value="vendeur">Vendeur</option>
              <option value="livreur">Livreur</option>
            </select>
          </div>

          <button type="submit" class="btn-signup">S'inscrire</button>
        </form>
      </div>
    </div>

    <div id="popup" class="popup">
      <h3>Votre compte a bien été créé !</h3>
      <button id="redirectButton">OK</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
