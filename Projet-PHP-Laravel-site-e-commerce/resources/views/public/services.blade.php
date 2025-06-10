@extends('public_base')

@section('styles')
<style>
.chaise {
    position: relative;
    width: 80%;
    top: -120px !important;
}
.hero-img-wrap {
    display: flex;
    align-items: flex-start;
}
</style>
@endsection

@section('content')
    <!-- Début de la section Héros -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Nos Services</h1>
                        <p class="mb-4">
                            Découvrez notre gamme complète de services conçus pour vous offrir la meilleure expérience d'achat en ligne.
                        </p>
                        <p>
                            <a href="{{ url('/') }}" class="btn btn-secondary me-2">Se connecter</a>
                            <a href="{{ url('/signup') }}" class="btn btn-white-outline">S'inscrire</a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="https://assets.corsair.com/image/upload/c_pad,q_85,h_1100,w_1100,f_auto/products/Gaming-Chairs/tc500-luxe/Gallery/SHERWOOD/CF-9010068-WW_01.webp" class="img-fluid chaise" alt="Gaming Chair" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de la section Héros -->

    <!-- Début de la section Services -->
    <div class="untree_co-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="section-title">Nos Services</h2>
                </div>
            </div>

            <div class="row">
                <!-- Service 1 -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="service text-center">
                        <span class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <h3>Vente en ligne</h3>
                        <p>Large sélection de produits électroniques de qualité à des prix compétitifs.</p>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="service text-center">
                        <span class="icon">
                            <i class="fas fa-truck"></i>
                        </span>
                        <h3>Livraison rapide</h3>
                        <p>Service de livraison rapide et fiable dans tout le Maroc.</p>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="service text-center">
                        <span class="icon">
                            <i class="fas fa-headset"></i>
                        </span>
                        <h3>Support client</h3>
                        <p>Une équipe de support client disponible pour répondre à toutes vos questions.</p>
                    </div>
                </div>

                <!-- Service 4 -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="service text-center">
                        <span class="icon">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <h3>Garantie produits</h3>
                        <p>Tous nos produits sont garantis pour votre tranquillité d'esprit.</p>
                    </div>
                </div>

                <!-- Service 5 -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="service text-center">
                        <span class="icon">
                            <i class="fas fa-undo"></i>
                        </span>
                        <h3>Retours faciles</h3>
                        <p>Politique de retour simple et transparente.</p>
                    </div>
                </div>

                <!-- Service 6 -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="service text-center">
                        <span class="icon">
                            <i class="fas fa-gift"></i>
                        </span>
                        <h3>Offres spéciales</h3>
                        <p>Promotions régulières et offres spéciales sur une large gamme de produits.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de la section Services -->

    <!-- Début de la section Pourquoi nous choisir -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="section-title">Pourquoi nous choisir ?</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="feature">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <h3>Qualité garantie</h3>
                        <p>Tous nos produits sont soigneusement sélectionnés pour garantir la meilleure qualité.</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="feature">
                        <span class="icon">
                            <i class="fas fa-clock"></i>
                        </span>
                        <h3>Service rapide</h3>
                        <p>Livraison rapide et service client réactif pour une expérience d'achat optimale.</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="feature">
                        <span class="icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <h3>Paiement sécurisé</h3>
                        <p>Transactions sécurisées pour une protection maximale de vos données.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de la section Pourquoi nous choisir -->
@endsection 