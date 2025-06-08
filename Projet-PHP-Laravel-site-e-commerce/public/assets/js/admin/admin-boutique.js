/**
 * admin-boutique.js - Script pour la gestion des produits dans l'interface admin
 */
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

// Fonction pour charger les produits
function loadProducts(page = 1) {
  const searchInput = document.getElementById('searchInput');
  const categoryFilter = document.getElementById('categoryFilter');
  const sortSelect = document.getElementById('sortSelect');
  const productsList = document.getElementById('productsList');
  const paginationContainer = document.querySelector('.pagination-container');

  const searchTerm = searchInput.value;
  const category = categoryFilter.value;
  const sort = sortSelect.value;

  fetch(`/admin/produits/search?page=${page}&search=${searchTerm}&category=${category}&sort=${sort}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayProducts(data.data, {
          current_page: data.current_page,
          last_page: data.last_page
        });
      } else {
        showNotification('Erreur lors du chargement des produits: ' + data.message, 'danger');
      }
    })
    .catch(error => {
      console.error('Erreur lors du chargement des produits:', error);
      showNotification('Une erreur est survenue lors du chargement des produits', 'danger');
    });
}

// Fonction pour afficher les produits
function displayProducts(products, pagination) {
  const productsList = document.getElementById('productsList');
  const paginationContainer = document.querySelector('.pagination-container');

  if (!products || products.length === 0) {
    productsList.innerHTML = `
      <tr>
        <td colspan="6" class="text-center">
          <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle"></i> Aucun produit trouvé pour les critères de recherche spécifiés.
          </div>
        </td>
      </tr>
    `;
    paginationContainer.innerHTML = '';
    return;
  }

  productsList.innerHTML = products.map(product => `
    <tr>
      <td>
        <img src="${product.image ? `/storage/${product.image}` : '/assets/images/no-image.png'}" 
             alt="${product.nom}" 
             class="product-image"
             style="width: 50px; height: 50px; object-fit: cover;">
      </td>
      <td>${product.nom}</td>
      <td>${product.categorie}</td>
      <td>${parseFloat(product.prix_unitaire).toFixed(2)} DH</td>
      <td>${product.vendeur ? product.vendeur.nom : 'Non spécifié'}</td>
      <td>
        <div class="btn-group" role="group">
          <i class="fas fa-trash-alt text-danger delete-btn" 
             style="cursor: pointer; font-size: 1.2rem; padding: 8px;"
             onclick="deleteProduct(${product.id})"
             title="Supprimer"></i>
        </div>
      </td>
    </tr>
  `).join('');

  // Mettre à jour la pagination
  if (pagination) {
    updatePagination(pagination);
  }
}

// Fonction pour mettre à jour la pagination
function updatePagination(pagination) {
  const paginationContainer = document.querySelector('.pagination-container');
  if (!pagination) return;
  
  const { current_page, last_page } = pagination;
  let paginationHtml = `
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
  `;

  // Bouton précédent
  paginationHtml += `
    <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" data-page="${current_page - 1}" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
  `;

  // Pages
  for (let i = 1; i <= last_page; i++) {
    if (
      i === 1 || // Première page
      i === last_page || // Dernière page
      (i >= current_page - 2 && i <= current_page + 2) // Pages autour de la page courante
    ) {
      paginationHtml += `
        <li class="page-item ${i === current_page ? 'active' : ''}">
          <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>
      `;
    } else if (
      i === current_page - 3 || // Avant la page courante
      i === current_page + 3 // Après la page courante
    ) {
      paginationHtml += `
        <li class="page-item disabled">
          <span class="page-link">...</span>
        </li>
      `;
    }
  }

  // Bouton suivant
  paginationHtml += `
    <li class="page-item ${current_page === last_page ? 'disabled' : ''}">
      <a class="page-link" href="#" data-page="${current_page + 1}" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  `;

  paginationHtml += `
        </ul>
      </nav>
  `;

  paginationContainer.innerHTML = paginationHtml;

  // Ajouter les écouteurs d'événements pour la pagination
  document.querySelectorAll('.page-link').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const page = e.target.closest('.page-link').dataset.page;
      if (page && !e.target.closest('.page-item').classList.contains('disabled')) {
        loadProducts(page);
      }
    });
  });
}

// Fonction pour supprimer un produit
function deleteProduct(id) {
  console.log('Début de la fonction deleteProduct avec ID:', id);
  
  if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
    console.log('Confirmation de suppression reçue');
    
    // Désactiver le bouton pendant la suppression
    const deleteButton = document.querySelector(`[onclick="deleteProduct(${id})"]`);
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

    console.log('Envoi de la requête POST pour supprimer le produit...');
    fetch(`/admin/produits/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': token.content,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      credentials: 'same-origin' // Important pour les requêtes CSRF
    })
    .then(response => {
      console.log('Réponse de suppression reçue:', response);
      if (!response.ok) {
        return response.json().then(data => {
          throw new Error(data.message || `Erreur HTTP: ${response.status}`);
        });
      }
      return response.json();
    })
    .then(data => {
      console.log('Données de réponse de suppression:', data);
      if (data.success) {
        // Recharger la liste des produits
        loadProducts();
        // Afficher un message de succès
        showNotification('Produit supprimé avec succès', 'success');
      } else {
        throw new Error(data.message || 'Erreur lors de la suppression du produit');
      }
    })
    .catch(error => {
      console.error('Erreur lors de la suppression:', error);
      // Afficher le message d'erreur dans une alerte plus visible
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-danger alert-dismissible fade show';
      alertDiv.style.position = 'fixed';
      alertDiv.style.top = '20px';
      alertDiv.style.right = '20px';
      alertDiv.style.zIndex = '9999';
      alertDiv.innerHTML = `
        <strong>Attention !</strong> ${error.message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
      document.body.appendChild(alertDiv);
      
      // Supprimer l'alerte après 5 secondes
      setTimeout(() => {
        alertDiv.remove();
      }, 5000);
    })
    .finally(() => {
      // Réactiver le bouton après la suppression
      if (deleteButton) {
        deleteButton.style.pointerEvents = 'auto';
        deleteButton.style.opacity = '1';
      }
    });
  } else {
    console.log('Suppression annulée par l\'utilisateur');
  }
}

document.addEventListener("DOMContentLoaded", () => {
  // Éléments du DOM
  const searchForm = document.getElementById('searchForm');
  const searchInput = document.getElementById('searchInput');
  const categoryFilter = document.getElementById('categoryFilter');
  const sortSelect = document.getElementById('sortSelect');

  // Écouteurs d'événements pour le formulaire de recherche
  if (searchForm) {
    searchForm.addEventListener('submit', (e) => {
      e.preventDefault();
      loadProducts(1);
    });
  }

  // Écouteurs d'événements pour les filtres
  if (categoryFilter) {
    categoryFilter.addEventListener('change', () => {
      loadProducts(1);
    });
  }

  if (sortSelect) {
    sortSelect.addEventListener('change', () => {
      loadProducts(1);
    });
  }

  // Charger les produits au chargement de la page
  loadProducts();
}); 