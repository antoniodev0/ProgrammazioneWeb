
<?php 
    // Includo il file di connessione al database
    require_once __DIR__ . "/Database.php"; 
    
    $sql = $sql = "SELECT * FROM ricette WHERE categoria = 'Popolari'";
    $results = $conn->query($sql);
    
    

?>


<!DOCTYPE html>
<html lang="it">
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FooD Day</title>
    <link rel="stylesheet" href="../css/homepage.css">
</head>
<body>
    <div class="container">
    <div class="login-registrazione" id="loginSection">
        <a href="../login.html">Accedi</a>
        <p>o</p>
        <a href="registrazione.php">Registrati</a>
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
                <!-- utilizzo htmlspecialchars per convertire caratteri speciali in entità HTML -->
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
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
    </section>
    </div>
    
</body>
</html>
