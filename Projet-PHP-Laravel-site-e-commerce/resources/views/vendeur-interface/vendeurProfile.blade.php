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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Informations utilisateur</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        <p><strong>Nom :</strong> <span id="user-nom">{{ $user['nom'] }}</span></p>
        <p><strong>Prénom :</strong> <span id="user-prenom">{{ $user['prenom'] }}</span></p>
        <p><strong>Email :</strong> <span id="user-email">{{ $user['email'] }}</span></p>
        <p><strong>Téléphone :</strong> <span id="user-telephone">{{ $user['telephone'] }}</span></p>
        <p><strong>Type d'utilisateur :</strong> {{ $user['type'] }}</p>
        
        <div class="text-center mt-3">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-edit me-2"></i>Modifier mes informations
          </button>
        </div>
      @else
        <p class="text-center">Aucune information disponible. Veuillez vous connecter d'abord.</p>
        <div class="text-center">
          <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
          </a>
        </div>
      @endif
    </div>
    
    <!-- Modal d'édition du profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Modifier mes informations</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editProfileForm" method="POST" action="{{ route('vendeur.profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-body">
              <div id="formErrors" class="alert alert-danger d-none"></div>
              
              <div class="mb-3">
                <label for="edit-nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="edit-nom" name="nom" value="{{ $user['nom'] ?? '' }}" required>
              </div>
              
              <div class="mb-3">
                <label for="edit-prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="edit-prenom" name="prenom" value="{{ $user['prenom'] ?? '' }}" required>
              </div>
              
              <div class="mb-3">
                <label for="edit-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit-email" name="email" value="{{ $user['email'] ?? '' }}" required>
              </div>
              
              <div class="mb-3">
                <label for="edit-telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="edit-telephone" name="telephone" value="{{ $user['telephone'] ?? '' }}" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i> Annuler
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Gérer la soumission du formulaire
  $('#editProfileForm').on('submit', function(e) {
    e.preventDefault();
    
    // Cacher les erreurs précédentes
    $('#formErrors').addClass('d-none').html('');
    
    // Désactiver le bouton de soumission
    const submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...');
    
    // Récupérer les données du formulaire
    const formData = new FormData(document.getElementById('editProfileForm'));
    const formValues = Object.fromEntries(formData.entries());
    
    // Envoyer la requête AJAX
    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          // Mettre à jour les informations affichées
          $('#user-nom').text(formValues.nom);
          $('#user-prenom').text(formValues.prenom);
          $('#user-email').text(formValues.email);
          $('#user-telephone').text(formValues.telephone);
          
          // Fermer la modale
          $('#editProfileModal').modal('hide');
          
          // Afficher une notification de succès avec SweetAlert2
          Swal.fire({
            icon: 'success',
            title: 'Succès !',
            text: 'Vos informations ont été mises à jour avec succès.',
            confirmButtonColor: '#0d6efd',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
          });
        } else {
          // Afficher les erreurs de validation avec SweetAlert2
          let errorMessage = 'Une erreur est survenue lors de la mise à jour du profil.';
          
          if (response.errors) {
            errorMessage = '';
            $.each(response.errors, function(key, value) {
              errorMessage += value[0] + '\n';
            });
          } else if (response.message) {
            errorMessage = response.message;
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: errorMessage,
            confirmButtonColor: '#dc3545'
          });
        }
      },
      error: function(xhr) {
        let errorMessage = 'Une erreur est survenue lors de la communication avec le serveur.';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        
        Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: errorMessage,
          confirmButtonColor: '#dc3545'
        });
      },
      complete: function() {
        // Réactiver le bouton de soumission
        submitBtn.prop('disabled', false).html(originalBtnText);
      }
    });
  });
  
  // Réinitialiser le formulaire quand la modale est fermée
  $('#editProfileModal').on('hidden.bs.modal', function () {
    $('#formErrors').addClass('d-none').html('');
  });
});
</script>


  </body>
</html>
