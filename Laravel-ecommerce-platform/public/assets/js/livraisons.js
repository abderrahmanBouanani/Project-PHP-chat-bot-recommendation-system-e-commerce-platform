document.addEventListener("DOMContentLoaded", () => {
    let allDeliveries = []
  
    function loadDeliveries() {
      // Charger les commandes depuis localStorage
      const orders = JSON.parse(localStorage.getItem("orders")) || []
      
      // Convertir les commandes en livraisons
      allDeliveries = orders.map(order => ({
        id: order.id,
        customer: order.customer,
        address: order.customer.address || order.address || "Adresse non spécifiée",
        date: order.date,
        status: mapOrderStatusToDeliveryStatus(order.status),
        total: order.total,
        items: order.items
      }))
      
      // Ne pas effacer les commandes existantes, mais ajouter les nouvelles
      const existingIds = new Set(Array.from(document.querySelectorAll("tbody tr")).map(tr => tr.querySelector("td:first-child").textContent))
      
      allDeliveries.forEach(delivery => {
        // Ne pas ajouter les commandes qui existent déjà
        if (existingIds.has(delivery.id.toString())) return
        
        // Ne pas afficher les commandes annulées
        if (delivery.status === "cancelled") return
        
        const row = document.createElement("tr")
        row.innerHTML = `
          <td>${delivery.id}</td>
          <td>${delivery.address}</td>
          <td>${formatDate(delivery.date)}</td>
          <td>${getStatusBadge(delivery.status)}</td>
          <td>
            <button class="btn btn-sm btn-link" onclick="showProducts(${delivery.id})">
              Voir les produits
            </button>
            ${getActionButtons(delivery)}
          </td>
        `
        document.querySelector("tbody").appendChild(row)
      })
    }
    
    // Convertir le statut de commande en statut de livraison
    function mapOrderStatusToDeliveryStatus(orderStatus) {
      switch (orderStatus) {
        case "Confirmée":
          return "pending"
        case "En attente":
          return "pending"
        case "En cours":
          return "in_progress"
        case "Livrée":
          return "delivered"
        case "Annulée":
          return "cancelled"
        default:
          return "pending"
      }
    }
  
    function displayDeliveries(deliveries) {
      const deliveriesList = document.getElementById("deliveriesList")
      deliveriesList.innerHTML = ""
  
      deliveries.forEach((delivery) => {
        // Ne pas afficher les commandes annulées
        if (delivery.status === "cancelled") return
        
        const row = document.createElement("tr")
        row.innerHTML = `
          <td>${delivery.id}</td>
          <td>${delivery.address}</td>
          <td>${formatDate(delivery.date)}</td>
          <td>${getStatusBadge(delivery.status)}</td>
          <td>
            <button class="btn btn-sm btn-link" onclick="showProducts(${delivery.id})">
              Voir les produits
            </button>
            ${getActionButtons(delivery)}
          </td>
        `
        deliveriesList.appendChild(row)
      })
    }
  
    function formatDate(dateString) {
      const options = { year: "numeric", month: "long", day: "numeric" }
      return new Date(dateString).toLocaleDateString("fr-FR", options)
    }
  
    function getStatusBadge(status) {
      switch (status) {
        case "pending":
          return '<span class="badge bg-warning text-dark">En attente</span>'
        case "in_progress":
          return '<span class="badge bg-primary">En cours</span>'
        case "delivered":
          return '<span class="badge bg-success">Livrée</span>'
        default:
          return '<span class="badge bg-secondary">Inconnu</span>'
      }
    }
  
    function getActionButtons(delivery) {
      if (delivery.status === "pending") {
        return `<button class="btn btn-sm btn-primary" onclick="startDelivery(${delivery.id})">Commencer</button>`
      } else if (delivery.status === "in_progress") {
        return `<button class="btn btn-sm btn-success" onclick="completeDelivery(${delivery.id})">Terminer</button>`
      }
      return ""
    }
  
    function filterDeliveries() {
      const searchTerm = document.getElementById("searchInput").value;
      const statusFilter = document.getElementById("statusFilter").value;
      
      // Appel à l'API de recherche
      fetch(`/api/livreur/livraison/search?search=${searchTerm}&status=${statusFilter}`)
        .then(response => response.json())
        .then(data => {
          displayDeliveries(data);
        })
        .catch(error => {
          console.error('Error searching deliveries:', error);
        });
    }
  
    // Fonction pour mettre à jour le statut d'une commande
    function updateOrderStatus(orderId, newStatus) {
        fetch(`/livreur/commande/${orderId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recharger la page pour afficher les changements
                window.location.reload();
            } else {
                alert(data.message || 'Erreur lors de la mise à jour du statut.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de la mise à jour du statut.');
        });
    }
  
    // Gestion des boutons Accepter
    document.querySelectorAll('.accepter-commande').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            if (confirm('Êtes-vous sûr de vouloir accepter cette commande ?')) {
                fetch(`/livreur/commande/${id}/accepter`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour le statut dans l'interface
                        const row = this.closest('tr');
                        const statusCell = row.querySelector('td:nth-child(4)');
                        statusCell.innerHTML = '<span class="badge bg-primary">En cours de livraison</span>';
                        
                        // Mettre à jour les boutons d'action
                        const actionCell = row.querySelector('td:nth-child(5)');
                        actionCell.innerHTML = `
                            <button class="btn btn-sm btn-link" onclick="showProducts(${id})">
                                Voir les produits
                            </button>
                            <button class="btn btn-sm btn-success livree-commande" data-id="${id}">
                                Livrée
                            </button>
                        `;
                        
                        // Réattacher les événements aux nouveaux boutons
                        attachEventListeners();
                    } else {
                        alert(data.message || 'Erreur lors de l\'acceptation de la commande');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de l\'acceptation de la commande');
                });
            }
        });
    });
  
    // Gestion des boutons Livrée
    document.querySelectorAll('.livree-commande').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            if (confirm('Êtes-vous sûr de vouloir marquer cette commande comme livrée ?')) {
                fetch(`/livreur/commande/${id}/livree`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour le statut dans l'interface
                        const row = this.closest('tr');
                        const statusCell = row.querySelector('td:nth-child(4)');
                        statusCell.innerHTML = '<span class="badge bg-success">Livrée</span>';
                        
                        // Mettre à jour les boutons d'action
                        const actionCell = row.querySelector('td:nth-child(5)');
                        actionCell.innerHTML = `
                            <button class="btn btn-sm btn-link" onclick="showProducts(${id})">
                                Voir les produits
                            </button>
                        `;
                    } else {
                        alert(data.message || 'Erreur lors de la mise à jour du statut');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la mise à jour du statut');
                });
            }
        });
    });
  
    // Fonction pour réattacher les événements aux boutons
    function attachEventListeners() {
        document.querySelectorAll('.accepter-commande').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // ... code existant ...
            });
        });

        document.querySelectorAll('.livree-commande').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // ... code existant ...
            });
        });
    }
  
    // Fonction pour afficher les produits dans le modal
    window.showProducts = (orderId) => {
        fetch(`/livreur/commande/${orderId}/produits`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(products => {
                const modalProductsList = document.getElementById("modalProductsList");
                const productsModal = document.getElementById("productsModal");
                
                if (!modalProductsList || !productsModal) {
                    throw new Error('Modal elements not found');
                }
                
                modalProductsList.innerHTML = '';
                
                if (!products || products.length === 0) {
                    modalProductsList.innerHTML = `<tr><td colspan="4" class="text-center">Aucun produit dans cette commande</td></tr>`;
                } else {
                    products.forEach(item => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${item.nom}</td>
                            <td>${item.quantite}</td>
                            <td>${parseFloat(item.prix_unitaire).toFixed(2)} DH</td>
                            <td>${(item.quantite * item.prix_unitaire).toFixed(2)} DH</td>
                        `;
                        modalProductsList.appendChild(row);
                    });
                }
                
                const myModal = new bootstrap.Modal(productsModal);
                myModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors du chargement des produits: ' + error.message);
            });
    };
  
    // Sauvegarder les modifications dans localStorage
    function saveDeliveries() {
      // Récupérer les commandes existantes
      const orders = JSON.parse(localStorage.getItem("orders")) || []
      
      // Mettre à jour le statut des commandes en fonction des livraisons
      allDeliveries.forEach(delivery => {
        const orderIndex = orders.findIndex(order => order.id === delivery.id)
        if (orderIndex !== -1) {
          // Mettre à jour le statut de la commande
          if (delivery.status === "delivered") {
            orders[orderIndex].status = "Livrée"
          } else if (delivery.status === "in_progress") {
            orders[orderIndex].status = "En cours"
          }
        }
      })
      
      // Sauvegarder les commandes mises à jour
      localStorage.setItem("orders", JSON.stringify(orders))
    }
  
    // Initialiser la page
    loadDeliveries()
  
    // Ajouter les écouteurs d'événements après que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
      const searchButton = document.getElementById("searchButton")
      const searchInput = document.getElementById("searchInput")
      const statusFilter = document.getElementById("statusFilter")

      if (searchButton) searchButton.addEventListener("click", filterDeliveries)
      if (searchInput) searchInput.addEventListener("input", filterDeliveries)
      if (statusFilter) statusFilter.addEventListener("change", filterDeliveries)
    })
  
    // Ces fonctions seraient connectées à un backend dans une application réelle
    window.startDelivery = (id) => {
      const delivery = allDeliveries.find((d) => d.id === id)
      if (delivery) {
        delivery.status = "in_progress"
        saveDeliveries()
        displayDeliveries(allDeliveries)
      }
    }
  
    window.completeDelivery = (id) => {
      const delivery = allDeliveries.find((d) => d.id === id)
      if (delivery) {
        delivery.status = "delivered"
        saveDeliveries()
        displayDeliveries(allDeliveries)
      }
    }
  })