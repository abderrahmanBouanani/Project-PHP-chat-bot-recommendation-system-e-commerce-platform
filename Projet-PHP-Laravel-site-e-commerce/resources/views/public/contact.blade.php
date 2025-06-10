@extends('public_base')

@section('styles')
<style>
    :root {
        --primary-color: #3b5d50;
        --secondary-color: #f9bf29;
        --text-color: #2f2f2f;
        --background-color: #ffffff;
    }
    .laptop {
        position: relative;
        width: 90%;
        top: -150px;
        display: block;
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
                        <h1>Contact</h1>
                        <p class="mb-4">Nous sommes là pour vous aider ! N'hésitez pas à nous contacter pour toute question ou demande concernant nos produits et services.</p>
                        <p>
                            <a href="{{ url('/') }}" class="btn btn-secondary me-2">Se connecter</a>
                            <a href="{{ url('/signup') }}" class="btn btn-white-outline">S'inscrire</a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="{{ asset('images/laptop.png') }}" class="img-fluid laptop" alt="Laptop">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de la section Héros -->

    <!-- Début du formulaire de contact -->
    <div class="untree_co-section" id="contact">
        <div class="container">
            <div class="block">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-8 pb-4">
                        <div class="row mb-5">
                            <div class="col-lg-4">
                                <div class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                                    <div class="service-icon color-1 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                        </svg>
                                    </div>
                                    <div class="service-contents">
                                        <p>123 Avenue Hassan II, 20000 Casablanca, Maroc</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                                    <div class="service-icon color-1 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                        </svg>
                                    </div>
                                    <div class="service-contents">
                                        <p>contact@shopall.com</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                                    <div class="service-icon color-1 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                        </svg>
                                    </div>
                                    <div class="service-contents">
                                        <p>+212 5 22 12 34 56</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form>
                            <div class="row" id="contact-form">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-black" for="fname">Prénom</label>
                                        <input type="text" class="form-control" id="fname">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-black" for="lname">Nom</label>
                                        <input type="text" class="form-control" id="lname">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-black" for="email">Adresse email</label>
                                <input type="email" class="form-control" id="email">
                            </div>

                            <div class="form-group mb-5">
                                <label class="text-black" for="message">Message</label>
                                <textarea name="" class="form-control" id="message" cols="30" rows="5"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary-hover-outline">Envoyer le message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin du formulaire de contact -->

    <!-- Début de la section Carte -->
    <div class="untree_co-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="map-wrap">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3397.1234567890123!2d-7.987654321!3d31.123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzHCsDA3JzI0LjUiTiA3wrDU5JzE1LjYiVw!5e0!3m2!1sfr!2sma!4v1234567890" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de la section Carte -->
@endsection 