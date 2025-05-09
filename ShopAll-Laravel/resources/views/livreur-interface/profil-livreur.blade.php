@extends('livreur_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<div class="main-content">
      <div class="container">
        <div class="user-info-card">
          <h2 class="text-center mb-4">Informations de votre compte</h2>
          @if(session('user'))
            <div id="userInfoDisplay">
              <div class="row">
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
              </div>
            </div>

            <form id="editProfileForm" style="display: none;" class="mt-4">
              @csrf
              <div class="row">
                <div class="col-md-6 mb-3">
                  <div class="form-group">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="{{ session('user')['nom'] }}" required>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-group">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="{{ session('user')['prenom'] }}" required>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ session('user')['email'] }}" required>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-group">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ session('user')['telephone'] }}" required>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-group">
                    <label for="motdepasse" class="form-label">Nouveau mot de passe (optionnel)</label>
                    <input type="password" class="form-control" id="motdepasse" name="motdepasse">
                  </div>
                </div>
              </div>
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <button type="button" class="btn btn-secondary" id="cancelEditBtn">Annuler</button>
              </div>
            </form>

            <div class="text-center mt-4">
              <button id="editProfileBtn" class="btn btn-edit">
                <i class="fas fa-edit"></i> Modifier le profil
              </button>
            </div>
          @else
            <p class="text-center">Aucune information disponible. Veuillez vous connecter d'abord.</p>
          @endif
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

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-edit {
        background-color: #2f4f4f;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-edit:hover {
        background-color: #1a2f2f;
        color: #ffed87;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-edit i {
        margin-right: 8px;
    }

    .btn-primary {
        background-color: #2f4f4f;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
        background-color: #1a2f2f;
        color: #ffed87;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        color: #ffed87;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editProfileBtn = document.getElementById('editProfileBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editProfileForm = document.getElementById('editProfileForm');
    const userInfoDisplay = document.getElementById('userInfoDisplay');

    editProfileBtn.addEventListener('click', function() {
        editProfileForm.style.display = 'block';
        userInfoDisplay.style.display = 'none';
        editProfileBtn.style.display = 'none';
    });

    cancelEditBtn.addEventListener('click', function() {
        editProfileForm.style.display = 'none';
        userInfoDisplay.style.display = 'block';
        editProfileBtn.style.display = 'block';
    });

    editProfileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/livreur/update-profile', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Profil mis à jour avec succès');
                window.location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de la mise à jour du profil');
        });
    });
});
</script>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/profil-livreur.js') }}"></script>
@endsection




