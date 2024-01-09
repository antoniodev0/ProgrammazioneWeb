<?php
session_start();
require_once __DIR__ . "/Database.php"; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ricettaId'])) {
    $ricettaId = $_POST['ricettaId'];

    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // Verifica se la ricetta è stata salvata dall'utente
    $stmt = $conn->prepare("SELECT * FROM ricette_preferite WHERE user_id = ? AND ricetta_id = ?");
    $stmt->bind_param("ii", $userId, $ricettaId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Ricetta non trovata tra le preferite']);
        $stmt->close();
        exit;
    }

    // Rimuovi la ricetta dai preferiti
    $stmt = $conn->prepare("DELETE FROM ricette_preferite WHERE user_id = ? AND ricetta_id = ?");
    $stmt->bind_param("ii", $userId, $ricettaId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Ricetta rimossa dai preferiti']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Errore durante la rimozione della ricetta']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Richiesta non valida']);
}
?>
