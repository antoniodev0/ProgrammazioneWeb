<?php
// Connessione al database

    include 'Database.php'; 


// Verifica il metodo della richiesta
$method = $_SERVER['REQUEST_METHOD'];

// Gestisci la richiesta in base al metodo
switch ($method) {
    case 'POST':
        saveRecipeAsFavorite($conn);
        break;
    case 'GET':
        getUserFavoriteRecipes($conn);
        break;
    default:
        // Metodo non supportato
        http_response_code(405);
        echo json_encode(['message' => 'Metodo non supportato']);
        break;
}

function saveRecipeAsFavorite($conn) {
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ricettaId = $_POST['ricettaId'];

        // Ottieni l'ID dell'utente
        $userId = getUserIdByUsername($conn, $_SESSION['username']);

        // Controlla se la ricetta è già stata salvata dall'utente
        if (isRecipeSaved($conn, $userId, $ricettaId)) {
            echo json_encode(['status' => 'error', 'message' => 'Ricetta già salvata']);
            return;
        }

        // Salva la ricetta come preferita
        if (saveRecipe($conn, $userId, $ricettaId)) {
            echo json_encode(['status' => 'success', 'message' => 'Ricetta salvata con successo']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Errore nel salvataggio della ricetta']);
        }
    }
}

function getUserFavoriteRecipes($conn) {
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_SESSION['username'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Utente non autenticato']);
            return;
        }

        // Ottieni l'ID dell'utente
        $userId = getUserIdByUsername($conn, $_SESSION['username']);

        // Ottieni le ricette preferite dell'utente
        $favoriteRecipes = getFavoriteRecipes($conn, $userId);

        // Ritorna la risposta in formato JSON
        echo json_encode(['favorites' => $favoriteRecipes]);
    }
}

// Funzione per ottenere l'ID dell'utente dal database
function getUserIdByUsername($conn, $username) {
    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['id'];
}

// Funzione per verificare se una ricetta è già stata salvata come preferita
function isRecipeSaved($conn, $userId, $ricettaId) {
    $stmt = $conn->prepare("SELECT * FROM ricette_preferite WHERE user_id = ? AND ricetta_id = ?");
    $stmt->bind_param("ii", $userId, $ricettaId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Funzione per salvare la ricetta come preferita per un utente
function saveRecipe($conn, $userId, $ricettaId) {
    $stmt = $conn->prepare("INSERT INTO ricette_preferite (user_id, ricetta_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $ricettaId);
    return $stmt->execute();
}

// Funzione per ottenere le ricette preferite di un utente
function getFavoriteRecipes($conn, $userId) {
    $favoriteRecipes = [];
    $stmt = $conn->prepare("SELECT * FROM ricette_preferite WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $favoriteRecipes[] = $row['ricetta_id'];
    }
    return $favoriteRecipes;
}

// Chiudi la connessione al termine dell'elaborazione
$conn->close();
?>
