<?php

    include 'Database.php'; 

// Funzione per ottenere l'ID dell'utente dal nome utente
function getUserIdByUsername($conn, $username) {
    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['id'];
}

// Funzione per verificare se una ricetta è già stata salvata come preferita dall'utente
function isRecipeSaved($conn, $userId, $ricettaId) {
    $stmt = $conn->prepare("SELECT * FROM ricette_preferite WHERE user_id = ? AND ricetta_id = ?");
    $stmt->bind_param("ii", $userId, $ricettaId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Funzione per rimuovere una ricetta dai preferiti dell'utente
function removeRecipeFromFavorites($conn, $userId, $ricettaId) {
    $stmt = $conn->prepare("DELETE FROM ricette_preferite WHERE user_id = ? AND ricetta_id = ?");
    $stmt->bind_param("ii", $userId, $ricettaId);
    return $stmt->execute();
}

// Verifica il metodo della richiesta
$method = $_SERVER['REQUEST_METHOD'];

// Endpoint per la rimozione di una ricetta dai preferiti
if ($method === 'POST' && isset($_POST['ricettaId'])) {
    session_start();
    
    $ricettaId = $_POST['ricettaId'];
    
    if (!isset($_SESSION['username'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Utente non autenticato']);
        exit;
    }
    
    // Ottieni l'ID dell'utente
    $userId = getUserIdByUsername($conn, $_SESSION['username']);
    // Rimuovi la ricetta dai preferiti
    if (removeRecipeFromFavorites($conn, $userId, $ricettaId)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Ricetta rimossa dai preferiti']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Errore durante la rimozione della ricetta']);
    }
    
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Richiesta non valida']);
}
?>