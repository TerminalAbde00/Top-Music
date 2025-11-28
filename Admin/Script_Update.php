<?php
session_start();
if($_SESSION["Admin"]==true){
include("config.php");

// Validazione input
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$NomeCanzone = isset($_POST['NomeCanzone']) ? trim($_POST['NomeCanzone']) : '';
$Autore = isset($_POST['Autore']) ? trim($_POST['Autore']) : '';
$IMG = isset($_POST['IMG']) ? trim($_POST['IMG']) : '';
$VID = isset($_POST['VID']) ? trim($_POST['VID']) : '';

if ($id <= 0 || empty($NomeCanzone) || empty($Autore)) {
    http_response_code(400);
    echo "Parametri non validi";
    exit();
}

$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($conn->connect_errno) {
    http_response_code(500);
    echo "Errore di connessione";
    exit();
}

// Usa prepared statement per evitare SQL injection
$stmt = $conn->prepare("UPDATE canzoni SET NomeCanzone = ?, Autore = ?, IMG = ?, VID = ? WHERE id = ?");
$stmt->bind_param('ssssi', $NomeCanzone, $Autore, $IMG, $VID, $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: index.php");
} else {
    http_response_code(500);
    echo "Errore nell'aggiornamento: " . $stmt->error;
    $stmt->close();
    $conn->close();
}
}else{
http_response_code(403);
echo "Non Autorizzato";
}
 ?>
