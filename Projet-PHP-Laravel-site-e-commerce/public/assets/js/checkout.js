document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM chargé, initialisation du checkout...");
    
    // Charger les produits du panier dans le résumé de commande
    displayOrderSummary();
  
    // Écouter l'événement sur le bouton de passer la commande
    const placeOrderButton = document.getElementById("place-order");
    if (placeOrderButton) {
        placeOrderButton.addEventListener("click", placeOrder);
    } else {
        console.error("Le bouton place-order n'a pas été trouvé");
    }
});
  
// Fonction pour afficher les produits du panier dans le résumé de commande
function displayOrderSummary() {
    const orderSummaryContainer = document.getElementById("order-summary");
    if (!orderSummaryContainer) {
        console.error("L'élément order-summary n'a pas été trouvé dans le DOM");
        return;
    }

    console.log("Récupération des totaux de la session...");
  
    // D'abord, récupérer les totaux de la session
    fetch("/cart/total", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        console.log("Réponse de /cart/total:", response.status);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(sessionData => {
        console.log("Données de session récupérées:", sessionData);
        
        // Ensuite, récupérer les produits du panier
        console.log("Récupération des produits du panier...");
        return fetch("/cart", {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log("Réponse de /cart:", response.status);
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Produits du panier récupérés:", data);
            
            orderSummaryContainer.innerHTML = ""; // Vider le tableau avant d'ajouter de nouveaux éléments
  
            if (!data || !data.success || !data.data || data.data.length === 0) {
                console.log("Panier vide ou données invalides:", data);
                orderSummaryContainer.innerHTML = "<tr><td colspan='2'>Panier vide</td></tr>";
                return;
            }
  
            // Calculer le sous-total à partir des produits
            let calculatedSubtotal = 0;
            
            // Ajouter chaque produit au résumé de commande
            data.data.forEach((item) => {
                console.log("Traitement du produit:", item);
                const itemTotal = item.prix * item.quantite;
                calculatedSubtotal += itemTotal;
  
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.nom_produit} <strong class="mx-2">×</strong> ${item.quantite}</td>
                    <td>${itemTotal.toFixed(2)} DH</td>
                `;
                orderSummaryContainer.appendChild(row);
            });
  
            // Utiliser les totaux de la session ou calculer si non disponibles
            const subtotal = sessionData.subtotal || calculatedSubtotal;
            const discount = sessionData.discount || 0;
            const total = sessionData.total || (subtotal - discount);
  
            console.log("Totaux calculés:", {
                subtotal: subtotal,
                discount: discount,
                total: total
            });
  
            // Ajouter la ligne de sous-total
            const subtotalRow = document.createElement("tr");
            subtotalRow.innerHTML = `
                <td class="text-black font-weight-bold"><strong>Sous-total</strong></td>
                <td class="text-black">${subtotal.toFixed(2)} DH</td>
            `;
            orderSummaryContainer.appendChild(subtotalRow);
  
            // Ajouter la ligne de réduction seulement si une réduction est appliquée
            if (discount > 0) {
                const discountRow = document.createElement("tr");
                discountRow.innerHTML = `
                    <td class="text-black font-weight-bold"><strong>Réduction</strong></td>
                    <td class="text-black">-${discount.toFixed(2)} DH</td>
                `;
                orderSummaryContainer.appendChild(discountRow);
                
                // Ajouter une ligne pour le code coupon si disponible
                if (sessionData.coupon_code) {
                    const couponRow = document.createElement("tr");
                    couponRow.innerHTML = `
                        <td class="text-black"><em>Code coupon: ${sessionData.coupon_code}</em></td>
                        <td></td>
                    `;
                    orderSummaryContainer.appendChild(couponRow);
                }
            }
  
            // Ajouter la ligne de total
            const totalRow = document.createElement("tr");
            totalRow.innerHTML = `
                <td class="text-black font-weight-bold"><strong>Total</strong></td>
                <td class="text-black font-weight-bold"><strong>${total.toFixed(2)} DH</strong></td>
            `;
            orderSummaryContainer.appendChild(totalRow);
        });
    })
    .catch(error => {
        console.error("Erreur lors de la récupération des données:", error);
        orderSummaryContainer.innerHTML = `
            <tr>
                <td colspan="2" class="text-danger">
                    Erreur lors du chargement des données. Veuillez rafraîchir la page.
                </td>
            </tr>
        `;
    });
}
  
// Fonction de secours pour afficher les produits sans les totaux de session
function displayCartItemsWithoutSession() {
    fetch("http://127.0.0.1:8000/cart")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erreur lors du chargement des produits du panier")
        }
        return response.json()
      })
      .then((data) => {
        const orderSummaryContainer = document.getElementById("order-summary")
        orderSummaryContainer.innerHTML = ""
  
        if (data.length === 0) {
          orderSummaryContainer.innerHTML = "<tr><td colspan='2'>Panier vide</td></tr>"
          return
        }
  
        let subtotal = 0
        
        data.forEach((item) => {
          const itemTotal = item.prix * item.quantite
          subtotal += itemTotal
  
          const row = document.createElement("tr")
          row.innerHTML = `
            <td>${item.nom_produit} <strong class="mx-2">×</strong> ${item.quantite}</td>
            <td>${itemTotal.toFixed(2)} DH</td>
          `
          orderSummaryContainer.appendChild(row)
        })
  
        // Ajouter seulement le sous-total comme total (pas de réduction)
        const subtotalRow = document.createElement("tr")
        subtotalRow.innerHTML = `
          <td class="text-black font-weight-bold"><strong>Sous-total</strong></td>
          <td class="text-black">${subtotal.toFixed(2)} DH</td>
        `
        orderSummaryContainer.appendChild(subtotalRow)
  
        const totalRow = document.createElement("tr")
        totalRow.innerHTML = `
          <td class="text-black font-weight-bold"><strong>Total de la commande</strong></td>
          <td class="text-black font-weight-bold"><strong>${subtotal.toFixed(2)} DH</strong></td>
        `
        orderSummaryContainer.appendChild(totalRow)
      })
      .catch((error) => {
        console.error("Erreur:", error)
        showNotification(error.message || "Impossible de charger les articles du panier", "error")
      })
  }
  
  // Fonction pour valider un email
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }
  
  // Fonction pour vérifier les vendeurs bloqués
  function checkBlockedVendors() {
    return fetch("/cart", {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data || !data.success || !data.data || data.data.length === 0) {
            return { hasBlockedVendors: false, blockedProducts: [] };
        }

        // Vérifier chaque produit pour les vendeurs bloqués
        const blockedProducts = data.data.filter(item => item.vendeur_blocked);
        
        return {
            hasBlockedVendors: blockedProducts.length > 0,
            blockedProducts: blockedProducts
        };
    });
  }
  
  // Fonction pour passer la commande
  function placeOrder(e) {
    e.preventDefault();

    // D'abord vérifier les vendeurs bloqués
    checkBlockedVendors()
        .then(({ hasBlockedVendors, blockedProducts }) => {
            if (hasBlockedVendors) {
                // Construire le message d'erreur avec les produits concernés
                let errorMessage = "Impossible de procéder au paiement. Les produits suivants ne sont plus disponibles car leurs vendeurs ont été bloqués :<br><ul>";
                blockedProducts.forEach(product => {
                    errorMessage += `<li>${product.nom_produit}</li>`;
                });
                errorMessage += "</ul>Veuillez retirer ces produits de votre panier avant de continuer.";
                showNotification(errorMessage, "error");
                return;
            }

            // Récupérer la méthode de paiement choisie
            const paymentMethod = document.getElementById('payment_method').value;
            console.log("Méthode de paiement sélectionnée:", paymentMethod);

            // Récupérer les données du formulaire
            const formData = {
                customer: {
                    first_name: document.getElementById('c_fname').value,
                    last_name: document.getElementById('c_lname').value,
                    email: document.getElementById('c_email_address').value,
                    phone: document.getElementById('c_phone').value,
                    address: document.getElementById('c_address').value,
                    city: document.getElementById('c_country').value,
                    state: document.getElementById('c_state_country').value,
                    postal_code: document.getElementById('c_postal_zip').value
                },
                payment_method: paymentMethod
            };

            console.log("Données du formulaire:", formData);

            // Valider les données
            if (!formData.customer.first_name || !formData.customer.last_name || 
                !formData.customer.email || !formData.customer.phone || 
                !formData.customer.address || !formData.customer.city || 
                !formData.customer.state || !formData.customer.postal_code) {
                showNotification("Veuillez remplir tous les champs obligatoires", "error");
                return;
            }

            if (!isValidEmail(formData.customer.email)) {
                showNotification("Veuillez entrer une adresse email valide", "error");
                return;
            }

            // Envoyer la commande au serveur
            submitOrder(formData);
        })
        .catch(error => {
            console.error("Erreur lors de la vérification des vendeurs:", error);
            showNotification("Une erreur est survenue lors de la vérification des produits", "error");
        });
  }
  
  // Fonction pour soumettre la commande au serveur
  function submitOrder(orderData) {
    // Afficher un indicateur de chargement
    const placeOrderButton = document.getElementById("place-order");
    const originalButtonText = placeOrderButton.innerHTML;
    placeOrderButton.disabled = true;
    placeOrderButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement en cours...';

    console.log("Envoi des données de commande:", orderData);

    // Envoyer la commande au serveur
    fetch("/api/orders/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(orderData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || "Erreur lors de la création de la commande");
            });
        }
        return response.json();
    })
    .then(data => {
        console.log("Réponse du serveur:", data);
        if (data.success) {
            // Rediriger vers la page de remerciement
            window.location.href = `/client/thankyou`;
        } else {
            throw new Error(data.message || "Erreur lors de la création de la commande");
        }
    })
    .catch(error => {
        console.error("Erreur:", error);
        showNotification(error.message || "Une erreur est survenue lors de la création de la commande", "error");
        // Réactiver le bouton
        placeOrderButton.disabled = false;
        placeOrderButton.innerHTML = originalButtonText;
    });
  }
  
  // Fonction pour déterminer la méthode de paiement sélectionnée
  function getSelectedPaymentMethod() {
    if (document.querySelector("#collapsebank.show")) {
      return "espece";
    } else if (document.querySelector("#collapsepaypal.show")) {
      return "carte"; // ou "paypal" si tu veux gérer ce cas plus tard
    } else {
      // Méthode par défaut
      return "espece";
    }
  }
  
  // Fonction d'affichage des notifications
  function showNotification(message, type = "info") {
    // Créer un élément de notification
    const notification = document.createElement("div")
    notification.className = `notification ${type}`
    notification.textContent = message
  
    // Ajouter la notification au document
    const container = document.querySelector(".notification-container")
    if (!container) {
      const notifContainer = document.createElement("div")
      notifContainer.className = "notification-container"
      document.body.appendChild(notifContainer)
      notifContainer.appendChild(notification)
    } else {
      container.appendChild(notification)
    }
  
    // Supprimer la notification après 3 secondes
    setTimeout(() => {
      notification.classList.add("fade-out")
      setTimeout(() => {
        notification.remove()
      }, 500)
    }, 3000)
  }
  
  // Ajouter du CSS pour les notifications
  document.addEventListener("DOMContentLoaded", () => {
    // Créer un conteneur de notifications s'il n'existe pas déjà
    if (!document.querySelector(".notification-container")) {
      const notifContainer = document.createElement("div")
      notifContainer.className = "notification-container"
      document.body.appendChild(notifContainer)
    }
  
    // Ajouter le style pour les notifications
    const style = document.createElement("style")
    style.textContent = `
      /* Styles pour les notifications */
      .notification-container {
          position: fixed;
          top: 20px;
          right: 20px;
          z-index: 1000;
      }
      .notification {
          padding: 12px 20px;
          margin-bottom: 10px;
          border-radius: 4px;
          color: white;
          box-shadow: 0 3px 6px rgba(0,0,0,0.16);
          animation: slide-in 0.3s ease-out forwards;
      }
      .notification.success {
          background-color: #4CAF50;
      }
      .notification.error {
          background-color: #F44336;
      }
      .notification.info {
          background-color: #2196F3;
      }
      .notification.fade-out {
          animation: fade-out 0.5s ease-out forwards;
      }
      @keyframes slide-in {
          from { transform: translateX(100%); opacity: 0; }
          to { transform: translateX(0); opacity: 1; }
      }
      @keyframes fade-out {
          from { opacity: 1; }
          to { opacity: 0; }
      }
      
      /* Style pour les champs invalides */
      .is-invalid {
          border-color: #dc3545 !important;
      }
      
      .invalid-feedback {
          display: block;
          width: 100%;
          margin-top: 0.25rem;
          font-size: 80%;
          color: #dc3545;
      }
    `
    document.head.appendChild(style)
  })