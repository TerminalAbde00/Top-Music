<?php
session_start();

// Se non autenticato, redirect alla pagina login
if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/style_UploadM.css">
    <link rel="icon" href="IMG/PAGINA/top.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Carica la tua canzone - TOP MUSIC</title>
    <style>
        #upup:hover {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
    <a href="logout.script.php">
        <img src="IMG/PAGINA/logout.png" id="backback" style="transition: transform 250ms;width: 32px;z-index:7;margin: 34px;position: absolute;right:0;filter: invert(0.9);">
    </a>
    <a href="/">
        <img src="IMG/PAGINA/ar.png" id="backback" style="transition: transform 250ms;width: 30px;z-index:7;margin: 33px;position: absolute;filter: invert(1);">
    </a>
    
    <form action="api/upload_song.php" method="post" enctype="multipart/form-data" id="mandadati" onsubmit="hide()">
        <h1><strong style="font-size: 47px;">Ciao <?php echo htmlspecialchars($_SESSION["nome"], ENT_QUOTES, 'UTF-8'); ?>,</strong> CARICA LA TUA CANZONE</h1>
        
        <div class="form-group">
            <label for="title">Nome Canzone <span>Inserisci il nome della canzone</span></label>
            <input type="text" name="NomeCanzone" class="form-controll" required="required" maxlength="29">
        </div>
        
        <div class="form-group">
            <label for="caption">Autore <span>Inserisci il nome dell'Autore</span></label>
            <input type="text" name="Autore" class="form-controll" required="required" maxlength="29">
        </div>

        <div class="form-group file-area">
            <label for="images">Cover <span>Inserisci la cover della canzone</span></label>
            <input type="file" name="IMG" required="required" accept="image/png, image/jpeg">
            <div class="file-dummy">
                <div class="success">Cover Caricata</div>
                <div class="default">Seleziona la Cover</div>
            </div>
        </div>

        <div class="form-group file-area">
            <label for="images">Video o Audio <span>Inserisci il video o l'audio della canzone (Max 20Mb)</span></label>
            <input type="file" name="VID" required="required" accept="video/mp4, .mp3, .m4a">
            <div class="file-dummy">
                <div class="success">Video/Audio Caricato</div>
                <div class="default">Seleziona il Video/Audio</div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit">Carica Canzone</button>
        </div>
    </form>
    
    <a href="CanzoniCaricate.php">
        <img src="IMG/PAGINA/CCC.png" id="upup" style="transition: transform 250ms;z-index: 12;position: fixed;bottom: 12px;right: 20px;width: 98px;padding: 14px;">
    </a>

    <link href="https://fonts.googleapis.com/css?family=Lato:100,200,300,400,500,600,700" rel="stylesheet" type="text/css">

    <div id="loading" style="transform: translate(-50%, -50%);position: fixed;top: 50%;left: 50%;width: 100%;display:none;">
        <div style="text-align: center;">
            <canvas id="pizza" width="210" height="210"></canvas>
        </div>
        <div>
            <p style="color: white;font-family: sans-serif;font-size: 38px;margin-bottom: 0;width: 100%;text-align: center;">Attendi sto caricando la tua canzone</p>
        </div>
    </div>

    <!-- Script Animazione Pizza -->
    <script src="JS/scriptUpload.js"></script>
</body>
</html>
