<?php

    include 'Database.php'; 
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();

    $response = ["status" => "error", "message" => ""];

    if ($user_result->num_rows === 1) {
        $row = $user_result->fetch_assoc();
        $stored_password = $row['password'];

        if (password_verify($password, $stored_password)) {
            session_start();
            $_SESSION["username"] = $username;
            $response["status"] = "success";
            $response["message"] = "Login effettuato con successo!";
        } else {
            $response["message"] = "Password errata.";
        }
    } else {
        $response["message"] = "Username non trovato.";
    }

    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);

?>
