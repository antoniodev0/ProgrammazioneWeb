<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'Database.php';

    
    // Ottiengo l'ID dell'utente attualmente loggato
    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        // Elimino i record correlati dalla tabella 'ricette_preferite'
        $delete_ricette = $conn->prepare("DELETE FROM ricette_preferite WHERE user_id = ?");
        $delete_ricette->bind_param("i", $user_id);
        $delete_ricette->execute();
        $delete_ricette->close();

        // Elimino l'utente dalla tabella 'utenti'
        $delete_user = $conn->prepare("DELETE FROM utenti WHERE id = ?");
        $delete_user->bind_param("i", $user_id);
        $delete_user->execute();
        $delete_user->close();

        // Reindirizzo alla pagina di login dopo l'eliminazione dell'account
        header("Location: logout.php");
        exit();
    } 

    $stmt->close();
}
?>

