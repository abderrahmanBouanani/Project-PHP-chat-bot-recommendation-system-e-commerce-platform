document.addEventListener("DOMContentLoaded", () => {
  // Charger les produits du panier dès que la page est prête
  displayCartItems()

  // Écouter l'événement sur le bouton d'application du coupon
  document.getElementById("applyCoupon")?.addEventListener("click", applyCoupon)

  // Ajouter des écouteurs d'événements pour les boutons d'augmentation/diminution
  document.addEventListener("click", (event) => {
    // Gérer les boutons d'augmentation
    if (event.target.classList.contains("increase")) {
      event.preventDefault() // Empêcher toute action par défaut
      event.stopPropagation() // Empêcher la propagation de l'événement
      const productId = event.target.getAttribute("data-id")
      increaseQuantity(productId)
      return false // Empêcher toute autre action
    }

    // Gérer les boutons de diminution
    if (event.target.classList.contains("decrease")) {
      event.preventDefault() // Empêcher toute action par défaut
      event.stopPropagation() // Empêcher la propagation de l'événement
      const productId = event.target.getAttribute("data-id")
      decreaseQuantity(productId)
      return false // Empêcher toute autre action
    }
  })
})

// Fonction pour afficher les produits du panier
function displayCartItems() {
  const cartItemsContainer = document.getElementById("cart-items");
  if (!cartItemsContainer) {
    console.error("L'élément cart-items n'a pas été trouvé dans le DOM");
    return;
  }

  fetch("http://127.0.0.1:8000/cart")
    .then((response) => {
      if (!response.ok) {
        return response.json().then(data => {
          throw new Error(data.message || "Erreur lors du chargement des produits du panier");
        });
      }
      return response.json();
    })
    .then((data) => {
      if (!data.success) {
        throw new Error(data.message || "Erreur lors du chargement des produits du panier");
      }

      cartItemsContainer.innerHTML = ""; // Vider le tableau avant d'ajouter de nouveaux éléments

      if (data.data.length === 0) {
        cartItemsContainer.innerHTML = "<tr><td colspan='6'>Panier vide</td></tr>";
        return;
      }

      data.data.forEach((item) => {
        const row = document.createElement("tr");
        row.setAttribute("data-product-id", item.id);
        row.innerHTML = `
                    <td><img src="http://127.0.0.1:8000/storage/images/${item.image}" alt="${item.nom_produit}" width="100" style="mix-blend-mode: multiply"></td>
                    <td>${item.nom_produit}</td>
                    <td class="product-price" data-price="${item.prix}">${item.prix} DH</td>
                    <td class="quantity-container">
                        <div class="quantity-controls">
                            <button type="button" class="decrease" data-id="${item.id}">−</button>
                            <input id="quantity-${item.id}" class="quantity-amount" type="text" value="${item.quantite}" disabled>
                            <button type="button" class="increase" data-id="${item.id}">+</button>
                        </div>
                    </td>
                    <td id="total-${item.id}" class="product-total" data-price="${item.prix}">${(item.prix * item.quantite).toFixed(2)} DH</td>
                    <td><button type="button" class="remove-btn" onclick="removeFromCart(${item.id})">×</button></td>
                `;
        cartItemsContainer.appendChild(row);
      });

      // Mettre à jour les totaux après l'affichage des articles
      updateCartTotals();
    })
    .catch((error) => {
      console.error("Erreur:", error);
      showNotification(error.message || "Impossible de charger les articles du panier", "error");
    });
}

// Fonction pour augmenter ou diminuer la quantité
function updateQuantity(id, newQuantity) {
  // Afficher un indicateur de chargement
  const quantityInput = document.getElementById(`quantity-${id}`)
  const row = document.querySelector(`tr[data-product-id="${id}"]`)

  if (row) {
    row.classList.add("updating")
  }

  console.log(`Mise à jour de la quantité: ID=${id}, Nouvelle quantité=${newQuantity}`)
  console.log(`URL: http://127.0.0.1:8000/cart/update/${id}`)

  // Créer un objet FormData pour l'envoi
  const formData = new FormData()
  formData.append("quantity", newQuantity)
  formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"))

  // Utiliser fetch avec XMLHttpRequest pour plus de contrôle
  const xhr = new XMLHttpRequest()
  xhr.open("POST", `http://127.0.0.1:8000/cart/update/${id}`, true)
  xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute("content"))
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest") // Pour indiquer que c'est une requête AJAX

  xhr.onreadystatechange = () => {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const data = JSON.parse(xhr.responseText)
          if (data.success) {
            // Mettre à jour l'affichage
            quantityInput.value = data.updatedQuantity

            // Mettre à jour le prix total de la ligne
            const totalElement = document.getElementById(`total-${id}`)
            const price = Number.parseFloat(row.querySelector(".product-price").getAttribute("data-price"))
            totalElement.textContent = (price * data.updatedQuantity).toFixed(2) + " DH"

            // Mettre à jour les totaux du panier
            updateCartTotals()

            showNotification(data.message, "success")
          } else {
            showNotification(data.message || "Erreur lors de la mise à jour", "error")
          }
        } catch (e) {
          console.error("Erreur de parsing JSON:", e)
          showNotification("Erreur de réponse du serveur", "error")
        }
      } else {
        console.error("Erreur HTTP:", xhr.status)
        showNotification(`Erreur HTTP: ${xhr.status}`, "error")
      }

      // Supprimer l'indicateur de chargement
      if (row) {
        row.classList.remove("updating")
      }
    }
  }

  xhr.send(formData)
}

