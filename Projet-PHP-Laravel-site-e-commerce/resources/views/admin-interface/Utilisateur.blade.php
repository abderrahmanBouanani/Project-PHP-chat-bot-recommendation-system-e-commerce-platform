@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    min-width: 300px;
    max-width: 500px;
  }

  .delete-btn {
    cursor: pointer;
    font-size: 1.2rem;
    padding: 8px;
    transition: all 0.3s ease;
  }

  .delete-btn:hover {
    transform: scale(1.1);
  }

  .delete-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
</style>

<div class="main-content">
  <h1 class="h3 mb-4" id="pageTitle">{{ $cardTitle ?? 'Liste des Utilisateurs' }}</h1>

  <!-- Barre de recherche et filtres -->
  <div class="search-filter-container mb-4">
    <div class="row g-3">
      <div class="col-md-4">
        <input
          type="text"
          class="form-control"
          id="searchInput"
          placeholder="Rechercher par nom, email ou téléphone"
        />
      </div>
      <div class="col-md-3">
        <select class="form-select" id="typeFilter">
          <option value="all">Tous les types</option>
          <option value="client">Client</option>
          <option value="livreur">Livreur</option>
          <option value="vendeur">Vendeur</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-primary w-100" id="searchButton">
          Rechercher
        </button>
      </div>
    </div>
  </div>

  <div class="card">
    <div
      class="card-header d-flex justify-content-between align-items-center"
    >
      <h5 class="mb-0" id="cardTitle">{{ $cardTitle ?? 'Utilisateurs' }}</h5>
      <span id="clientCount" class="badge bg-primary">Total : {{ count($users) }}</span>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th><i class="bi bi-person-vcard me-1"></i>Nom</th>
              <th><i class="bi bi-envelope me-1"></i>Email</th>
              <th><i class="bi bi-telephone me-1"></i>Téléphone</th>
              <th><i class="bi bi-person-badge me-1"></i>Type</th>
              <th><i class="bi bi-gear me-1"></i>Actions</th>
            </tr>
          </thead>
          <tbody id="clientTable">
            @if(count($users) > 0)
              @foreach($users as $user)
              <tr>
                <td>{{ $user->nom }} {{ $user->prenom }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->telephone }}</td>
                <td>{{ $user->type }}</td>
                <td>
                  <i class="fas fa-trash-alt text-danger delete-btn" 
                     onclick="deleteUser({{ $user->id }})"
                     title="Supprimer"></i>
                </td>
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="5" class="text-center">Aucun utilisateur trouvé</td>
              </tr>
            @endif
          </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Fonction pour afficher les notifications
  function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.insertBefore(notification, document.body.firstChild);
    setTimeout(() => notification.remove(), 5000);
  }

  // Fonction pour supprimer un utilisateur
  function deleteUser(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
      // Désactiver le bouton pendant la suppression
      const deleteButton = document.querySelector(`[onclick="deleteUser(${id})"]`);
      if (deleteButton) {
        deleteButton.style.pointerEvents = 'none';
        deleteButton.style.opacity = '0.5';
      }

      // Vérifier si le token CSRF est disponible
      const token = document.querySelector('meta[name="csrf-token"]');
      if (!token) {
        console.error('Token CSRF non trouvé');
        showNotification('Erreur de sécurité: Token CSRF manquant', 'danger');
        return;
      }

      fetch(`/admin/utilisateur/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': token.content,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
      })
      .then(response => {
        if (!response.ok) {
          return response.json().then(data => {
            throw new Error(data.message || `Erreur HTTP: ${response.status}`);
          });
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          // Recharger la liste des utilisateurs
          filterUsers();
          // Afficher un message de succès
          showNotification('Utilisateur supprimé avec succès', 'success');
        } else {
          throw new Error(data.message || 'Erreur lors de la suppression de l\'utilisateur');
        }
      })
      .catch(error => {
        console.error('Erreur lors de la suppression:', error);
        showNotification(error.message, 'danger');
      })
      .finally(() => {
        // Réactiver le bouton après la suppression
        if (deleteButton) {
          deleteButton.style.pointerEvents = 'auto';
          deleteButton.style.opacity = '1';
        }
      });
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("searchButton").addEventListener("click", filterUsers);
    document.getElementById("typeFilter").addEventListener("change", filterUsers);
  });

  function filterUsers() {
    const searchTerm = document.getElementById("searchInput").value;
    const typeFilter = document.getElementById("typeFilter").value;

    fetch(`/api/admin/users/search?search=${searchTerm}&type=${typeFilter}`)
      .then(response => response.json())
      .then(data => {
        displayUsers(data.data);
      })
      .catch(error => {
        console.error('Error searching users:', error);
        showNotification('Erreur lors de la recherche des utilisateurs', 'danger');
      });
  }

  function displayUsers(users) {
    const clientTable = document.getElementById("clientTable");
    const clientCount = document.getElementById("clientCount");

    clientTable.innerHTML = "";
    clientCount.textContent = `Total : ${users.length}`;

    if (users.length === 0) {
      clientTable.innerHTML = `<tr><td colspan="5" class="text-center">Aucun utilisateur trouvé</td></tr>`;
      return;
    }

    users.forEach(user => {
      const row = `
        <tr>
          <td>${user.nom} ${user.prenom}</td>
          <td>${user.email}</td>
          <td>${user.telephone}</td>
          <td>${user.type}</td>
          <td>
            <i class="fas fa-trash-alt text-danger delete-btn" 
               onclick="deleteUser(${user.id})"
               title="Supprimer"></i>
          </td>
        </tr>
      `;
      clientTable.insertAdjacentHTML("beforeend", row);
    });
  }
</script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->