<?php
session_start();
require_once("../config.php");

// This endpoint returns small HTML snippets (buttons) used by the frontend
header('Content-Type: text/html; charset=utf-8');

// Verifica che l'utente sia autenticato
if (!isset($_SESSION["id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Non autorizzato"]);
    exit();
}

// Verifica che la richiesta sia GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito"]);
    exit();
}

// Ottieni parametri
$action = isset($_GET['action']) ? $_GET['action'] : '';
$canzoneId = isset($_GET['Id']) ? intval($_GET['Id']) : 0;
$utenteId = $_SESSION["id"];

// Validazione
if ($canzoneId <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "ID canzone non valido"]);
    exit();
}

if ($action !== 'add' && $action !== 'remove') {
    http_response_code(400);
    echo json_encode(["error" => "Azione non valida"]);
    exit();
}

// Connessione database
$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);

if ($conn->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "Errore di connessione al database"]);
    exit();
}

// Gestione add
if ($action === 'add') {
    $stmt = $conn->prepare("INSERT INTO canzoniPreferite (fkCanzone, fkUtente) VALUES (?, ?)");
    $stmt->bind_param('ii', $canzoneId, $utenteId);
    
    if ($stmt->execute()) {
        // return a button that uses CSS classes (no inline styles)
        $button = '<button id="bottone'.$canzoneId.'" onClick="elimina('.$canzoneId.')" class="bottones"></button>';
        echo $button;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Errore nell'aggiunta ai preferiti"]);
    }
    $stmt->close();
}

// Gestione remove
if ($action === 'remove') {
    $stmt = $conn->prepare("DELETE FROM canzoniPreferite WHERE fkCanzone = ? AND fkUtente = ?");
    $stmt->bind_param('ii', $canzoneId, $utenteId);
    
    if ($stmt->execute()) {
        // when removed, return the inactive button
        $button = '<button id="bottone'.$canzoneId.'" onClick="aggiungi('.$canzoneId.')" class="bottone"></button>';
        echo $button;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Errore nella rimozione dai preferiti"]);
    }
    $stmt->close();
}

$conn->close();
exit();
?>
