<?php
session_start();
if(isset($_SESSION["Admin"]) && $_SESSION["Admin"] === 'yes'){
    // Validazione input
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) {
        http_response_code(400);
        echo "ID non valido";
        exit();
    }
    
    include("config.php");
    $conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
    if ($conn->connect_errno) {
        http_response_code(500);
        echo "Errore di connessione";
        exit();
    }
    
    // Usa prepared statement per evitare SQL injection
    $stmt = $conn->prepare("DELETE FROM segnalazioni WHERE fkCanzone = ?");
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php");
    } else {
        http_response_code(500);
        echo "Errore nella cancellazione: " . $stmt->error;
        $stmt->close();
        $conn->close();
    }
} else {
    http_response_code(403);
    echo "Non Autorizzato";
}
?>
