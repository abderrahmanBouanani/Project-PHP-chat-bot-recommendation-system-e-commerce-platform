document.getElementById('filtrer').addEventListener('change', function() {
    const categorieSelect = document.getElementById('categorie');
    const categorieLabel = document.querySelector('label[for="categorie"]');
    
    if (this.value === 'categorie') {
      categorieSelect.style.display = 'block';
      categorieLabel.style.display = 'block';
      setTimeout(() => {
        categorieSelect.classList.add('show');
      }, 10);
    } else {
      categorieSelect.classList.remove('show');
      setTimeout(() => {
        categorieSelect.style.display = 'none';
        categorieLabel.style.display = 'none';
      }, 500);
    }
  });