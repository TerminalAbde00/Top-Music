<?php
session_start();
require_once("../config.php");

// Verifica metodo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Location: login.php");
    exit();
}

// Connessione database
$mydb = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($mydb->connect_errno) {
    http_response_code(500);
    $_SESSION["errore_login"] = true;
    header("Location: login.php");
    exit();
}

// Validazione input
if (!isset($_POST["usr"]) || !isset($_POST["pwd"])) {
    $_SESSION["errore_login"] = true;
    header("Location: login.php");
    exit();
}

$username = trim($_POST["usr"]);
$password = $_POST["pwd"];

// Validazione campi vuoti
if (empty($username) || empty($password)) {
    $_SESSION["errore_login"] = true;
    header("Location: login.php");
    exit();
}

// Prepared statement per login
$stmt = $mydb->prepare("SELECT id, nome, password FROM utenti WHERE username = ?");
if (!$stmt) {
    $_SESSION["errore_login"] = true;
    header("Location: login.php");
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($id, $nome, $stored_password);

$login_success = false;

while ($stmt->fetch()) {
    // Verifica password usando password_verify() per sicurezza
    // Supporta sia password_hash che MD5 per compatibilità legacy
    if (password_verify($password, $stored_password) || md5($password) == $stored_password) {
        unset($_SESSION["errore_login"]);
        $_SESSION["nome"] = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
        $_SESSION["id"] = $id;
        $login_success = true;
        break;
    }
}

$stmt->close();
$mydb->close();

if ($login_success) {
    header("Location: ../profilo.php");
} else {
    $_SESSION["errore_login"] = true;
    header("Location: ../profilo.php");
}
exit();
?>