<html lang="it">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - FooD Day</title>
    <link rel="stylesheet" href="../css/registrazione.css"> 
  

</head>
<body>
<div class="container">
<header>
    <h1>FooD Day</h1>
</header>
<div class="riquadro-registrazione">
<div id="error-messages">
<?php
session_start();
require_once 'Database.php';

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = ""; // Stringa per raccogliere gli errori

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
    $repeat_password = test_input($_POST["repeat_password"]);

    // Validazione dell'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors .= "Email non valida.<br>";
    }

    // Verifica corrispondenza password e criteri di sicurezza
    if ($password !== $repeat_password) {
        $errors .= "Le password non corrispondono.<br>";
    } elseif (strlen($password) < 8 || !preg_match('/^(?=.*[0-9])[a-zA-Z0-9]{8,}$/', $password)) {
        $errors .= "La password deve contenere almeno 8 caratteri con almeno un numero e senza caratteri speciali.<br>";
    }

    // Controllo lunghezza username
    if (strlen($username) < 4) {
        $errors .= "Lo username deve essere lungo almeno 4 caratteri.<br>";
    }

    // Controllo username ed email già in uso
    if (empty($errors)) {
        // Controllo username
        $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors .= "Lo username è già stato utilizzato. Scegli un altro username.<br>";
        }

        // Controllo email
        $stmt = $conn->prepare("SELECT * FROM utenti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors .= "L'email è già stata utilizzata. Usa un'altra email.<br>";
        }
    }

    // Se non ci sono errori, procedo con la registrazione
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if (!$stmt->execute()) {
            $errors .= "Errore durante la registrazione: " . $conn->error;
        }

        $stmt->close();
    }

    // Stampa gli errori o un messaggio di successo
    if (!empty($errors)) {
        echo $errors;
    } else {
        // Se la registrazione è riuscita, puoi reindirizzare o fare altro
        header("Location: homepage.php");
        exit;
    }
}
?>
</div>
    <form action="" method="post" > 
        <h2>Registrazione</h2>
        <input type="text" id="username" name="username" placeholder="Username" required>
        <input type="email"  id="email" name="email" placeholder="E-mail" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <input type="password" id="repeat_password" name="repeat_password" placeholder="Ripeti Password" required>
        <button type="submit">Registrati</button>                
    </form>
</div>
</div> 
</body>
</html>