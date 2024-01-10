<?php
$servername = "localhost:3306"; // Indirizzo del server MySQL
$username = "root"; // Nome utente di MySQL (default di XAMPP)
$password = ""; // Password di MySQL (default di XAMPP)
$dbname = "cucina_db"; // Nome del database

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}