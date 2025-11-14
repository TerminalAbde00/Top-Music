<?php
// Inizializzazione
session_start();
$isLoggedIn = isset($_SESSION["id"]);
$userId = $isLoggedIn ? $_SESSION["id"] : null;

// Connessione al database
require_once("config.php");
$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if($myDB->connect_errno) {
    die("Errore di connessione al database");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TOP MUSIC - Discover new music everyday</title>    
    <link rel="icon" href="IMG/PAGINA/top.png">    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style_HomeOk.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="JS/scriptIndex.js" defer></script>
</head>

<body class="page-bg">
    <!-- Top Navigation Bar -->
    
    <header class="bar bar--top">
        <?php if ($isLoggedIn): ?>
            <a href="fav.php" class="favorites-link">
                <img id="LeftI" src="IMG/PAGINA/star.png" alt="I tuoi preferiti">
            </a>
        <?php endif; ?>
        
        <div class="logo">
            <img src="IMG/PAGINA/logo.png" alt="TOP MUSIC Logo">
        </div>
        
        <!-- Search Bar -->
        <div class="search-container">
            <button id="ricerca">
                <img src="IMG/PAGINA/cerca1.png" width="30" alt="Cerca">
            </button>
            <div id="box" class="search-box visuallyhidden hidden">
                <input type="text" 
                       autocomplete="off" 
                       placeholder="Cerca la tua canzone"
                       aria-label="Cerca canzoni">
                <div class="result" aria-live="polite"></div>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main id="app" class="container">
        <?php
        // Query per ottenere tutte le canzoni
        $risultato = $myDB->query("SELECT Id, NomeCanzone, Autore, IMG, fkUser 
                                  FROM canzoni 
                                  ORDER BY Id DESC");

        // Itera sulle canzoni
        while ($song = $risultato->fetch_assoc()):
            ?>
            <div class="card-wrap">
                <div class="card">
                    <?php if ($isLoggedIn): ?>
                        <?php
                        // Controlla se la canzone è nei preferiti
                        $stmt = $myDB->prepare("SELECT 1 FROM canzoniPreferite WHERE fkCanzone = ? AND fkUtente = ?");
                        $stmt->bind_param('ii', $song['Id'], $userId);
                        $stmt->execute();
                        $Preferiti = $stmt->get_result();
                        $isFavorite = $Preferiti->num_rows > 0;
                        $stmt->close();
                        ?>
                        
                        <!-- Pulsante Preferiti -->
                        <div id="bottone_colore<?= $song['Id'] ?>">
                            <button id="bottone<?= $song['Id'] ?>"
                                    onClick="<?= $isFavorite ? 'elimina' : 'aggiungi' ?>(<?= $song['Id'] ?>)"
                                    class="<?= $isFavorite ? 'bottones' : 'bottone' ?>"
                                    aria-label="<?= $isFavorite ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' ?>">
                            </button>
                        </div>

                        <!-- Pulsante Elimina (solo per il proprietario) -->
                        <?php /*if ($userId == $song['fkUser']): ?>
                            <a href="api/delete_song.php?idc=<?= $song['Id'] ?>"
                               onclick="return confirm('Sei sicuro di voler cancellare <?= htmlspecialchars($song['NomeCanzone']) ?> ?')"
                               class="delete-button">
                                <img src="IMG/PAGINA/del.png" id="del" alt="Elimina canzone">
                            </a>
                        <?php endif;*/ ?>
                    <?php endif; ?>

                    <!-- Card Canzone -->
                    <a href="Player.php?Id=<?= $song['Id'] ?>" class="song-link">
                        <img class="cover-img" 
                             src="IMG/COVER/<?= htmlspecialchars($song['IMG']) ?>" 
                             alt="Cover di <?= htmlspecialchars($song['NomeCanzone']) ?>">
                        <div class="card-info">
                            <p class="song-title">
                                <b><?= htmlspecialchars($song['NomeCanzone']) ?></b>
                            </p>
                            <p class="song-author"><?= htmlspecialchars($song['Autore']) ?></p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endwhile; 
        
        // Libera le risorse
        $risultato->free();
        ?>
    </main>
    <!-- Upload Button -->
    <a href="profilo.php" class="upload-button" aria-label="Carica una nuova canzone">
        <img src="IMG/PAGINA/upload.png" id="upup" alt="Carica canzone">
    </a>

    <?php
    // Chiudi la connessione al database
    $myDB->close();
    ?>
</body>
</html>
