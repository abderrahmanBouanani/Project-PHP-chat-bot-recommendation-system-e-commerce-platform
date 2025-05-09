@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('head')
    @if(Request::is('admin_about'))
    <link rel="stylesheet" href="{{ asset('assets/css/adminprofile.css')}}" />
    @endif
@endsection

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<div class="main-content">
      <div class="container">
        <div class="user-info-card">
          <h2 class="text-center mb-4">Informations de votre compte</h2>
          <div id="userInfo" class="row">
            @if(session('user'))
              <div class="col-md-6 mb-3">
                <div class="user-info-item">
                  <span class="user-info-label">Nom :</span>
                  <span class="user-info-value">{{ session('user')['nom'] }}</span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="user-info-item">
                  <span class="user-info-label">Prénom :</span>
                  <span class="user-info-value">{{ session('user')['prenom'] }}</span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="user-info-item">
                  <span class="user-info-label">Email :</span>
                  <span class="user-info-value">{{ session('user')['email'] }}</span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="user-info-item">
                  <span class="user-info-label">Téléphone :</span>
                  <span class="user-info-value">{{ session('user')['telephone'] }}</span>
                </div>
              </div>
              <div class="col-12">
                <div class="user-info-item">
                  <span class="user-info-label">Type d'utilisateur :</span>
                  <span class="user-info-value">{{ session('user')['type'] }}</span>
                </div>
              </div>
            @else
              <p class="text-center">Aucune information disponible. Veuillez vous connecter d'abord.</p>
            @endif
          </div>
        </div>
      </div>
</div>

<style>
    .user-info-card {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 40px;
    }

    .user-info-card h2 {
        font-size: 24px;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px;
    }

    .user-info-item {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .user-info-label {
        font-weight: 600;
        color: #495057;
        margin-right: 10px;
    }

    .user-info-value {
        color: #212529;
    }
</style>
@endsection
