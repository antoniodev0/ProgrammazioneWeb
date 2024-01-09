<!DOCTYPE html>
<html lang="it">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo</title>
    <link rel="stylesheet" href="../css/profilo.css"> 
    <script
    src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/186eb98a62.js" crossorigin="anonymous"></script>
    <script src="../js/rimozione_ricetta.js"></script>
    <script src="../js/conferma_eliminazione.js"></script>
</head>
<body>
    <div class="container">
        <header>
        <a href="Dashboard_utente.php" class="profilo-link"><h1>Il tuo profilo</h1></a>

            <p>Qui puoi visualizzare le tue informazioni e le tue ricette preferite</p>
        </header>
        
        <div class="login-registrazione" id="loginSection">
            <a href="logout.php">Logout</a>
        </div>
        <section id="eliminazione-account">
            <form action="eliminazione_account.php" method="post" id="eliminazione-account-form">
                <button type="button" id="conferma-eliminazione">Elimina Account</button>
            </form>
        </section>
        <section id="informazioni-personali">
            <?php 
                session_start();
                if (isset($_SESSION["username"])) {
                    require_once __DIR__ . "/Database.php"; 

                    // Query per informazioni personali
                    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
                    $stmt->bind_param("s", $_SESSION["username"]);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        echo "<div id='informazioni-personali'>";
                        echo "<h2>Informazioni Personali</h2>";
                        echo "<p>Username: " . $user['username'] . "</p>";
                        echo "<p>Email: " . $user['email'] . "</p>";
                        echo "</div>";
                    }
                }
            ?>
        </section>
        <section id="ricette-preferite">
            <?php 
                if (isset($_SESSION["username"])) {
                    require_once __DIR__ . "/Database.php"; 

                    // Ottiengo l'ID dell'utente
                    $id_utente = $user["id"];

                    // Query per ottenere le ricette preferite con i dettagli dalla tabella 'ricette'
                    $stmt = $conn->prepare("
                        SELECT r.*
                        FROM ricette_preferite rp
                        JOIN ricette r ON rp.ricetta_id = r.id
                        WHERE rp.user_id = ?
                    ");
                    $stmt->bind_param("i", $id_utente);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    echo "<div id='ricette-preferite'>";
                    echo "<h2>Ricette Preferite</h2>";
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='ricetta-preferita'>";
                        echo "<img src='" . htmlspecialchars($row['immagine_url']) . "' alt='" . htmlspecialchars($row['nome']) . "'>";
                        echo "<h3>" . htmlspecialchars($row['nome']) . "</h3>";
                        echo "<p>" . htmlspecialchars($row['descrizione']) . "</p>";
                        echo "<p>" . htmlspecialchars($row['preparazione']) . "</p>";
                        echo "<p>" . htmlspecialchars($row['dose']). "</p>";
                        echo "<p>" . 'Ingredienti:' . "</p>";
                        echo "<ul>";
                        $ingredienti = explode(',', $row['ingredienti']);
                        foreach ($ingredienti as $ingrediente):
                            echo  "<li>" . htmlspecialchars(trim($ingrediente)) . "</li>";
                        endforeach;
                        echo "</ul>";
                        echo "<form class='form-rimuovi-preferita' action='rimozione_ricetta.php' method='post'>";
                        echo "<input type='hidden' name='ricettaId' value='" . $row['id'] . "'>";
                        echo "<button type='submit' class='btn-rimuovi-preferita'>";
                        echo "<i class='fa-solid fa-heart'></i>";
                        echo "</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    echo "</div>";

                    $stmt->close();
                }
            ?>
        </section>
    </div>
</body>
</html>

