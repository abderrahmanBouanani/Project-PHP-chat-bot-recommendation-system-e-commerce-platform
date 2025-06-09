/**
 * Boutique.js - Script principal pour la boutique en ligne
 * VERSION CORRIGÉE - Affiche tous les produits avec gestion de stock
 */
document.addEventListener("DOMContentLoaded", () => {
  // Fonction pour récupérer les produits depuis l'API avec pagination
  function getProducts(page = 1, limit = 8) {
    return fetch(`http://127.0.0.1:8000/api/produits?page=${page}&limit=${limit}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          return {
            products: data.data || [],
            pagination: {
              total: data.total || 0,
              currentPage: data.current_page || 1,
              lastPage: data.last_page || 1,
              perPage: data.per_page || 8,
            },
          }
        } else {
          console.error("Format de données invalide:", data)
          return { products: [], pagination: { total: 0, currentPage: 1, lastPage: 1, perPage: 8 } }
        }
      })
      .catch((error) => {
        console.error("Erreur lors de la récupération des produits:", error)
        return { products: [], pagination: { total: 0, currentPage: 1, lastPage: 1, perPage: 8 } }
      })
  }

  // Fonction pour obtenir la quantité d'un produit (gestion des différents noms de champs)
  function getProductQuantity(product) {
    // Essayer différents noms de champs possibles
    return product.quantite || product.quantity || product.stock || product.qte || 0
  }

  // Fonction pour afficher les produits AVEC GESTION DE STOCK CORRIGÉE
  function displayProducts(productsToDisplay, pagination = null) {
    const productList = document.getElementById("product-list")
    if (!productList) {
      console.error("L'élément product-list n'a pas été trouvé dans le DOM")
      return
    }

    productList.innerHTML = "" // Vide le conteneur avant d'afficher les produits

    if (!Array.isArray(productsToDisplay)) {
      console.error("productsToDisplay n'est pas un tableau:", productsToDisplay)
      return
    }

    // Créer un conteneur pour les produits
    const productsContainer = document.createElement("div")
    productsContainer.className = "row w-100"

    productsToDisplay.forEach((product) => {
      const productElement = document.createElement("div")
      productElement.className = "col-12 col-md-4 col-lg-3 mb-5"

      // LOGIQUE CORRIGÉE : Vérifier le stock avec gestion robuste
      const quantity = getProductQuantity(product)
      const isOutOfStock = quantity === 0
      const isLowStock = quantity > 0 && quantity <= 5

      console.log(`Produit: ${product.nom}, Quantité: ${quantity}, En rupture: ${isOutOfStock}`) // Debug

      // Générer le badge de stock
      let stockBadge = ""
      let productClass = "product-item product"

      if (isOutOfStock) {
        stockBadge = `
          <div class="stock-badge out-of-stock">
            <span>⚠️ Rupture du stock</span>
          </div>
        `
        productClass += " out-of-stock-item"
      } else if (isLowStock) {
        stockBadge = `
          <div class="stock-badge low-stock">
            <span>⚠️ Stock faible (${quantity})</span>
          </div>
        `
        productClass += " low-stock-item"
      } 

      productElement.innerHTML = `
        <a class="${productClass}"
           href="#"
           data-id="${product.id}"
           data-name="${product.nom}"
           data-description="${product.description || "Aucune description disponible"}"
           data-price="${product.prix_unitaire}"
           data-category="${product.categorie || "Non catégorisé"}"
           data-quantity="${quantity}"
           data-image="http://127.0.0.1:8000/storage/${product.image}"
           ${isOutOfStock ? 'data-out-of-stock="true"' : ""}>
          <div class="product-image-container">
            <img src="http://127.0.0.1:8000/storage/${product.image}" 
                 class="img-fluid product-thumbnail ${isOutOfStock ? "grayscale" : ""}" 
                 style="mix-blend-mode: multiply;">
            ${stockBadge}
            ${isOutOfStock ? '<div class="overlay-disabled">INDISPONIBLE</div>' : ""}
          </div>
          <h3 class="product-title ${isOutOfStock ? "text-muted" : ""}">${product.nom}</h3>
          ${product.vendeur && product.vendeur.blocked ? 
            '<div class="blocked-vendor-info"><span class="blocked-icon">⚠️</span> Vendeur bloqué - Produit inaccessible</div>' : ''}
          <strong class="product-price ${isOutOfStock ? "text-muted" : ""}">${product.prix_unitaire} DH</strong>
          <span class="icon-cross ${isOutOfStock ? "disabled" : ""}">
            <img src="../images/cross.svg" class="img-fluid">
          </span>
        </a>
      `
      productsContainer.appendChild(productElement)
    })

    // Ajouter le conteneur de produits à la liste
    productList.appendChild(productsContainer)

    // Ajouter la pagination si elle est fournie
    if (pagination) {
      const paginationContainer = createPaginationControls(pagination)
      productList.appendChild(paginationContainer)
    }

    // Ajouter les écouteurs d'événements pour les produits
    addProductClickListeners()
  }

  // Fonction pour ajouter les écouteurs d'événements aux produits
  function addProductClickListeners() {
    const productItems = document.querySelectorAll(".product-item")
    productItems.forEach((item) => {
      item.addEventListener("click", function (e) {
        e.preventDefault()

        // Récupérer les données du produit
        const productId = this.getAttribute("data-id")
        const productName = this.getAttribute("data-name")
        const productPrice = this.getAttribute("data-price")
        const productImage = this.getAttribute("data-image")
        const productDescription = this.getAttribute("data-description")
        const productCategory = this.getAttribute("data-category")
        const productQuantity = Number.parseInt(this.getAttribute("data-quantity"))
        const isOutOfStock = this.getAttribute("data-out-of-stock") === "true"

        // Enregistrer le clic dans la base de données (même pour les produits en rupture)
        trackProductClick(productCategory)

        // Afficher le popup avec les informations de stock
        showProductPopup(
          productId,
          productName,
          productPrice,
          productImage,
          productDescription,
          productQuantity,
          isOutOfStock,
        )
      })
    })
  }

  // Fonction pour afficher le popup avec gestion de stock
  function showProductPopup(id, name, price, image, description, quantity = 0, isOutOfStock = false) {
    // Créer le popup s'il n'existe pas déjà
    let popup = document.getElementById("product-popup")
    if (!popup) {
      popup = document.createElement("div")
      popup.id = "product-popup"
      popup.className = "product-popup-overlay"
      document.body.appendChild(popup)
    }

    // Déterminer le statut du stock
    const isLowStock = quantity > 0 && quantity <= 5

    let stockInfo = ""
    let addToCartButton = ""

    if (isOutOfStock) {
      stockInfo = `
        <div class="stock-info out-of-stock">
          <span class="stock-icon">⚠️</span>
          <span class="stock-text">Rupture du stock - Produit temporairement indisponible</span>
        </div>
      `
      addToCartButton = `
        <button class="btn btn-secondary" disabled>
          <i class="fas fa-ban"></i> Produit indisponible
        </button>
      `
    } else if (isLowStock) {
      stockInfo = `
        <div class="stock-info low-stock">
          <span class="stock-icon">⚠️</span>
          <span class="stock-text">Stock faible - Plus que ${quantity} disponible(s)</span>
        </div>
      `
      addToCartButton = `
        <button class="btn btn-warning add-to-cart-btn" data-id="${id}" data-name="${name}" data-price="${price}" data-image="${image}">
          <i class="fas fa-shopping-cart"></i> Ajouter au panier (Dépêchez-vous!)
        </button>
      `
    } else {
      stockInfo = `
        <div class="stock-info in-stock">
          <span class="stock-icon">✅</span>
          <span class="stock-text">En stock - ${quantity} disponible(s)</span>
        </div>
      `
      addToCartButton = `
        <button class="btn btn-primary add-to-cart-btn" data-id="${id}" data-name="${name}" data-price="${price}" data-image="${image}">
          <i class="fas fa-shopping-cart"></i> Ajouter au panier
        </button>
      `
    }

    // Remplir le popup avec les informations du produit
    popup.innerHTML = `
      <div class="product-popup-content">
        <div class="product-popup-header">
          <h3>${name}</h3>
          <button class="close-popup">&times;</button>
        </div>
        <div class="product-popup-body">
          <div class="product-popup-image">
            <img src="${image}" alt="${name}" class="img-fluid ${isOutOfStock ? "grayscale" : ""}" style="mix-blend-mode: multiply;">
          </div>
          <div class="product-popup-info">
            <p class="product-popup-price">${price} DH</p>
            <p class="product-popup-description">${description}</p>
            ${stockInfo}
          </div>
        </div>
        <div class="product-popup-footer">
          ${addToCartButton}
          <button class="btn btn-secondary return-btn">
            <i class="fas fa-arrow-left"></i> Retour
          </button>
        </div>
      </div>
    `

    // Afficher le popup
    popup.style.display = "flex"

    // Ajouter les écouteurs d'événements pour les boutons du popup
    const closeButton = popup.querySelector(".close-popup")
    const returnButton = popup.querySelector(".return-btn")
    const addToCartBtn = popup.querySelector(".add-to-cart-btn")

    // Supprimer les anciens écouteurs d'événements
    closeButton.replaceWith(closeButton.cloneNode(true))
    returnButton.replaceWith(returnButton.cloneNode(true))
    
    // Récupérer les nouveaux éléments après le clonage
    const newCloseButton = popup.querySelector(".close-popup")
    const newReturnButton = popup.querySelector(".return-btn")
    
    // Ajouter les écouteurs d'événements
    newCloseButton.addEventListener("click", closePopup)
    newReturnButton.addEventListener("click", closePopup)

    if (addToCartBtn && !isOutOfStock) {
      // Cloner le bouton pour supprimer les anciens écouteurs
      const newAddToCartBtn = addToCartBtn.cloneNode(true)
      addToCartBtn.replaceWith(newAddToCartBtn)
      
      // Ajouter le nouvel écouteur d'événement
      newAddToCartBtn.addEventListener("click", (e) => {
        e.stopPropagation() // Empêcher la propagation de l'événement
        addToCart(id, name, price, image, quantity)
      })
    }

    // Fermer le popup si on clique en dehors
    popup.addEventListener("click", (e) => {
      if (e.target === popup) {
        closePopup()
      }
    })
  }

  // Fonction pour mettre à jour le compteur du panier
  function updateCartCount() {
    fetch("http://127.0.0.1:8000/api/cart/count", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      credentials: "include"
    })
    .then(response => response.json())
    .then(data => {
      const cartBadge = document.getElementById('cart-badge');
      if (cartBadge) {
        if (data.count > 0) {
          cartBadge.textContent = data.count;
          cartBadge.style.display = 'block';
        } else {
          cartBadge.style.display = 'none';
        }
      }
    })
    .catch(error => console.error('Erreur lors de la mise à jour du panier:', error));
  }

  // Fonction pour ajouter au panier avec vérification de stock
  function addToCart(productId, productName, productPrice, productImage, productQuantity = 0) {
    // Vérifier si l'utilisateur est connecté
    if (!window.sessionId) {
      showNotification("Veuillez vous connecter pour ajouter des produits au panier", "error")
      return
    }

    // Vérifier si le produit est en stock
    if (productQuantity <= 0) {
      showNotification("Ce produit n'est plus disponible en stock", "error")
      return
    }

    // Récupérer le token CSRF
    const csrfToken = getCSRFToken()

    // Envoyer la requête AJAX
    fetch("/api/cart/add", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify({
        produit_id: productId,
        nom_produit: productName,
        prix: productPrice,
        image: productImage,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showNotification(data.message, "success")
          updateCartCount()
          closePopup()
        } else {
          // Gérer le cas spécifique où le vendeur est bloqué
          if (data.message && data.message.includes("vendeur de ce produit a été bloqué")) {
            showNotification("Le vendeur de ce produit a été bloqué par l'administrateur. Le produit n'est plus disponible.", "error")
          } else {
            showNotification(data.message || "Erreur lors de l'ajout au panier", "error")
          }
        }
      })
      .catch((error) => {
        console.error("Erreur lors de l'ajout au panier:", error)
        showNotification("Erreur lors de l'ajout au panier", "error")
      })
  }

  // Fonction pour enregistrer le clic sur un produit
  function trackProductClick(category) {
    const csrfToken = getCSRFToken()
    const clickData = {
      categorie: category,
      client_id: window.sessionId || 0,
    }

    console.log("Enregistrement du clic:", clickData)

    fetch("http://127.0.0.1:8000/api/compteurs/track", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
        "X-Requested-With": "XMLHttpRequest",
      },
      credentials: "include",
      body: JSON.stringify(clickData),
    })
      .then((response) => {
        console.log("Statut de la réponse:", response.status)
        if (!response.ok) {
          return response.text().then((text) => {
            console.error("Erreur détaillée:", text)
            throw new Error("Erreur lors de l'enregistrement du clic (Statut: " + response.status + ")")
          })
        }
        return response.json()
      })
      .then((data) => {
        console.log("Clic enregistré avec succès:", data)
      })
      .catch((error) => {
        console.error("Erreur lors de l'enregistrement du clic:", error)
      })
  }

  // Fonction pour récupérer le token CSRF
  function getCSRFToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]')
    if (metaToken) {
      return metaToken.getAttribute("content")
    }

    const tokenInput = document.querySelector('input[name="_token"]')
    if (tokenInput) {
      return tokenInput.value
    }

    return ""
  }

  // Fonction pour fermer le popup
  function closePopup() {
    const popup = document.getElementById("product-popup")
    if (popup) {
      popup.style.display = "none"
    }
  }

  // Fonction pour créer les contrôles de pagination
  function createPaginationControls(pagination) {
    const { currentPage, lastPage, total } = pagination

    const paginationContainer = document.createElement("div")
    paginationContainer.className = "d-flex justify-content-center mt-4 w-100 product-section"

    const paginationNav = document.createElement("nav")
    paginationNav.setAttribute("aria-label", "Navigation des pages")

    const paginationList = document.createElement("ul")
    paginationList.className = "pagination"

    // Bouton "Précédent"
    const prevItem = document.createElement("li")
    prevItem.className = `page-item ${currentPage === 1 ? "disabled" : ""}`

    const prevLink = document.createElement("a")
    prevLink.className = "page-link"
    prevLink.href = "#"
    prevLink.setAttribute("aria-label", "Précédent")
    prevLink.innerHTML = '<span aria-hidden="true">&laquo;</span>'

    if (currentPage > 1) {
      prevLink.addEventListener("click", (e) => {
        e.preventDefault()
        loadPage(currentPage - 1)
      })
    }

    prevItem.appendChild(prevLink)
    paginationList.appendChild(prevItem)

    // Pages numérotées
    const startPage = Math.max(1, currentPage - 2)
    const endPage = Math.min(lastPage, currentPage + 2)

    for (let i = startPage; i <= endPage; i++) {
      const pageItem = document.createElement("li")
      pageItem.className = `page-item ${i === currentPage ? "active" : ""}`

      const pageLink = document.createElement("a")
      pageLink.className = "page-link"
      pageLink.href = "#"
      pageLink.textContent = i

      if (i !== currentPage) {
        pageLink.addEventListener("click", (e) => {
          e.preventDefault()
          loadPage(i)
        })
      }

      pageItem.appendChild(pageLink)
      paginationList.appendChild(pageItem)
    }

    // Bouton "Suivant"
    const nextItem = document.createElement("li")
    nextItem.className = `page-item ${currentPage === lastPage ? "disabled" : ""}`

    const nextLink = document.createElement("a")
    nextLink.className = "page-link"
    nextLink.href = "#"
    nextLink.setAttribute("aria-label", "Suivant")
    nextLink.innerHTML = '<span aria-hidden="true">&raquo;</span>'

    if (currentPage < lastPage) {
      nextLink.addEventListener("click", (e) => {
        e.preventDefault()
        loadPage(currentPage + 1)
      })
    }

    nextItem.appendChild(nextLink)
    paginationList.appendChild(nextItem)

    paginationNav.appendChild(paginationList)
    paginationContainer.appendChild(paginationNav)

    return paginationContainer
  }

  // Fonction pour afficher une notification
  function showNotification(message, type) {
    const notification = document.createElement("div")
    notification.className = `notification ${type}`
    notification.textContent = message

    document.body.appendChild(notification)

    setTimeout(() => {
      notification.classList.add("fade-out")
      setTimeout(() => {
        document.body.removeChild(notification)
      }, 500)
    }, 3000)
  }

  // Fonction pour charger une page spécifique
  function loadPage(page) {
    getProducts(page).then(({ products, pagination }) => {
      displayProducts(products, pagination)
      window.scrollTo({ top: 0, behavior: "smooth" })
    })
  }

  // INITIALISATION CORRIGÉE
  getProducts().then(({ products, pagination }) => {
    displayProducts(products, pagination)
    
    // Mettre à jour le compteur du panier au chargement de la page
    updateCartCount();

    // Variable pour stocker tous les produits (pour la recherche et le filtrage)
    let allProducts = products

    // Gestion de la recherche CORRIGÉE
    const searchInput = document.getElementById("search")
    if (searchInput) {
      searchInput.addEventListener("input", () => {
        const searchTerm = searchInput.value.toLowerCase()
        const filteredProducts = allProducts.filter((product) => product.nom.toLowerCase().includes(searchTerm))
        displayProducts(filteredProducts) // Pas de pagination pour la recherche
      })
    }

    // Gestion du filtrage CORRIGÉE
    const filterSelect = document.getElementById("filtrer")
    if (filterSelect) {
      filterSelect.addEventListener("change", () => {
        const filterValue = filterSelect.value
        let filteredProducts = [...allProducts] // Copie du tableau

        if (filterValue === "prix") {
          // Tri par prix croissant
          filteredProducts.sort((a, b) => Number.parseFloat(a.prix_unitaire) - Number.parseFloat(b.prix_unitaire))
        } else if (filterValue === "categorie") {
          // Filtrage par catégorie
          const categorySelect = document.getElementById("categorie")
          const selectedCategory = categorySelect ? categorySelect.value : ""
          if (selectedCategory) {
            filteredProducts = allProducts.filter(
              (product) => (product.categorie || "").toLowerCase() === selectedCategory.toLowerCase(),
            )
          }
        } else if (filterValue === "stock") {
          // Tri par stock (en stock d'abord, puis rupture)
          filteredProducts.sort((a, b) => {
            const qtyA = getProductQuantity(a)
            const qtyB = getProductQuantity(b)
            if (qtyA === 0 && qtyB > 0) return 1
            if (qtyA > 0 && qtyB === 0) return -1
            return qtyB - qtyA // Stock décroissant
          })
        }

        displayProducts(filteredProducts) // Pas de pagination pour le filtrage
      })
    }

    // Gestion de la sélection de catégorie CORRIGÉE
    const categorySelect = document.getElementById("categorie")
    if (filterSelect && categorySelect) {
      filterSelect.addEventListener("change", () => {
        if (filterSelect.value === "categorie") {
          categorySelect.style.display = "block"
          const categoryLabel = document.querySelector('label[for="categorie"]')
          if (categoryLabel) categoryLabel.style.display = "block"
        } else {
          categorySelect.style.display = "none"
          const categoryLabel = document.querySelector('label[for="categorie"]')
          if (categoryLabel) categoryLabel.style.display = "none"
        }
      })

      categorySelect.addEventListener("change", () => {
        const selectedCategory = categorySelect.value
        const filteredProducts = selectedCategory
          ? allProducts.filter((product) => (product.categorie || "").toLowerCase() === selectedCategory.toLowerCase())
          : allProducts
        displayProducts(filteredProducts)
      })
    }

    // Charger tous les produits pour la recherche et le filtrage
    getProducts(1, 1000).then(({ products: allProductsData }) => {
      allProducts = allProductsData
      console.log("Tous les produits chargés:", allProducts.length) // Debug
    })
  })
})
