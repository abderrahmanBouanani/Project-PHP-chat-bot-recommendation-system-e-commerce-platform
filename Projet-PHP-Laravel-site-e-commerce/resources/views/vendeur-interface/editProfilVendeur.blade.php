@extends('vendeur_base')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Modifier mes informations</div>
        <div class="card-body">
          <form method="POST" action="{{ url('/vendeur/profile/update') }}">
            @csrf
            <div class="mb-3">
              <label for="nom" class="form-label">Nom</label>
              <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $user->nom ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="prenom" class="form-label">Prénom</label>
              <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="telephone" class="form-label">Téléphone</label>
              <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone ?? '') }}" required>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ url('/vendeur_profile') }}" class="btn btn-secondary ms-2">Annuler</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
