<!DOCTYPE html>
<html>

<head>
  <!-- Styles moved to CSS/style_Player.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="JS/scriptPlayer.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto+Condensed'><link rel="stylesheet" href="./CSS/style_SegnalazioniOk.css">
  <?php
  include("config.php");
  $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
  if ($myDB->connect_errno) {
    echo "Errore di connessione";
    exit();
  }

  // validate id
  $Id_C = isset($_GET['Id']) ? (int)$_GET['Id'] : 0;
  if ($Id_C <= 0) {
    echo "ID non valido.";
    exit();
  }

  // fetch song using prepared statement (with robust fallbacks)
  $song = null;
  $sql = "SELECT Id, NomeCanzone, Autore, IMG, VID FROM canzoni WHERE Id = ? LIMIT 1";
  $stmt = $myDB->prepare($sql);
  if ($stmt === false) {
    // prepare failed (maybe driver or SQL); fallback to direct query
    error_log("[Player.php] prepare() failed: " . $myDB->error);
    $escapedId = (int)$Id_C;
    $res = $myDB->query("SELECT Id, NomeCanzone, Autore, IMG, VID FROM canzoni WHERE Id = $escapedId LIMIT 1");
    $song = $res ? $res->fetch_assoc() : null;
  } else {
    if (! $stmt->bind_param('i', $Id_C)) {
      error_log("[Player.php] bind_param() failed: " . $stmt->error);
    }
    $stmt->execute();
    // prefer get_result when available (requires mysqlnd), otherwise use bind_result
    if (method_exists($stmt, 'get_result')) {
      $res = $stmt->get_result();
      $song = $res ? $res->fetch_assoc() : null;
    } else {
      // fallback: bind_result
      $stmt->bind_result($sid, $sname, $sauthor, $simg, $svid);
      if ($stmt->fetch()) {
        $song = [
          'Id' => $sid,
          'NomeCanzone' => $sname,
          'Autore' => $sauthor,
          'IMG' => $simg,
          'VID' => $svid,
        ];
      }
    }
    $stmt->close();
  }

  if (!$song) {
    echo "Canzone non trovata.";
    exit();
  }

  // count reports (with fallback if prepare/get_result not available)
  $reports = 0;
  $sql2 = "SELECT COUNT(*) AS cnt FROM segnalazioni WHERE fkCanzone = ?";
  $stmt2 = $myDB->prepare($sql2);
  if ($stmt2 === false) {
    error_log("[Player.php] prepare() for reports failed: " . $myDB->error);
    $res2 = $myDB->query("SELECT COUNT(*) AS cnt FROM segnalazioni WHERE fkCanzone = " . (int)$Id_C);
    if ($res2 && ($r = $res2->fetch_assoc())) {
      $reports = (int)$r['cnt'];
    }
  } else {
    $stmt2->bind_param('i', $Id_C);
    $stmt2->execute();
    if (method_exists($stmt2, 'get_result')) {
      $res2 = $stmt2->get_result();
      $reports = ($res2 && ($r = $res2->fetch_assoc())) ? (int)$r['cnt'] : 0;
    } else {
      $stmt2->bind_result($cnt);
      if ($stmt2->fetch()) {
        $reports = (int)$cnt;
      } else {
        $reports = 0;
      }
    }
    $stmt2->close();
  }

  // output meta tags using fetched data
  ?>
  <meta charset="utf-8">
  <link rel="stylesheet" href="CSS/style_Player.css">
  <link rel="icon" href="IMG/COVER/<?= htmlspecialchars($song['IMG']) ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title><?= htmlspecialchars($song['NomeCanzone']) ?> - <?= htmlspecialchars($song['Autore']) ?></title>
  <?php
  
  ?>
</head>

<body>
<a href="/" id="backback" class="back"><img src="IMG/PAGINA/ar.png" alt="Indietro"></a>
<?php // show flag button only when reports < 5 ?>
<?php if ($reports < 5): ?>
  <div id="show" class="segnala">
    <i class="fa fa-flag flag-icon" aria-hidden="true"></i>
  </div>
<?php endif; ?>
  <?php
  // render using $song and $reports (already fetched)
  $FILE = $song['VID'];
  $parts = explode('.', $FILE);
  $estensioneF = strtolower(array_pop($parts));

  ?>
  <div class="black"></div>
  <div class="BG"></div>

  <?php if (in_array($estensioneF, ['mp4', 'm4v'])): ?>
    <video autoplay loop playsinline id="myVideo">
      <source src="VID/<?= htmlspecialchars($song['VID']) ?>" type="video/mp4">
      <div class="PS"></div>
    </video>
  <?php elseif (in_array($estensioneF, ['mp3', 'm4a'])): ?>
    <video autoplay loop playsinline id="myVideo2">
      <source src="VID/loop/loop.mp4" type="video/mp4">
      <div class="PS"></div>
    </video>
    <audio src="VID/<?= htmlspecialchars($song['VID']) ?>" autoplay loop id="myVideo"></audio>
  <?php endif; ?>
  <div>
    <div class="box">
      <?php if ($reports >= 5): ?>
        <img src="IMG/PAGINA/rev.png" class="rev-badge" alt="Badge">
      <?php endif; ?>
      <div class="cover">
        <img id="angoli" src="IMG/COVER/<?= htmlspecialchars($song['IMG']) ?>" width="200" height="200" alt="Cover">
      </div>
      <div class="text">
        <p><strong><?= htmlspecialchars($song['NomeCanzone']) ?></strong></p>
      </div>
      <div class="text">
        <p class="author"><?= htmlspecialchars($song['Autore']) ?></p>
      </div>
      <div>
        <button id="myBtn" onclick="myFunction()">Pause</button>
      </div>
    </div>
  </div>

  <div class="background">
    <div class="container">
      <div class="screen">
        <div class="screen-body">
          <div class="screen-body-item left">
            <div class="app-title">
              <span>SEGNALA</span>
              <span>PROBLEMA</span>
            </div>
          </div>
          <div class="screen-body-item">
            <div class="app-form">
              <form>
                <div class="app-form-group">
                  <input class="app-form-control" placeholder="Nome della canzone" value="<?= htmlspecialchars($song['NomeCanzone']) ?>" readonly>
                </div>
                <div class="app-form-group">
                  <input class="app-form-control" placeholder="Autore" value="<?= htmlspecialchars($song['Autore']) ?>" readonly>
                </div>

                <div class="app-form-group message">
                  <input class="app-form-control hidden-input" name="id" value="<?= (int)$song['Id'] ?>">
                  <input class="app-form-control" placeholder="Descrivi il problema" name="Messaggio" required>
                </div>
                <div class="app-form-group buttons">
                  <p class="app-form-button cancel-button">ANNULLA</p>
                  <input type="submit" class="app-form-button" value="SEGNALA" name="submit">
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="progress">
    <span class="start"></span>
    <div class="progress-bar">
      <div class="now"></div>
    </div>
    <span class="end"></span>
  </div>
   <!-- Scripts spostati in JS/scriptPlayer.js -->
  </body>
</html>