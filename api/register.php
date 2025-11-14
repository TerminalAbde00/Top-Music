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
$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($conn->connect_errno) {
    http_response_code(500);
    $_SESSION["errore_register"] = true;
    $_SESSION["errore_register0"] = true;
    $_SESSION["errore_register1"] = true;
    header("Location: ../login.php");
    exit();
}

// Validazione input
if (!isset($_POST['usr']) || !isset($_POST['psw']) || !isset($_POST['nme'])) {
    $_SESSION["errore_register"] = true;
    $_SESSION["errore_register0"] = true;
    $_SESSION["errore_register1"] = true;
    header("Location: ../login.php");
    exit();
}

$username = trim($_POST['usr']);
$password = $_POST['psw'];
$nome = trim($_POST['nme']);

// Validazione campi vuoti
if (empty($username) || empty($password) || empty($nome)) {
    $_SESSION["errore_register"] = true;
    $_SESSION["errore_register0"] = true;
    $_SESSION["errore_register1"] = true;
    header("Location: ../login.php");
    exit();
}

// Sanitizzazione input
$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
$nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
// Usa password_hash per sicurezza, MD5 è deprecato
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$Data = date("d-m-Y");
$Ora = date("H:i:s");

// Verifica se username esiste già
$stmt = $conn->prepare("SELECT username FROM utenti WHERE username = ?");
if (!$stmt) {
    $_SESSION["errore_register"] = true;
    $_SESSION["errore_register0"] = true;
    $_SESSION["errore_register1"] = true;
    header("Location: ../login.php");
    exit();
}

$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($existing_username);
$stmt->fetch();
$stmt->close();

if ($existing_username !== null) {
    // Username già esistente
    $_SESSION["errore_register"] = true;
    $_SESSION["errore_register0"] = true;
    $_SESSION["errore_register1"] = true;
    mysqli_close($conn);
    header("Location: ../login.php");
    exit();
}

// Inserimento nuovo utente
$stmt = $conn->prepare("INSERT INTO utenti (username, password, nome, Data, Ora) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    $_SESSION["errore_register"] = true;
    $_SESSION["errore_register0"] = true;
    $_SESSION["errore_register1"] = true;
    mysqli_close($conn);
    header("Location: ../login.php");
    exit();
}

$stmt->bind_param('sssss', $username, $hashedPassword, $nome, $Data, $Ora);

if ($stmt->execute()) {
    unset($_SESSION["errore_register"]);
    unset($_SESSION["errore_register0"]);
    unset($_SESSION["errore_register1"]);
    
    // Auto-login dopo registrazione
    $user_id = $conn->insert_id;
    $_SESSION["id"] = $user_id;
    $_SESSION["nome"] = $nome;

    header("Location: ../profilo.php");
} else {
    $_SESSION["errore_register"] = true;
    $_SESSION["errore_register0"] = true;
    $_SESSION["errore_register1"] = true;
    header("Location: ../profilo.php");
}

$stmt->close();
mysqli_close($conn);
exit();
?>
