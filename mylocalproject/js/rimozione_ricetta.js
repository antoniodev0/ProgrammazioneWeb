document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-rimuovi-preferita').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const ricettaPreferita = form.closest('.ricetta-preferita');
                    ricettaPreferita.style.display = 'none'; // Nascondi la ricetta rimossa

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