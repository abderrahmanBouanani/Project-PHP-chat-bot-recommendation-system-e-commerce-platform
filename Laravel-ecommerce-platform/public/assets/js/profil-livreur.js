document.addEventListener("DOMContentLoaded", () => {
    const editProfileBtn = document.getElementById("editProfileBtn");
    const cancelEditBtn = document.getElementById("cancelEditBtn");
    const editProfileForm = document.getElementById("editProfileForm");
    const userInfoDisplay = document.getElementById("userInfoDisplay");

    if (editProfileBtn) {
        editProfileBtn.addEventListener("click", () => {
            editProfileForm.style.display = 'block';
            userInfoDisplay.style.display = 'none';
            editProfileBtn.style.display = 'none';
        });
    }

    if (cancelEditBtn) {
        cancelEditBtn.addEventListener("click", () => {
            editProfileForm.style.display = 'none';
            userInfoDisplay.style.display = 'block';
            editProfileBtn.style.display = 'block';
        });
    }

    if (editProfileForm) {
        editProfileForm.addEventListener("submit", (e) => {
            e.preventDefault();
            
            const formData = new FormData(editProfileForm);
            
            fetch('/livreur/update-profile', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Profil mis à jour avec succès');
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de la mise à jour du profil');
            });
        });
    }
});

