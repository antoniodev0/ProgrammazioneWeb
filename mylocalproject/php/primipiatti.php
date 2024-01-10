<?php 
session_start();

$logged_in = isset($_SESSION['username']);
    // Includo il file di connessione al database
    require_once __DIR__ . "/Database.php"; 
    
    $sql = "SELECT * FROM ricette WHERE categoria = 'Primi piatti'";
    $results = $conn->query($sql);
    

?>
<!DOCTYPE html>
<html lang="it">
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primi Piatti - FooD Day</title>
    <link rel="stylesheet" href="../css/homepage.css"> 
    <script src="../js/salvaRicetta.js"></script>
    <script
    src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/186eb98a62.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
    <div class="login-registrazione" id="loginSection">
        <?php if ($logged_in): ?>
            <!-- Se l'utente è loggato, visualizza il link al profilo -->
            <a href="profilo.php">Profilo</a> 
        <?php else: ?>
            <!-- Se l'utente non è loggato, visualizza i link per accedere o registrarsi -->
            <a href="../login.html">Accedi</a>
            <p>o</p>
            <a href="registrazione.php">Registrati</a>
        <?php endif; ?>
    </div>
        <header>
        <?php if ($logged_in){ echo '<a href="Dashboard_utente.php"><h1>FooD Day - Primi Piatti</h1></a>';}
                else{ echo '<a href="homepage.php"><h1>FooD Day - Primi Piatti</h1></a>';}?>
            <p>Benvenuti nella sezione dei Primi Piatti!</p>
        </header>
        <nav>
            <ul>
            <li><a href="primipiatti.php">Primi piatti</a></li>
            <li><a href="secondipiatti.php">Secondi piatti</a></li>
            <li><a href="dolci.php">Dolci</a></li>
            </ul>
        </nav>
        <section id="ricette-primi-piatti">
            <h2>Ricette di Primi Piatti</h2>
        
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
                        <?php if ($logged_in): ?>
                            <form class="form-salva-ricetta" action="salvaRicetta.php">
                            <input type="hidden" name="ricettaId" value="<?php echo $ricetta['id']; ?>">
                            <button class="btn-salva-ricetta" type="submit">
                            <i class="fa-solid fa-heart cuore"></i>
                            </button>
                        </form> 
                     <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Non ci sono ricette di Primi Piatti al momento.</p>
            <?php endif; ?>
        </section>
    </div>

    
</body>
</html>
