// Fonction pour mettre à jour le badge du panier
function updateCartBadge() {
    fetch('/cart')
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data) {
          // Calculer le nombre total d'articles (en tenant compte des quantités)
          let totalItems = 0;
          data.data.forEach(item => {
            totalItems += parseInt(item.quantite);
          });
          
          // Mettre à jour le badge
          const badge = document.getElementById('cart-badge');
          if (badge) {
            if (totalItems > 0) {
              badge.textContent = totalItems;
              badge.style.display = 'block';
            } else {
              badge.style.display = 'none';
            }
          }
        }
      })
      .catch(error => console.error('Erreur lors de la récupération du panier:', error));
  }
  
  // Mettre à jour le badge au chargement de la page
  document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
    
    // Intercepter les événements d'ajout au panier pour mettre à jour le badge
    document.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('add-to-cart-btn')) {
        // Attendre un peu pour que l'ajout au panier soit traité
        setTimeout(updateCartBadge, 500);
      }
    });
  });
  
  // Créer un événement personnalisé pour mettre à jour le badge depuis d'autres scripts
  window.updateCartBadgeEvent = new Event('updateCartBadge');
  document.addEventListener('updateCartBadge', updateCartBadge);