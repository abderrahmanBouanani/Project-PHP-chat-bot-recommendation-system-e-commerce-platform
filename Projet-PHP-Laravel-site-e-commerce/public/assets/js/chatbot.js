// Vérifier si le meta tag client-id existe, sinon le créer
if (!document.querySelector('meta[name="client-id"]')) {
  const meta = document.createElement('meta');
  meta.name = 'client-id';
  meta.content = '{{ Auth::check() ? Auth::id() : null }}';
  document.head.appendChild(meta);
}

// Chatbot functionality
const chatbotToggle = document.createElement("div")
chatbotToggle.className = "chatbot-toggle"
chatbotToggle.innerHTML = '<i class="fas fa-comment-dots"></i>'

document.body.appendChild(chatbotToggle)

// Create chatbot container
const chatbotContainer = document.createElement("div")
chatbotContainer.className = "chatbot-container"
chatbotContainer.innerHTML = `
    <div class="chatbot-header">
        <h3>Assistant Virtuel</h3>
        <button class="chatbot-close">&times;</button>
    </div>
    <div class="chatbot-messages"></div>
    <div class="chatbot-input">
        <input type="text" placeholder="Posez votre question...">
        <button><i class="fas fa-paper-plane"></i></button>
    </div>
`

document.body.appendChild(chatbotContainer)

// Toggle chatbot visibility
chatbotToggle.addEventListener("click", () => {
  chatbotContainer.style.display = chatbotContainer.style.display === "block" ? "none" : "block"
})

document.querySelector(".chatbot-close").addEventListener("click", () => {
  chatbotContainer.style.display = "none"
})

// Handle sending messages
const inputField = document.querySelector(".chatbot-input input")
const sendButton = document.querySelector(".chatbot-input button")
const messagesContainer = document.querySelector(".chatbot-messages")

function addMessage(message, isUser = false) {
  const messageElement = document.createElement("div")
  messageElement.className = isUser ? "user-message" : "bot-message"
  messageElement.textContent = message
  messagesContainer.appendChild(messageElement)
  messagesContainer.scrollTop = messagesContainer.scrollHeight
}

// Ajoute des cartes produits dans le chatbot
function addProductCards(produits) {
  const cardsContainer = document.createElement("div")
  cardsContainer.className = "product-cards"
  
  produits.forEach(produit => {
    const card = document.createElement("div")
    card.className = "product-card"
    
    // Create product image element if available
    const imageUrl = produit.image ? 
      `/storage/${produit.image}` : 
      '/assets/img/placeholder-product.png'
    
    card.innerHTML = `
      <div class="product-image">
        <img src="${imageUrl}" alt="${produit.nom}" onerror="this.src='/assets/img/placeholder-product.png'">
      </div>
      <div class="product-details">
        <h4>${produit.nom}</h4>
        <p class="product-price">${produit.prix} DH</p>
        <p class="product-category">${produit.categorie || 'Non spécifiée'}</p>
      </div>
    `
    
    // Add click event to redirect to product page
    card.addEventListener('click', () => {
      window.location.href = `/produit/${produit.id}`
    })
    
    cardsContainer.appendChild(card)
  })
  
  messagesContainer.appendChild(cardsContainer)
  messagesContainer.scrollTop = messagesContainer.scrollHeight
}

function sendMessage() {
  const message = inputField.value.trim()
  if (message) {
    addMessage(message, true)
    inputField.value = ""
    inputField.disabled = true
    sendButton.disabled = true

    // Add loading indicator
    const loadingElement = document.createElement("div")
    loadingElement.className = "bot-message"
    loadingElement.id = "typing-indicator"
    loadingElement.innerHTML = `
      <div class="typing-indicator">
        <span></span>
        <span></span>
        <span></span>
      </div>
    `
    messagesContainer.appendChild(loadingElement)
    messagesContainer.scrollTop = messagesContainer.scrollHeight

    // Récupérer l'ID du client connecté depuis la balise meta
    const clientId = document.querySelector('meta[name="client-id"]')?.content || null;
    
    // Send to Flask API
    fetch("http://localhost:5000/ask", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ 
        message: message,
        client_id: clientId
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        // Remove loading indicator
        const typingIndicator = document.getElementById("typing-indicator")
        if (typingIndicator) {
          messagesContainer.removeChild(typingIndicator)
        }

        if (data.status === "intent") {
          addMessage(data.response)
          if (data.response.includes("Au revoir")) {
            setTimeout(() => {
              chatbotContainer.style.display = "none"
            }, 2000)
          }
        } else if (data.status === "ok") {
          if (data.produits && data.produits.length > 0) {
            // Only show the message if it's not the default one
            if (data.message && !data.message.includes("produits populaires")) {
              addMessage(data.message)
            }
            addProductCards(data.produits)
          } else {
            addMessage("Je n'ai pas trouvé de produits correspondant à votre recherche.")
          }
        } else if (data.status === "vide") {
          addMessage(data.message || "Aucun produit trouvé pour cette catégorie.")
        } else {
          addMessage(data.message || "Désolé, je n'ai pas pu traiter votre demande.")
        }
        
        // Re-enable input field and button
        inputField.disabled = false
        sendButton.disabled = false
        inputField.focus()
      })
      .catch((error) => {
        // Remove loading indicator
        const typingIndicator = document.getElementById("typing-indicator")
        if (typingIndicator) {
          messagesContainer.removeChild(typingIndicator)
        }

        console.error("Error:", error)
        addMessage("Une erreur est survenue lors de la communication avec le chatbot.")
        
        // Re-enable input field and button on error
        inputField.disabled = false
        sendButton.disabled = false
        inputField.focus()
      })
  }
}

sendButton.addEventListener("click", sendMessage)
inputField.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    sendMessage()
  }
})

// Add welcome message when chatbot is opened
chatbotToggle.addEventListener("click", () => {
  if (messagesContainer.children.length === 0) {
    addMessage("Bonjour ! Comment puis-je vous aider aujourd'hui ?")
  }
})

// Add CSS for typing indicator
const style = document.createElement("style")
style.textContent = `
.typing-indicator {
    display: flex;
    align-items: center;
    padding: 5px 0;
}

.typing-indicator span {
    height: 8px;
    width: 8px;
    margin: 0 2px;
    background-color: #2f4f4f;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.4;
}

.typing-indicator span:nth-child(1) {
    animation: pulse 1s infinite 0.1s;
}

.typing-indicator span:nth-child(2) {
    animation: pulse 1s infinite 0.3s;
}

.typing-indicator span:nth-child(3) {
    animation: pulse 1s infinite 0.5s;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.4;
    }
    50% {
        transform: scale(1.2);
        opacity: 1;
        background-color: #ffd700;
    }
}
`
document.head.appendChild(style)
