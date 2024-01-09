document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-salva-ricetta').forEach(function(form) {
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
                    mostraMessaggio('Ricetta salvata con successo', 'successo');
                } else if (data.status === 'error') {
                    mostraMessaggio(data.message, 'errore');
                }
            })
            .catch(error => {
                mostraMessaggio('Errore nel salvataggio della ricetta', 'errore');
            });
        });
    });
});

function mostraMessaggio(messaggio, tipo) {
    const messaggioDiv = document.createElement('div');
    messaggioDiv.textContent = messaggio;
    messaggioDiv.className = tipo === 'successo' ? 'messaggio-successo' : 'messaggio-errore';
    document.body.appendChild(messaggioDiv);
    setTimeout(() => {
        messaggioDiv.remove();
    }, 1000);
}
