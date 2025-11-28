<?php
session_start();

if (isset($_SESSION["Admin"]) && $_SESSION["Admin"] === 'yes') {
    include("config.php");
    $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
    if ($myDB->connect_errno) {
        http_response_code(500);
        echo "Errore di connessione";
        exit();
    }
    
    // Validazione input
    $Id_C = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
    if ($Id_C <= 0) {
        http_response_code(400);
        echo "ID non valido";
        exit();
    }
    
    // Usa prepared statement per evitare SQL injection
    $stmt = $myDB->prepare("SELECT Id, NomeCanzone, Autore, IMG, VID, fkUser FROM canzoni WHERE id = ?");
    $stmt->bind_param('i', $Id_C);
    $stmt->execute();
    $risultato = $stmt->get_result();
    $row = $risultato->fetch_assoc();
    $stmt->close();
    
    if ($row) {
        $path1 = '../IMG/COVER/' . $row["IMG"];
        $path2 = '../VID/' . $row["VID"];
        
        // Cancella file
        if (file_exists($path1)) {
            unlink($path1);
        }
        if (file_exists($path2)) {
            unlink($path2);
        }
        
        // Usa prepared statement per cancellazione
        $deleteStmt = $myDB->prepare("DELETE FROM canzoni WHERE id = ?");
        $deleteStmt->bind_param('i', $Id_C);
        
        if ($deleteStmt->execute()) {
            $deleteStmt->close();
            $myDB->close();
            header("Location: index.php");
        } else {
            http_response_code(500);
            echo "Errore nella cancellazione: " . $deleteStmt->error;
            $deleteStmt->close();
            $myDB->close();
        }
    } else {
        http_response_code(404);
        echo "Canzone non trovata";
        $myDB->close();
    }
} else {
    http_response_code(403);
    echo "Non Autorizzato";
}
?>