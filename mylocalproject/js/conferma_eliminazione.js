// Attendo il caricamento completo del documento
document.addEventListener("DOMContentLoaded", function() {
    // Ottengo il riferimento al bottone di conferma eliminazione tramite ID
    const confermaEliminazioneBtn = document.getElementById('conferma-eliminazione');
    // Aggiungo un ascoltatore di eventi al click del bottone
    confermaEliminazioneBtn.addEventListener('click', function() {
        // Apro una finestra di dialogo per chiedere conferma all'utente
        const conferma = confirm("Sicuro di voler procedere all'eliminazione dell'account?");
        // Verifico se l'utente ha confermato l'eliminazione
        if (conferma) {
            // Se confermato, si procede con l'eliminazione dell'account
            document.getElementById('eliminazione-account-form').submit();
        } else {
            // L'utente ha annullato l'eliminazione dell'account
            console.log("L'utente ha annullato l'eliminazione dell'account.");
        }
    });
});