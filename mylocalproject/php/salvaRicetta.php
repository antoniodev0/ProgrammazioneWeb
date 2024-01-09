<?php
session_start();
require_once __DIR__ . "/Database.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ricettaId = $_POST['ricettaId'];

    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // Controlla se la ricetta è già stata salvata dall'utente
    $stmt = $conn->prepare("SELECT * FROM ricette_preferite WHERE user_id = ? AND ricetta_id = ?");
    $stmt->bind_param("ii", $userId, $ricettaId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Ricetta già salvata']);
        $stmt->close();
        exit;
    }

    // Salva la ricetta come preferita
    $stmt = $conn->prepare("INSERT INTO ricette_preferite (user_id, ricetta_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $ricettaId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Ricetta salvata con successo']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Errore nel salvataggio della ricetta']);
    }

    $stmt->close();
}
?>
