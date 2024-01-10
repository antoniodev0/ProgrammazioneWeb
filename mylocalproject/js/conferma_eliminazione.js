document.addEventListener("DOMContentLoaded", function() {
    const confermaEliminazioneBtn = document.getElementById('conferma-eliminazione');
    confermaEliminazioneBtn.addEventListener('click', function() {
        const conferma = confirm("Sicuro di voler procedere all'eliminazione dell'account?");
        if (conferma) {
            deleteAccount();
        } else {
            // L'utente ha annullato l'eliminazione dell'account
            console.log("L'utente ha annullato l'eliminazione dell'account.");
        }
    });

    function deleteAccount() {
        fetch('../php/eliminazione_account.php', {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                // Effettua il logout 
                window.location.href = "../php/logout.php";
            } 
        })
    }
});