document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("login-form");
    const loginErrors = document.getElementById("login-errors");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Impedisco l'invio predefinito del modulo
        
        // Creo un oggetto FormData con i dati del modulo attuale
        const formData = new FormData(this);
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                window.location.href = "../mylocalproject/php/Dashboard_utente.php"; // Reindirizza in caso di successo
            } else {
                loginErrors.textContent = data.message; // Mostra errore
            }
        })
        .catch(error => {
            console.error('Errore:', error);
        });
    });
});
