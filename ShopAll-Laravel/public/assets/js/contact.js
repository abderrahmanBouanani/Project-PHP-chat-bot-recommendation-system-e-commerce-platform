document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#contact-form");
    if (!form) {
        console.error("Le formulaire contact-form n'a pas été trouvé dans le DOM");
        return;
    }

    const formParent = form.parentElement;
    if (!formParent) {
        console.error("Le parent du formulaire n'a pas été trouvé dans le DOM");
        return;
    }

    const fnameInput = document.querySelector("#fname");
    const lnameInput = document.querySelector("#lname");
    const emailInput = document.querySelector("#email");
    const messageInput = document.querySelector("#message");

    if (!fnameInput || !lnameInput || !emailInput || !messageInput) {
        console.error("Un ou plusieurs champs du formulaire n'ont pas été trouvés dans le DOM");
        return;
    }

    formParent.addEventListener("submit", function (e) {
        e.preventDefault(); // Empêcher l'envoi par défaut du formulaire

        // Vérification des champs
        const errors = [];
        if (!fnameInput.value.trim()) {
            errors.push("Le prénom est obligatoire.");
        }
        if (!lnameInput.value.trim()) {
            errors.push("Le nom est obligatoire.");
        }
        if (!emailInput.value.trim() || !validateEmail(emailInput.value)) {
            errors.push("Une adresse email valide est obligatoire.");
        }
        if (!messageInput.value.trim()) {
            errors.push("Le message est obligatoire.");
        }

        if (errors.length > 0) {
            alert("Veuillez corriger les erreurs suivantes :\n" + errors.join("\n"));
        } else {
            // Simuler un envoi réussi
            alert("Merci de nous avoir contactés, " + fnameInput.value + " !");
            form.reset();
        }
    });

    // Fonction pour valider l'email
    function validateEmail(email) {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(email);
    }
});
