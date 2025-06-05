@extends('admin_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
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
                      <form action="{{ url('/admin/utilisateur/' . $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                      </form>
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
      document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("searchButton").addEventListener("click", filterUsers);
        document.getElementById("typeFilter").addEventListener("change", filterUsers);
      });

      function filterUsers() {
        const searchTerm = document.getElementById("searchInput").value;
        const typeFilter = document.getElementById("typeFilter").value;

        // Appel à l'API de recherche
        fetch(`/api/admin/users/search?search=${searchTerm}&type=${typeFilter}`)
          .then(response => response.json())
          .then(data => {
            displayUsers(data);
          })
          .catch(error => {
            console.error('Error searching users:', error);
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
                <form action="/admin/utilisateur/${user.id}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?');">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                </form>
              </td>
            </tr>
          `;
          clientTable.insertAdjacentHTML("beforeend", row);
        });
      }
    </script>
@endsection <!-- Ici finit le contenu spécifique à cette page -->