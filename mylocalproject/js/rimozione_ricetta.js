document.addEventListener('DOMContentLoaded', function() {
    // Per ogni form con classe '.form-rimuovi-preferita'
    document.querySelectorAll('.form-rimuovi-preferita').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Impedisco l'invio predefinito del modulo

            // Creo un oggetto FormData con i dati del modulo attuale
            const formData = new FormData(this);
            // Esegue una richiesta fetch con il metodo POST
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Converte la risposta in formato JSON
            .then(data => {
                if (data.status === 'success') {
                    const ricettaPreferita = form.closest('.ricetta-preferita');
                    ricettaPreferita.style.display = 'none'; // Nascondo la ricetta rimossa

                    mostraMessaggio('Ricetta rimossa dai preferiti', 'successo');
                } else if (data.status === 'error') {
                    mostraMessaggio(data.message, 'errore');
                }
            })
            .catch(error => {
                mostraMessaggio('Errore nella rimozione della ricetta', 'errore');
            });
        });
    });
    // Funzione per mostrare un messaggio temporaneo nella pagina
    function mostraMessaggio(testo, tipo) {
        const messaggio = document.createElement('div');
        messaggio.textContent = testo;
        messaggio.classList.add('messaggio', tipo);
        document.body.appendChild(messaggio);

        setTimeout(() => {
            messaggio.remove();
        }, 1000);
    }
});
