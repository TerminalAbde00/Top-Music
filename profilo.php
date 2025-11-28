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
    <title>Il tuo profilo - TOP MUSIC</title>
    <link rel="icon" href="IMG/PAGINA/top.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lato', sans-serif;
        }

        body {
            background-color: #f2f2f2;
            min-height: 100vh;
        }

        .top-bar {
            background: #333333;
            padding: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar a img {
            width: 32px;
            filter: invert(0.9);
            transition: transform 250ms;
        }

        .top-bar a img:hover {
            transform: scale(1.1);
        }

        .tabs {
            margin-top: 80px;
            padding: 20px;
        }

        .tab-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-button {
            padding: 10px 20px;
            border: none;
            background: #333;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
        }

        .tab-button.active {
            background: #007bff;
        }

        .tab-content {
            display: none;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .tab-content.active {
            display: block;
        }

        /* Stili form upload */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Stili griglia canzoni */
        .songs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        /* Stili lista canzoni */
        .songs-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            padding: 0;
        }

        .song-list-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            background: white;
            border-bottom: 1px solid #e1e1e1;
            transition: background 0.3s ease;
        }

        .song-list-item:hover {
            background: #f8f9fa;
        }

        .song-list-item:last-child {
            border-bottom: none;
        }

        .song-list-link {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: inherit;
        }

        .song-list-cover {
            flex-shrink: 0;
        }

        .song-list-cover img {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .song-list-info {
            flex: 1;
            min-width: 0;
        }

        .song-list-title {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .song-list-artist {
            margin: 0;
            font-size: 13px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .song-list-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .favorite-btn-list {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            background: #f0f0f0;
            color: #666;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .favorite-btn-list:hover {
            background: #e0e0e0;
            color: #ff6b6b;
        }

        .favorite-btn-list.active {
            background: #ff6b6b;
            color: white;
        }

        .delete-btn-list {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            background: #f0f0f0;
            color: #999;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .delete-btn-list:hover {
            background: #ff4444;
            color: white;
        }

        .no-songs {
            padding: 40px 20px;
            text-align: center;
            color: #999;
            font-size: 16px;
        }

        .song-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .song-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .song-info {
            padding: 10px;
        }

        .song-info h3 {
            margin: 0;
            font-size: 18px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .song-info p {
            margin: 5px 0 0;
            color: #666;
        }

        /* Stili profilo utente */
        .profile-section {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .file-area {
            position: relative;
            margin-bottom: 20px;
        }

        .file-dummy {
            padding: 30px;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            text-align: center;
            transition: background 0.3s;
        }

        .file-dummy .success {
            display: none;
        }

        .file-area input[type="file"]:valid + .file-dummy .success {
            display: inline-block;
        }

        .file-area input[type="file"]:valid + .file-dummy .default {
            display: none;
        }

        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            padding: 20px;
            border-radius: 10px;
            display: none;
            z-index: 1000;
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,0,0,0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .favorite-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: grey;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .favorite-btn.active {
            background: gold;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="/">
            <img src="IMG/PAGINA/ar.png" alt="Torna alla home">
        </a>
        <h2 style="color: white;">Area Personale</h2>
        <a href="/api/logout.php">
            <img src="IMG/PAGINA/logout.png" alt="Logout">
        </a>
    </div>

    <div class="container">
        <div class="tabs">
            <div class="tab-buttons">
                <button class="tab-button active" onclick="openTab('upload')">
                    <i class="fas fa-upload"></i> Carica
                </button>
                <button class="tab-button" onclick="openTab('songs')">
                    <i class="fas fa-music"></i> Le tue canzoni
                </button>
                <button class="tab-button" onclick="openTab('profile')">
                    <i class="fas fa-user"></i> Profilo
                </button>
            </div>

            <!-- Tab Carica -->
            <div id="upload" class="tab-content active">
                <form action="api/upload_song.php" method="post" enctype="multipart/form-data" id="mandadati" onsubmit="showLoading()" class="upload-form">
                    <h2><i class="fas fa-cloud-upload-alt"></i> Carica la tua canzone</h2>
                    <div class="form-group">
                        <label for="songName">
                            <i class="fas fa-music"></i> Nome Canzone
                        </label>
                        <input type="text" name="NomeCanzone" id="songName" class="form-control" required maxlength="29" placeholder="Inserisci il nome della canzone">
                    </div>
                    
                    <div class="form-group">
                        <label for="artistName">
                            <i class="fas fa-user-music"></i> Artista
                        </label>
                        <input type="text" name="Autore" id="artistName" class="form-control" required maxlength="29" placeholder="Inserisci il nome dell'Artista">
                    </div>

                    <div class="form-group file-area">
                        <label for="cover">
                            <i class="fas fa-image"></i> Cover
                        </label>
                        <input type="file" name="IMG" id="cover" required accept="image/png, image/jpeg" class="file-input" hidden>
                        <div class="file-dummy" onclick="document.getElementById('cover').click()">
                            <div class="success">
                                <i class="fas fa-check-circle"></i> Cover Caricata
                            </div>
                            <div class="default">
                                <i class="fas fa-upload"></i> Seleziona la Cover
                            </div>
                        </div>
                    </div>

                    <div class="form-group file-area">
                        <label for="song">
                            <i class="fas fa-file-audio"></i> File Audio/Video
                        </label>
                        <input type="file" name="VID" id="song" required accept="video/mp4, .mp3, .m4a" class="file-input" hidden>
                        <div class="file-dummy" onclick="document.getElementById('song').click()">
                            <div class="success">
                                <i class="fas fa-check-circle"></i> File Caricato
                            </div>
                            <div class="default">
                                <i class="fas fa-upload"></i> Seleziona il file (Max 20Mb)
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-cloud-upload-alt"></i> Carica Canzone
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tab Le tue canzoni -->
            <div id="songs" class="tab-content">
                <div class="songs-list">
                <?php
                include("config.php");
                $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
                if($myDB->connect_errno){
                    echo "Errore di connessione";
                    exit();
                }
                // Usa prepared statement per evitare SQL injection
                $stmt = $myDB->prepare("SELECT * FROM canzoni WHERE fkUser = ? ORDER BY Id DESC");
                $stmt->bind_param('i', $_SESSION['id']);
                $stmt->execute();
                $risultato = $stmt->get_result();
                if ($risultato->num_rows == 0){
                    echo "<p class='no-songs'>Non hai ancora caricato nessuna canzone.</p>";
                }
                while ($row = $risultato->fetch_assoc()) {
                    // Bottone preferiti
                    $stmtPref = $myDB->prepare("SELECT fkCanzone,fkUtente FROM canzoniPreferite WHERE fkCanzone = ? AND fkUtente = ?");
                    $stmtPref->bind_param('ii', $row['Id'], $_SESSION["id"]);
                    $stmtPref->execute();
                    $Preferiti = $stmtPref->get_result();
                    $isFavorite = $Preferiti->num_rows > 0;
                    $stmtPref->close();
                    
                    $favoriteClass = $isFavorite ? 'active' : '';
                    $favoriteIcon = $isFavorite ? '♥' : '♡';
                    
                    echo '
                    <div class="song-list-item">
                        <a href="Player.php?Id='.$row["Id"].'" class="song-list-link">
                            <div class="song-list-cover">
                                <img src="IMG/COVER/'.$row["IMG"].'" alt="'.$row["NomeCanzone"].'">
                            </div>
                            <div class="song-list-info">
                                <h3 class="song-list-title">'.$row["NomeCanzone"].'</h3>
                                <p class="song-list-artist">'.$row["Autore"].'</p>
                            </div>
                        </a>
                        <div class="song-list-actions">
                            <button class="favorite-btn-list '.$favoriteClass.'" id="fav-'.$row["Id"].'" onclick="toggleFavorite('.$row['Id'].')" title="Aggiungi ai preferiti">
                                <i class="fas fa-heart"></i>
                            </button>';
                    
                    // Bottone elimina
                    if ($_SESSION["id"] == $row["fkUser"]) {
                        echo '<button class="delete-btn-list" onclick="deleteSong('.$row["Id"].',\''.$row["NomeCanzone"].'\');" title="Elimina canzone">
                                <i class="fas fa-trash-alt"></i>
                            </button>';
                    }
                    
                    echo '</div>
                    </div>';
                }
                ?>
                </div>
            </div>
        </div>

        <!-- Tab Profilo -->
        <div id="profile" class="tab-content">
            <div class="profile-section">
                <div class="profile-header">
                    <h2><?php echo htmlspecialchars($_SESSION["nome"], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <?php
                    $userInfoQuery = mysqli_query($myDB, "SELECT Email FROM utenti WHERE Id=".$_SESSION["id"]);
                    $userInfo = mysqli_fetch_assoc($userInfoQuery);
                    ?>
                    <p class="user-email">
                        <i class="fas fa-envelope"></i> 
                        <?php echo htmlspecialchars($userInfo["Email"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
                
                <div class="profile-stats">
                    <div class="stat-card">
                        <i class="fas fa-music fa-2x"></i>
                        <h3><?php echo $risultato->num_rows; ?></h3>
                        <p>Canzoni caricate</p>
                    </div>
                    <?php 
                    $favStmt = $myDB->prepare("SELECT COUNT(*) as total FROM canzoniPreferite WHERE fkUtente = ?");
                    $favStmt->bind_param('i', $_SESSION["id"]);
                    $favStmt->execute();
                    $favQuery = $favStmt->get_result();
                    $favCount = $favQuery->fetch_assoc()['total'];
                    $favStmt->close();
                    ?>
                    <div class="stat-card">
                        <i class="fas fa-heart fa-2x"></i>
                        <h3><?php echo $favCount; ?></h3>
                        <p>Canzoni preferite</p>
                    </div>
                    <?php
                    $dataStmt = $myDB->prepare("SELECT Data FROM utenti WHERE Id = ?");
                    $dataStmt->bind_param('i', $_SESSION["id"]);
                    $dataStmt->execute();
                    $dataQuery = $dataStmt->get_result();
                    $userData = $dataQuery->fetch_assoc();
                    $dataStmt->close();
                    $dateJoined = new DateTime($userData['Data']);
                    $now = new DateTime();
                    $interval = $dateJoined->diff($now);
                    ?>
                    <div class="stat-card">
                        <i class="fas fa-calendar fa-2x"></i>
                        <h3><?php echo $interval->days; ?></h3>
                        <p>Giorni su TOP MUSIC</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading" class="loading-overlay">
        <div class="loading-content">
            <canvas id="pizza" width="210" height="210"></canvas>
            <p>Caricamento in corso...</p>
        </div>
    </div>

    <script>
        function openTab(tabName) {
            // Nascondi tutti i contenuti dei tab
            var contents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < contents.length; i++) {
                contents[i].classList.remove('active');
            }
            
            // Rimuovi la classe active da tutti i bottoni
            var buttons = document.getElementsByClassName('tab-button');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove('active');
            }
            
            // Mostra il contenuto del tab selezionato
            document.getElementById(tabName).classList.add('active');
            
            // Attiva il bottone corrispondente
            event.currentTarget.classList.add('active');
        }

        function showLoading() {
            document.getElementById('loading').style.display = 'block';
            startPizzaAnimation();
        }

        function toggleFavorite(songId) {
            const button = document.getElementById('fav-' + songId);
            if (button.classList.contains('active')) {
                // Rimuovi dai preferiti
                fetch('api/favorite.php?action=remove&Id=' + songId)
                    .then(response => response.text())
                    .then(html => {
                        button.classList.remove('active');
                    })
                    .catch(error => console.error('Errore:', error));
            } else {
                // Aggiungi ai preferiti
                fetch('api/favorite.php?action=add&Id=' + songId)
                    .then(response => response.text())
                    .then(html => {
                        button.classList.add('active');
                    })
                    .catch(error => console.error('Errore:', error));
            }
        }

        function deleteSong(songId, songName) {
            if (confirm('Sei sicuro di voler cancellare ' + songName + '?')) {
                // Usa l'API moderna delete_song.php
                fetch('api/delete_song.php?idc=' + songId)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else if (response.status === 401) {
                            alert('Sessione scaduta. Effettua il login.');
                            window.location.href = 'login.php';
                        } else if (response.status === 403) {
                            alert('Non hai permessi per cancellare questa canzone.');
                        } else {
                            throw new Error('Errore nella cancellazione');
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Canzone cancellata con successo');
                            location.reload(); // Ricarica la pagina
                        } else {
                            alert('Errore: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Errore:', error);
                        alert('Errore nella cancellazione della canzone.');
                    });
            }
        }

        // Animazione pizza durante il caricamento
        function startPizzaAnimation() {
            const canvas = document.getElementById('pizza');
            const ctx = canvas.getContext('2d');
            let rotation = 0;

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.save();
                ctx.translate(canvas.width/2, canvas.height/2);
                ctx.rotate(rotation);
                
                // Disegna la pizza
                ctx.beginPath();
                ctx.arc(0, 0, 80, 0, Math.PI * 2);
                ctx.fillStyle = '#f5d742';
                ctx.fill();
                
                // Disegna le fette
                for(let i = 0; i < 8; i++) {
                    ctx.rotate(Math.PI/4);
                    ctx.beginPath();
                    ctx.moveTo(0, 0);
                    ctx.lineTo(80, 0);
                    ctx.strokeStyle = '#c17f16';
                    ctx.stroke();
                }
                
                ctx.restore();
                rotation += 0.05;
                requestAnimationFrame(animate);
            }
            
            animate();
        }
    </script>
</body>
</html>