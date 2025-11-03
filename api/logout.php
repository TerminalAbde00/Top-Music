<?php
session_start();

// Verifica se l'utente è effettivamente loggato
if (!isset($_SESSION["id"]) && !isset($_SESSION["nome"])) {
    // Se non è loggato, reindirizza alla homepage
    header("Location: ../");
    exit();
}

// Elimina tutte le variabili di sessione
unset($_SESSION["id"]);
unset($_SESSION["nome"]);

// Elimina eventuali altre variabili di sessione correlate
if (isset($_SESSION["errore_login"])) {
    unset($_SESSION["errore_login"]);
}

// Distrugge completamente la sessione per sicurezza
session_destroy();

// Rigenera l'ID di sessione per prevenire session fixation
session_start();
session_regenerate_id(true);

// Reindirizza alla homepage con messaggio di logout
header("Location: ../");
exit();
?>
