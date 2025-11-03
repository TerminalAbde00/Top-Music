<?php
session_start();
require_once("../config.php");

header('Content-Type: application/json');

// Verifica autenticazione
if (!isset($_SESSION["id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Non autorizzato"]);
    exit();
}

// Solo metodo GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito"]);
    exit();
}

// Validazione parametri
$songId = isset($_GET['idc']) ? intval($_GET['idc']) : 0;
$userId = $_SESSION["id"];

if ($songId <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "ID canzone non valido"]);
    exit();
}

// Connessione database
$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);

if ($conn->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "Errore di connessione al database"]);
    exit();
}

// Verifica che la canzone appartenga all'utente
$stmt = $conn->prepare("SELECT Id, IMG, VID, fkUser FROM canzoni WHERE Id = ?");
$stmt->bind_param('i', $songId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Canzone non trovata"]);
    $stmt->close();
    $conn->close();
    exit();
}

$row = $result->fetch_assoc();

// Verifica autorizzazione
if ($row['fkUser'] != $userId) {
    http_response_code(403);
    echo json_encode(["error" => "Non hai i permessi per cancellare questa canzone"]);
    $stmt->close();
    $conn->close();
    exit();
}

// Percorsi file
$path1 = '../IMG/COVER/' . $row["IMG"];
$path2 = '../VID/' . $row["VID"];

// Cancella file dal server
if (file_exists($path1)) {
    unlink($path1);
}
if (file_exists($path2)) {
    unlink($path2);
}

// Cancella record dal database
$deleteStmt = $conn->prepare("DELETE FROM canzoni WHERE Id = ?");
$deleteStmt->bind_param('i', $songId);

if ($deleteStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Canzone cancellata con successo"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Errore nella cancellazione"]);
}

$deleteStmt->close();
$stmt->close();
$conn->close();
exit();
?>