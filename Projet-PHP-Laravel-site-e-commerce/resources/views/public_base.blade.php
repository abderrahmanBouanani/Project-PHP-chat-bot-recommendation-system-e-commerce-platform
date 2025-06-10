<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="{{ asset('images/favicon.png')}}" />
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
    <title>{{ $page ?? 'ShopAll - Home' }}</title>
    <style>
        :root {
            --primary-color: #3b5d50;
            --secondary-color: #f9bf29;
            --text-color: #2f2f2f;
            --background-color: #ffffff;
        }

        .custom-navbar {
            background: #3b5d50 !important;
            padding: 20px 0 0 0;
            border-bottom: none;
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
    </style>
    @yield('styles')
</head>
<body>
    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="ShopAll navigation bar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">ShopAll<span>.</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsShopAll"
                aria-controls="navbarsShopAll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsShopAll">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="{{ request()->is('public/about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/public/about') }}">À Propos</a>
                    </li>
                    <li class="{{ request()->is('public/services') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/public/services') }}">Services</a>
                    </li>
                    <li class="{{ request()->is('public/contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/public/contact') }}">Contactez-nous</a>
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
                            <span class="me-1"><img src="{{ asset('images/envelope-outline.svg') }}" alt="Image" class="img-fluid" /></span>
                            <span>Abonnez-vous à la Newsletter</span>
                        </h3>

                        <form action="#" class="row g-3">
                            <div class="col-auto">
                                <input type="text" class="form-control" placeholder="Entrez votre nom" />
                            </div>
                            <div class="col-auto">
                                <input type="email" class="form-control" placeholder="Entrez votre email" />
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
                        ShopAll est une plateforme e-commerce dédiée à la vente de produits électroniques tels que des smartphones, ordinateurs, tablettes et accessoires. Elle offre une expérience d'achat simple, rapide et sécurisée, avec un large choix de produits de qualité à des prix compétitifs.
                    </p>

                    <ul class="list-unstyled custom-social">
                        <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
                    </ul>
                </div>

                <div class="col-lg-8">
                    <div class="row links-wrap">
                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="{{ url('/public/about') }}">À propos de nous</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="{{ url('/public/services') }}">Services</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="{{ url('/public/contact') }}">Contactez-nous</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-top copyright">
                <div class="row pt-4">
                    <div class="col-lg-6">
                        <p class="mb-2 text-center text-lg-start">Copyright &copy; 2025. Tous droits réservés. &mdash; Conçu avec ❤</p>
                    </div>
                    <div class="col-lg-6 text-center text-lg-end">
                        <ul class="list-unstyled d-inline-flex ms-auto">
                            <li class="me-4"><a href="#">Termes & Conditions</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer Section -->

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html> 