// Fonction pour augmenter la quantité
function increaseQuantity(id) {
  const quantityInput = document.getElementById(`quantity-${id}`)
  const currentQuantity = Number.parseInt(quantityInput.value)
  updateQuantity(id, currentQuantity + 1)
}

// Fonction pour diminuer la quantité
function decreaseQuantity(id) {
  const quantityInput = document.getElementById(`quantity-${id}`)
  const currentQuantity = Number.parseInt(quantityInput.value)
  if (currentQuantity > 1) {
    updateQuantity(id, currentQuantity - 1)
  }
}

function removeFromCart(id) {
  // Empêcher toute action par défaut
  event.preventDefault()
  event.stopPropagation()

  if (!confirm("Êtes-vous sûr de vouloir supprimer cet article ?")) {
    return
  }

  fetch(`http://127.0.0.1:8000/client_cart/remove/${id}`, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur lors de la suppression")
      }
      return response.json()
    })
    .then((data) => {
      if (data.success) {
        showNotification(data.message, "success")

        // Recharger la liste sans recharger toute la page
        displayCartItems()
      } else {
        showNotification(data.message, "error")
      }
    })
    .catch((error) => {
      console.error("Erreur AJAX :", error)
      showNotification("Erreur AJAX : " + error.message, "error")
    })

  return false
}

// Fonction pour appliquer un coupon de réduction
function applyCoupon(e) {
  e.preventDefault()

  const couponCode = document.getElementById("coupon").value.trim()
  if (!couponCode) {
    showNotification("Veuillez entrer un code coupon", "error")
    return
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")

  // Calculer le sous-total actuel pour l'envoyer au serveur
  const subtotalText = document.getElementById("subtotal").textContent
  const subtotal = Number.parseFloat(subtotalText.replace(" DH", ""))

  console.log("Sous-total envoyé au serveur:", subtotal)

  fetch("http://127.0.0.1:8000/coupon/apply", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-CSRF-TOKEN": csrfToken || "",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify({
      code: couponCode,
      subtotal: subtotal, // Envoyer le sous-total pour le calcul côté serveur
    }),
    credentials: "same-origin",
  })
    .then((response) => {
      if (!response.ok) {
        return response.json().then((data) => {
          throw new Error(data.message || "Coupon invalide")
        })
      }
      return response.json()
    })
    .then((data) => {
      // Afficher la réponse complète pour le débogage
      console.log("Réponse du serveur pour le coupon:", data)

      // Calculer manuellement la réduction en fonction du type de coupon
      let calculatedDiscount = 0
      const subtotal = Number.parseFloat(document.getElementById("subtotal").textContent.replace(" DH", ""))

      if (data.discount_type === "percentage") {
        calculatedDiscount = (subtotal * data.discount_value) / 100
        console.log(`Calcul de la réduction: ${subtotal} * ${data.discount_value}% = ${calculatedDiscount}`)
      } else if (data.discount_type === "fixed") {
        calculatedDiscount = data.discount_value
        // S'assurer que la réduction ne dépasse pas le sous-total
        if (calculatedDiscount > subtotal) {
          calculatedDiscount = subtotal
        }
        console.log(`Réduction fixe: ${calculatedDiscount}`)
      }

      // Utiliser la réduction calculée ou celle fournie par le serveur
      const discount = calculatedDiscount || data.discount_amount || 0
      console.log("Réduction finale:", discount)

      const total = subtotal - discount
      console.log(`Total calculé: ${subtotal} - ${discount} = ${total}`)

      // Afficher les détails du coupon
      let couponMessage = ""
      if (data.discount_type === "percentage") {
        couponMessage = `Coupon ${couponCode}: ${data.discount_value}% de réduction`
      } else {
        couponMessage = `Coupon ${couponCode}: ${data.discount_value} DH de réduction`
      }

      // Mettre à jour les totaux avec les valeurs exactes
      updateCartTotals(subtotal, discount, total)
      // Stocker explicitement les totaux et le code coupon dans la session
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")
fetch("http://127.0.0.1:8000/api/cart/update-totals", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": csrfToken || "",
    "X-Requested-With": "XMLHttpRequest",
  },
  body: JSON.stringify({ 
    subtotal: subtotal,
    discount: discount,
    total: total,
    coupon_code: couponCode
  }),
}).catch((error) => console.error("Erreur lors de la mise à jour des totaux avec coupon:", error))

      // Afficher un message de succès
      showNotification(`${couponMessage} appliqué avec succès (${discount.toFixed(2)} DH)`, "success")

      // Désactiver le champ de coupon et le bouton
      document.getElementById("coupon").disabled = true
      document.getElementById("applyCoupon").disabled = true
      document.getElementById("applyCoupon").textContent = "Appliqué"
    })
    .catch((error) => {
      console.error("Erreur:", error)
      showNotification(error.message || "Coupon invalide ou expiré", "error")
    })
}

