document.addEventListener("DOMContentLoaded", () => {
  // Fonction pour récupérer les produits depuis l'API
  function getProducts() {
    return fetch("http://127.0.0.1:8000/api/produits")
      .then((response) => response.json())
      .then((data) => {
        if (data.success && Array.isArray(data.data)) {
          return data.data;
        } else {
          console.error("Format de données invalide:", data);
          return [];
        }
      })
      .catch((error) => console.error("Erreur lors de la récupération des produits:", error))
  }

  // Fonction pour afficher les produits
  function displayProducts(productsToDisplay) {
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

    productsToDisplay.forEach((product) => {
      const productElement = document.createElement("div")
      productElement.className = "col-12 col-md-4 col-lg-3 mb-5"
      productElement.innerHTML = `
        <a class="product-item product"
           href="#"
           data-id="${product.id}"
           data-name="${product.nom}"
           data-price="${product.prix_unitaire}"
           data-image="http://127.0.0.1:8000/storage/${product.image}">
          <img src="http://127.0.0.1:8000/storage/${product.image}" class="img-fluid product-thumbnail" style="mix-blend-mode: multiply;">
          <h3 class="product-title">${product.nom}</h3>
          <strong class="product-price">${product.prix_unitaire} DH</strong>
          <span class="icon-cross">
            <img src="../images/cross.svg" class="img-fluid">
          </span>
        </a>
      `
      productList.appendChild(productElement)
    })

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

        // Afficher le popup
        showProductPopup(productId, productName, productPrice, productImage)
      })
    })
  }

  // Fonction pour afficher le popup
  function showProductPopup(id, name, price, image) {
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

  // Fonction pour ajouter au panier
function addToCart(productId, productName, productPrice, productImage) {
  // Récupérer le token CSRF
  let csrfToken

  // Essayer de récupérer le token depuis le meta tag
  const metaToken = document.querySelector('meta[name="csrf-token"]')
  if (metaToken) {
    csrfToken = metaToken.getAttribute("content")
  } else {
    // Fallback: essayer de récupérer depuis un input hidden
    const tokenInput = document.querySelector('input[name="_token"]')
    if (tokenInput) {
      csrfToken = tokenInput.value
    }
  }

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
    method: "POST", // Assurez-vous que c'est bien POST
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-CSRF-TOKEN": csrfToken || "", // Inclure le token CSRF
    },
    credentials: "same-origin", // Important pour les cookies et l'authentification
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

// Fonction pour fermer le popup
function closePopup() {
  const popup = document.getElementById("product-popup")
  if (popup) {
    popup.style.display = "none"
  }
}


  // Initialisation : récupérer et afficher tous les produits
  getProducts().then((products) => {
    displayProducts(products)

    // Gestion de la recherche
    const searchInput = document.getElementById("search")
    searchInput.addEventListener("input", () => {
      const searchTerm = searchInput.value.toLowerCase()
      const filteredProducts = products.filter((product) => product.nom.toLowerCase().includes(searchTerm))
      displayProducts(filteredProducts)
    })

    // Gestion du filtrage
    const filterSelect = document.getElementById("filtrer")
    filterSelect.addEventListener("change", () => {
      const filterValue = filterSelect.value
      let filteredProducts

      if (filterValue === "prix") {
        filteredProducts = [...products].sort((a, b) => a.prix_unitaire - b.prix_unitaire)
      } else if (filterValue === "categorie") {
        const selectedCategory = document.getElementById("categorie").value
        filteredProducts = selectedCategory
          ? products.filter((product) => product.categorie === selectedCategory)
          : products
      } else {
        filteredProducts = products
      }
      displayProducts(filteredProducts)
    })

    // Gestion de la sélection de catégorie
    const categorySelect = document.getElementById("categorie")
    filterSelect.addEventListener("change", () => {
      if (filterSelect.value === "categorie") {
        categorySelect.style.display = "block"
        document.querySelector('label[for="categorie"]').style.display = "block"
      } else {
        categorySelect.style.display = "none"
        document.querySelector('label[for="categorie"]').style.display = "none"
      }
    })

    categorySelect.addEventListener("change", () => {
      const selectedCategory = categorySelect.value
      const filteredProducts = selectedCategory
        ? products.filter((product) => product.categorie === selectedCategory)
        : products
      displayProducts(filteredProducts)
    })
  })
})
