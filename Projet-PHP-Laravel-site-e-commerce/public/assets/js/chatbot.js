// Chatbot functionality
const chatbotToggle = document.createElement('div');
chatbotToggle.className = 'chatbot-toggle';
chatbotToggle.innerHTML = '<i class="fas fa-comment-dots"></i>';

document.body.appendChild(chatbotToggle);

// Create chatbot container
const chatbotContainer = document.createElement('div');
chatbotContainer.className = 'chatbot-container';
chatbotContainer.innerHTML = `
    <div class="chatbot-header">
        <h3>Assistant Virtuel</h3>
        <button class="chatbot-close">&times;</button>
    </div>
    <div class="chatbot-messages"></div>
    <div class="chatbot-input">
        <input type="text" placeholder="Posez votre question...">
        <button>Envoyer</button>
    </div>
`;

document.body.appendChild(chatbotContainer);

// Toggle chatbot visibility
chatbotToggle.addEventListener('click', () => {
    chatbotContainer.style.display = chatbotContainer.style.display === 'block' ? 'none' : 'block';
});

document.querySelector('.chatbot-close').addEventListener('click', () => {
    chatbotContainer.style.display = 'none';
});

// Handle sending messages
const inputField = document.querySelector('.chatbot-input input');
const sendButton = document.querySelector('.chatbot-input button');
const messagesContainer = document.querySelector('.chatbot-messages');

function addMessage(message, isUser = false) {
    const messageElement = document.createElement('div');
    messageElement.className = isUser ? 'user-message' : 'bot-message';
    messageElement.textContent = message;
    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function sendMessage() {
    const message = inputField.value.trim();
    if (message) {
        addMessage(message, true);
        inputField.value = '';
        
        // Send to Flask API
        fetch('http://localhost:5000/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                if (data.produits && data.produits.length > 0) {
                    let response = 'Voici les produits que j\'ai trouvés :\n';
                    data.produits.forEach(prod => {
                        response += `- ${prod.nom} (${prod.prix}€)\n`;
                    });
                    addMessage(response);
                } else {
                    addMessage('Je n\'ai pas trouvé de produits correspondant à votre recherche.');
                }
            } else {
                addMessage(data.message || 'Désolé, je n\'ai pas pu traiter votre demande.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('Une erreur est survenue lors de la communication avec le chatbot.');
        });
    }
}

sendButton.addEventListener('click', sendMessage);
inputField.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Add welcome message when chatbot is opened
chatbotToggle.addEventListener('click', () => {
    if (messagesContainer.children.length === 0) {
        addMessage('Bonjour ! Comment puis-je vous aider aujourd\'hui ?');
    }
}); 