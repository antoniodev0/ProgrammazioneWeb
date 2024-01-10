<?php 
session_start();
if (isset($_SESSION["username"])) {

    // Includo il file di connessione al database
    require_once __DIR__ . "/Database.php"; 
    
    $sql = $sql = "SELECT * FROM ricette WHERE categoria = 'Popolari'";
    $results = $conn->query($sql);
    
    // Uso le prepared statements per prevenire SQL Injection
    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id_utente = $user["id"];
       
    } 

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FooD Day</title>
    <link rel="stylesheet" href="../css/homepage.css"> 
    <script src="../js/salvaRicetta.js"></script>
    <script src="https://kit.fontawesome.com/186eb98a62.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
    <div class="login-registrazione" id="loginSection">
        <a href="profilo.php">Profilo</a>
        
    </div>
        <header>
            <h1>FooD Day</h1>
            <p>Benvenuti in FooD Day, qui potrete trovare tutte le vostre ricette preferite da poter realizzare in modo semplice a casa vostra!</p>
        </header>

        

        <nav>
            <ul>
            <li><a href="primipiatti.php">Primi piatti</a></li>
            <li><a href="secondipiatti.php">Secondi piatti</a></li>
            <li><a href="dolci.php">Dolci</a></li>
            </ul>
        </nav>

        <section id="ricette-popolari">
            <h2>Le ricette più deliziose</h2>
        
            <?php if ($results->num_rows > 0): ?>
        <?php while($ricetta = $results->fetch_assoc()): ?>
            <div class="ricetta">
                <img src="<?php echo htmlspecialchars($ricetta['immagine_url']); ?>" alt="<?php echo htmlspecialchars($ricetta['nome']); ?>">
                <div class="descrizione-ricetta-sinistra">
                    <h3><?php echo htmlspecialchars($ricetta['nome']); ?></h3>
                    <p><?php echo htmlspecialchars($ricetta['descrizione']); ?></p>
                    <p>Preparazione: <?php echo htmlspecialchars($ricetta['preparazione']); ?></p>
                    <p>Dose: <?php echo htmlspecialchars($ricetta['dose']); ?></p>
                    <p>Ingredienti:</p>
                    <ul>
                        <?php
                        // Assumendo che gli ingredienti siano separati da virgole
                        $ingredienti = explode(',', $ricetta['ingredienti']);
                        foreach ($ingredienti as $ingrediente):
                        ?>
                            <li><?php echo htmlspecialchars(trim($ingrediente)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <form class="form-salva-ricetta" action="salvaRicetta.php">
                <input type="hidden" name="ricettaId" value="<?php echo $ricetta['id']; ?>">
                <button class="btn-salva-ricetta" type="submit">
                <i class="fa-solid fa-heart cuore"></i>
                </button>
            </form> 
               
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</section>
    </div>
    
    
</body>
</html>
