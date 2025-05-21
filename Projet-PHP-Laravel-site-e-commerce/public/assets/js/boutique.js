/**
 * Boutique.js - Script principal pour la boutique en ligne
 * Gère l'affichage des produits, le suivi des clics et les interactions utilisateur
 */
document.addEventListener("DOMContentLoaded", () => {
  // Fonction pour récupérer les produits depuis l'API avec pagination
  function getProducts(page = 1, limit = 8) {
    return fetch(`http://127.0.0.1:8000/api/produits?page=${page}&limit=${limit}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Retourner à la fois les données et les informations de pagination
          return {
            products: data.data || [],
            pagination: {
              total: data.total || 0,
              currentPage: data.current_page || 1,
              lastPage: data.last_page || 1,
              perPage: data.per_page || 8
            }
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

  // Fonction pour afficher les produits
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
      productElement.innerHTML = `
        <a class="product-item product"
           href="#"
           data-id="${product.id}"
           data-name="${product.nom}"
           data-description="${product.description}"
           data-price="${product.prix_unitaire}"
           data-category="${product.categorie}"
           data-image="http://127.0.0.1:8000/storage/${product.image}">
          <img src="http://127.0.0.1:8000/storage/${product.image}" class="img-fluid product-thumbnail" style="mix-blend-mode: multiply;">
          <h3 class="product-title">${product.nom}</h3>
          <strong class="product-price">${product.prix_unitaire} DH</strong>
          <span class="icon-cross">
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

        // Enregistrer le clic dans la base de données
        trackProductClick(productCategory)

        // Afficher le popup
        showProductPopup(productId, productName, productPrice, productImage, productDescription)
      })
    })
  }

  // Fonction pour enregistrer le clic sur un produit
  function trackProductClick(category) {
    // Récupérer le token CSRF
    const csrfToken = getCSRFToken()

    // Préparer les données à envoyer
    const clickData = {
      categorie: category,
      client_id: window.sessionId || 0, // Ajout de l'id de session réel
    }

    console.log("Enregistrement du clic:", clickData)

    // Envoyer les données au serveur
    fetch("http://127.0.0.1:8000/api/compteurs/track", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
        "X-Requested-With": "XMLHttpRequest",
      },
      credentials: "include", // Important pour inclure les cookies de session
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
    // Essayer de récupérer le token depuis le meta tag
    const metaToken = document.querySelector('meta[name="csrf-token"]')
    if (metaToken) {
      return metaToken.getAttribute("content")
    }

    // Fallback: essayer de récupérer depuis un input hidden
    const tokenInput = document.querySelector('input[name="_token"]')
    if (tokenInput) {
      return tokenInput.value
    }

    return ""
  }

  // Fonction pour afficher le popup
  function showProductPopup(id, name, price, image, description) {
    // Créer le popup s'il n'existe pas déjà
    let popup = document.getElementById("product-popup")
    if (!popup) {
      popup = document.createElement("div")
      popup.id = "product-popup"
      popup.className = "product-popup-overlay"
      document.body.appendChild(popup)
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
            <img src="${image}" alt="${name}" class="img-fluid" style="mix-blend-mode: multiply;">
          </div>
          <div class="product-popup-info">
            <p class="product-popup-price">${price} DH</p>
            <p class="product-popup-description">${description}</p>
          </div>
        </div>
        <div class="product-popup-footer">
          <button class="btn btn-primary add-to-cart-btn" data-id="${id}" data-name="${name}" data-price="${price}" data-image="${image}">
            Ajouter au panier
          </button>
          <button class="btn btn-secondary return-btn">Retour</button>
        </div>
      </div>
    `

    // Afficher le popup
    popup.style.display = "flex"

    // Ajouter les écouteurs d'événements pour les boutons du popup
    const closeButton = popup.querySelector(".close-popup")
    const returnButton = popup.querySelector(".return-btn")
    const addToCartButton = popup.querySelector(".add-to-cart-btn")

    closeButton.addEventListener("click", closePopup)
    returnButton.addEventListener("click", closePopup)
    addToCartButton.addEventListener("click", () => {
      addToCart(id, name, price, image)
    })

    // Fermer le popup si on clique en dehors
    popup.addEventListener("click", (e) => {
      if (e.target === popup) {
        closePopup()
      }
    })
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
    prevItem.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`

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
      pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`

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
    nextItem.className = `page-item ${currentPage === lastPage ? 'disabled' : ''}`

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

  // Fonction pour ajouter au panier
  function addToCart(productId, productName, productPrice, productImage) {
    // Récupérer le token CSRF
    const csrfToken = getCSRFToken()

    // Extraire juste le nom du fichier de l'URL complète
    let imageFileName = productImage
    if (productImage.includes("/")) {
      imageFileName = productImage.split("/").pop()
    }

    // Préparer les données à envoyer
    const cartData = {
      produit_id: productId,
      nom_produit: productName,
      image: imageFileName,
      prix: productPrice,
    }

    console.log("Envoi de la requête à:", "http://127.0.0.1:8000/api/cart/add")
    console.log("Méthode:", "POST")
    console.log("Données envoyées:", cartData)

    // Envoyer les données au serveur
    fetch("http://127.0.0.1:8000/api/cart/add", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      credentials: "include", // Important pour les cookies et l'authentification
      body: JSON.stringify(cartData),
    })
      .then((response) => {
        console.log("Statut de la réponse:", response.status)
        if (!response.ok) {
          return response.text().then((text) => {
            console.error("Erreur détaillée:", text)
            throw new Error("Erreur lors de l'ajout au panier (Statut: " + response.status + ")")
          })
        }
        return response.json()
      })
      .then((data) => {
        console.log("Réponse du serveur:", data)
        // Afficher un message de succès
        showNotification("Produit ajouté au panier avec succès!", "success")

        // Fermer le popup
        closePopup()
      })
      .catch((error) => {
        console.error("Erreur:", error)
        showNotification("Erreur lors de l'ajout au panier", "error")
      })
  }

  // Fonction pour afficher une notification
  function showNotification(message, type) {
    const notification = document.createElement("div")
    notification.className = `notification ${type}`
    notification.textContent = message

    document.body.appendChild(notification)

    // Faire disparaître la notification après 3 secondes
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

      // Faire défiler vers le haut de la page
      window.scrollTo({ top: 0, behavior: 'smooth' })
    })
  }

  // Initialisation : récupérer et afficher tous les produits
  getProducts().then(({ products, pagination }) => {
    displayProducts(products, pagination)

    // Variable pour stocker tous les produits (pour la recherche et le filtrage)
    let allProducts = products

    // Gestion de la recherche
    const searchInput = document.getElementById("search")
    if (searchInput) {
      searchInput.addEventListener("input", () => {
        const searchTerm = searchInput.value.toLowerCase()
        const filteredProducts = allProducts.filter((product) => product.nom.toLowerCase().includes(searchTerm))
        displayProducts(filteredProducts)
      })
    }

    // Gestion du filtrage
    const filterSelect = document.getElementById("filtrer")
    if (filterSelect) {
      filterSelect.addEventListener("change", () => {
        const filterValue = filterSelect.value
        let filteredProducts

        if (filterValue === "prix") {
          filteredProducts = [...allProducts].sort((a, b) => a.prix_unitaire - b.prix_unitaire)
        } else if (filterValue === "categorie") {
          const selectedCategory = document.getElementById("categorie").value
          filteredProducts = selectedCategory
            ? allProducts.filter((product) => product.categorie === selectedCategory)
            : allProducts
        } else {
          filteredProducts = allProducts
        }
        displayProducts(filteredProducts)
      })
    }

    // Gestion de la sélection de catégorie
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
          ? allProducts.filter((product) => product.categorie === selectedCategory)
          : allProducts
        displayProducts(filteredProducts)
      })
    }

    // Charger tous les produits pour la recherche et le filtrage
    getProducts(1, 1000).then(({ products }) => {
      allProducts = products
    })
  })
})