// Mettre à jour la fonction updateCartTotals pour stocker le sous-total dans la session
// Mettre à jour la fonction updateCartTotals pour stocker le sous-total, la réduction et le total dans la session
function updateCartTotals(subtotalValue = null, discountValue = null, totalValue = null) {
  console.log(`updateCartTotals appelé avec: subtotal=${subtotalValue}, discount=${discountValue}, total=${totalValue}`)

  // Si les valeurs ne sont pas fournies, les calculer à partir du tableau
  if (subtotalValue === null) {
    subtotalValue = 0
    const rows = document.querySelectorAll("#cart-items tr")
    rows.forEach((row) => {
      const totalCell = row.querySelector(".product-total")
      if (totalCell) {
        const itemTotalText = totalCell.textContent.replace(" DH", "")
        const itemTotal = Number.parseFloat(itemTotalText)
        if (!isNaN(itemTotal)) {
          subtotalValue += itemTotal
        }
      }
    })
    console.log("Sous-total calculé à partir des lignes:", subtotalValue)

    // Récupérer la réduction actuelle si elle existe
    const discountText = document.getElementById("discount").textContent.replace(" DH", "")
    discountValue = Number.parseFloat(discountText) || 0
    console.log("Réduction actuelle:", discountValue)

    // Calculer le total
    totalValue = subtotalValue - discountValue
    console.log(`Total calculé: ${subtotalValue} - ${discountValue} = ${totalValue}`)
  }

  // Mettre à jour les affichages avec les valeurs exactes
  document.getElementById("subtotal").textContent = subtotalValue.toFixed(2) + " DH"
  document.getElementById("discount").textContent = discountValue.toFixed(2) + " DH"
  document.getElementById("total").textContent = totalValue.toFixed(2) + " DH"

  console.log("Valeurs mises à jour dans l'interface:", {
    subtotal: subtotalValue.toFixed(2) + " DH",
    discount: discountValue.toFixed(2) + " DH",
    total: totalValue.toFixed(2) + " DH",
  })

  // Stocker le sous-total, la réduction et le total dans la session via une requête AJAX
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")

  fetch("http://127.0.0.1:8000/api/cart/update-totals", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrfToken || "",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify({ 
      subtotal: subtotalValue,
      discount: discountValue,
      total: totalValue
    }),
  }).catch((error) => console.error("Erreur lors de la mise à jour des totaux:", error))
}

// Fonction d'affichage des notifications améliorée
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

// Ajouter du CSS pour les notifications et les contrôles de quantité
document.addEventListener("DOMContentLoaded", () => {
  // Créer un conteneur de notifications s'il n'existe pas déjà
  if (!document.querySelector(".notification-container")) {
    const notifContainer = document.createElement("div")
    notifContainer.className = "notification-container"
    document.body.appendChild(notifContainer)
  }

  // Ajouter le style pour les notifications et les contrôles
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
        
        /* Style pour les lignes en cours de mise à jour */
        .updating {
            opacity: 0.7;
            pointer-events: none;
        }
        
        /* Styles pour les contrôles de quantité */
        .quantity-container {
            vertical-align: middle;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }
        
        .quantity-controls button {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s ease;
            margin: 0 2px;
        }
        
        .quantity-controls button:hover {
            background-color: #e9ecef;
            border-color: #ced4da;
        }
        
        .quantity-controls button:active {
            transform: scale(0.95);
        }
        
        .quantity-controls .quantity-amount {
            width: 40px;
            height: 28px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 0 5px;
            font-size: 14px;
            padding: 0;
        }
        
        /* Style pour le bouton de suppression */
        .remove-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ff4d4d;
            color: white;
            border: none;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .remove-btn:hover {
            background-color: #ff3333;
            transform: scale(1.05);
            box-shadow: 0 3px 7px rgba(0,0,0,0.15);
        }
        
        .remove-btn:active {
            transform: scale(0.95);
        }
    `
  document.head.appendChild(style)
})
