# 🛠️ GUIDA PRATICA - TOP MUSIC CODE EXAMPLES

**Esempi di codice, snippet e how-to pratici**

---

## 📑 INDICE

1. [Uso API Endpoints](#uso-api-endpoints)
2. [JavaScript Frontend](#javascript-frontend)
3. [Query Database Comuni](#query-database-comuni)
4. [Validazione Input](#validazione-input)
5. [Gestione File](#gestione-file)
6. [Error Handling](#error-handling)
7. [Testing Manuale](#testing-manuale)

---

## 🔌 USO API ENDPOINTS

### 1. Registrazione Utente

#### Client Side (HTML)
```html
<!-- /login.php -->
<form class="register-form" method="post" action="api/register.php">
    <input type="text" 
           placeholder="Nome Completo" 
           name="nme" 
           maxlength="34" 
           required>
    
    <input type="text" 
           placeholder="Username" 
           name="usr" 
           maxlength="34" 
           minlength="8" 
           required>
    
    <input type="password" 
           placeholder="Password" 
           name="psw" 
           minlength="8" 
           required>
    
    <button type="submit">Registrati</button>
</form>
```

#### Server Side Processing
```php
// /api/register.php
<?php
session_start();
require_once("../config.php");

// Metodo POST check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Location: ../login.php");
    exit();
}

// Connessione DB
$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($conn->connect_errno) {
    $_SESSION["errore_register0"] = true;
    header("Location: ../login.php");
    exit();
}

// Validazione input
$username = trim($_POST['usr'] ?? '');
$password = $_POST['psw'] ?? '';
$nome = trim($_POST['nme'] ?? '');

// Sanitize
$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
$nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');

// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$data = date("d-m-Y");
$ora = date("H:i:s");

// Check username duplicato
$stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($existing_id);
$stmt->fetch();
$stmt->close();

if ($existing_id !== null) {
    $_SESSION["errore_register"] = true;
    header("Location: ../login.php");
    exit();
}

// Insert nuovo utente
$stmt = $conn->prepare("INSERT INTO utenti (username, password, nome, Data, Ora) 
                       VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $username, $hashedPassword, $nome, $data, $ora);
$success = $stmt->execute();
$stmt->close();
$conn->close();

if ($success) {
    header("Location: ../login.php");
} else {
    $_SESSION["errore_register0"] = true;
    header("Location: ../login.php");
}
?>
```

### 2. Login Utente

#### Client Side
```html
<form class="login-form" method="post" action="api/auth.php">
    <input type="text" 
           placeholder="Username" 
           name="usr" 
           maxlength="34" 
           required>
    
    <input type="password" 
           placeholder="Password" 
           name="pwd" 
           minlength="8" 
           required>
    
    <button type="submit">Accedi</button>
</form>
```

#### Server Side
```php
// /api/auth.php
<?php
session_start();
require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Location: ../login.php");
    exit();
}

$mydb = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($mydb->connect_errno) {
    $_SESSION["errore_login"] = true;
    header("Location: ../login.php");
    exit();
}

$username = trim($_POST["usr"] ?? '');
$password = $_POST["pwd"] ?? '';

// Prepared statement - SICURO contro SQL Injection
$stmt = $mydb->prepare("SELECT id, nome, password FROM utenti WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($id, $nome, $stored_password);

$login_success = false;

while ($stmt->fetch()) {
    // Supporta BCRYPT (nuovo) e MD5 (legacy)
    if (password_verify($password, $stored_password) || 
        md5($password) == $stored_password) {
        
        unset($_SESSION["errore_login"]);
        $_SESSION["nome"] = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
        $_SESSION["id"] = $id;
        $login_success = true;
        break;
    }
}

$stmt->close();
$mydb->close();

if ($login_success) {
    header("Location: ../profilo.php");
} else {
    $_SESSION["errore_login"] = true;
    header("Location: ../login.php");
}
exit();
?>
```

### 3. Upload Canzone

#### HTML Form
```html
<!-- /profilo.php -->
<form method="POST" 
      action="api/upload_song.php" 
      enctype="multipart/form-data" 
      id="upload-form">
    
    <!-- Cover Image -->
    <input type="file" 
           name="IMG" 
           accept=".jpg,.jpeg,.png,.pjp,.pjpeg,.jfif" 
           required>
    <label>Cover (JPG/PNG - Max 2MB)</label>
    
    <!-- Audio/Video File -->
    <input type="file" 
           name="VID" 
           accept=".mp3,.mp4,.m4v,.m4a" 
           required>
    <label>File Audio/Video (MP3/MP4 - Max 20MB)</label>
    
    <!-- Metadati -->
    <input type="text" 
           name="NomeCanzone" 
           placeholder="Nome canzone" 
           maxlength="30" 
           required>
    
    <input type="text" 
           name="Autore" 
           placeholder="Nome autore" 
           maxlength="30" 
           required>
    
    <button type="submit">Carica Canzone</button>
</form>

<script>
// Validazione client-side (prima di inviare)
document.getElementById('upload-form').addEventListener('submit', function(e) {
    const img = document.querySelector('input[name="IMG"]').files[0];
    const vid = document.querySelector('input[name="VID"]').files[0];
    
    // Check file sizes
    if (img && img.size > 2097152) {
        e.preventDefault();
        alert('Immagine troppo grande (max 2MB)');
        return;
    }
    
    if (vid && vid.size > 20971509) {
        e.preventDefault();
        alert('File audio/video troppo grande (max 20MB)');
        return;
    }
});
</script>
```

#### Server Side Processing
```php
// /api/upload_song.php
<?php
session_start();
require_once("../config.php");

// 1. AUTENTICAZIONE
if (!isset($_SESSION["id"])) {
    http_response_code(401);
    die(json_encode(["error" => "Non autorizzato"]));
}

// 2. METODO CHECK
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(["error" => "Metodo non consentito"]));
}

// 3. DATABASE CONNECTION
$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($myDB->connect_errno) {
    http_response_code(500);
    die(json_encode(["error" => "Errore database"]));
}

$errors = [];
$IMG_name = null;
$VID_name = null;

// 4. VALIDAZIONE COVER IMAGE
if (isset($_FILES['IMG']) && $_FILES['IMG']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['IMG'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    $allowed_img = ['png', 'jpg', 'jpeg', 'pjp', 'pjpeg', 'jfif'];
    
    if (!in_array($ext, $allowed_img)) {
        $errors[] = "Formato immagine non valido. Formati supportati: " . 
                    implode(", ", $allowed_img);
    } elseif ($file['size'] > 2097152) { // 2MB
        $errors[] = "Immagine troppo grande (max 2MB)";
    } else {
        // Nome univoco con timestamp
        $IMG_name = uniqid('img_', true) . '.' . $ext;
        $destination = '../IMG/COVER/' . $IMG_name;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $errors[] = "Errore nel caricamento dell'immagine";
        }
    }
} else {
    $errors[] = "Immagine richiesta";
}

// 5. VALIDAZIONE FILE AUDIO/VIDEO
if (isset($_FILES['VID']) && $_FILES['VID']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['VID'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    $allowed_vid = ['mp3', 'mp4', 'm4v', 'm4a'];
    
    if (!in_array($ext, $allowed_vid)) {
        $errors[] = "Formato file non valido. Formati supportati: " . 
                    implode(", ", $allowed_vid);
    } elseif ($file['size'] > 20971509) { // ~20MB
        $errors[] = "File troppo grande (max 20MB)";
    } else {
        $VID_name = uniqid('vid_', true) . '.' . $ext;
        $destination = '../VID/' . $VID_name;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $errors[] = "Errore nel caricamento del file";
            // Pulisci IMG se upload fallisce
            if ($IMG_name) unlink('../IMG/COVER/' . $IMG_name);
        }
    }
} else {
    $errors[] = "File audio/video richiesto";
}

// 6. VALIDAZIONE METADATI
$nome_canzone = trim($_POST['NomeCanzone'] ?? '');
$autore = trim($_POST['Autore'] ?? '');

if (empty($nome_canzone)) $errors[] = "Nome canzone richiesto";
if (empty($autore)) $errors[] = "Nome autore richiesto";

// 7. SE ERRORI, RISPONDI CON ERROR
if (!empty($errors)) {
    // Pulisci file caricati
    if ($IMG_name) @unlink('../IMG/COVER/' . $IMG_name);
    if ($VID_name) @unlink('../VID/' . $VID_name);
    
    http_response_code(400);
    die(json_encode([
        "success" => false,
        "errors" => $errors
    ]));
}

// 8. SANITIZE METADATI
$nome_canzone = htmlspecialchars($nome_canzone, ENT_QUOTES, 'UTF-8');
$autore = htmlspecialchars($autore, ENT_QUOTES, 'UTF-8');

// 9. INSERT INTO DATABASE
$stmt = $myDB->prepare("INSERT INTO canzoni 
                       (NomeCanzone, Autore, IMG, VID, fkUser) 
                       VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('ssssi', $nome_canzone, $autore, $IMG_name, $VID_name, $_SESSION["id"]);

if ($stmt->execute()) {
    $song_id = $myDB->insert_id;
    $stmt->close();
    $myDB->close();
    
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => "Canzone caricata con successo",
        "song_id" => $song_id
    ]);
} else {
    // Errore DB - pulisci file
    @unlink('../IMG/COVER/' . $IMG_name);
    @unlink('../VID/' . $VID_name);
    
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Errore nel salvataggio del database"
    ]);
}
?>
```

### 4. Ricerca Canzoni

#### AJAX Request (Client)
```javascript
// /JS/scriptIndex.js

// Setup ricerca
const searchInput = document.querySelector('#box input');
const searchResults = document.querySelector('#box .result');

// AJAX live search
searchInput.addEventListener('keyup', function() {
    let query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.innerHTML = '';
        return;
    }
    
    $.ajax({
        url: 'api/search.php',
        type: 'GET',
        data: { q: query },
        dataType: 'json',
        success: function(data) {
            displaySearchResults(data);
        },
        error: function() {
            searchResults.innerHTML = '<p>Errore nella ricerca</p>';
        }
    });
});

function displaySearchResults(songs) {
    let html = '';
    
    songs.forEach(song => {
        html += `
            <div class="search-result-item">
                <img src="IMG/COVER/${song.IMG}" alt="Cover">
                <div class="song-info">
                    <p class="song-name">${song.NomeCanzone}</p>
                    <p class="song-artist">${song.Autore}</p>
                </div>
                <a href="Player.php?Id=${song.Id}" class="play-btn">
                    ▶ Riproduci
                </a>
            </div>
        `;
    });
    
    searchResults.innerHTML = html;
}
```

#### API Endpoint (Server)
```php
// /api/search.php
<?php
require_once("../config.php");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die(json_encode(["error" => "Metodo non consentito"]));
}

$query = trim($_GET['q'] ?? '');

if (empty($query) || strlen($query) < 2) {
    http_response_code(400);
    die(json_encode(["error" => "Query troppo corta"]));
}

$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($myDB->connect_errno) {
    http_response_code(500);
    die(json_encode(["error" => "Errore database"]));
}

// Prepared statement - sicuro contro SQL injection
$search_term = "%" . $query . "%";
$stmt = $myDB->prepare("SELECT Id, NomeCanzone, Autore, IMG, VID, fkUser 
                       FROM canzoni 
                       WHERE NomeCanzone LIKE ? OR Autore LIKE ? 
                       ORDER BY NomeCanzone ASC 
                       LIMIT 20");

$stmt->bind_param('ss', $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();

$songs = [];
while ($row = $result->fetch_assoc()) {
    $songs[] = $row;
}

$stmt->close();
$myDB->close();

header('Content-Type: application/json');
echo json_encode($songs);
?>
```

---

## 🎨 JAVASCRIPT FRONTEND

### 1. Favoriti Add/Remove

```javascript
// /JS/scriptIndex.js

// Aggiungi canzone ai preferiti
function aggiungi(songId) {
    $.ajax({
        url: 'api/favorite.php',
        type: 'POST',
        data: {
            action: 'add',
            song_id: songId
        },
        success: function(response) {
            let button = $('#bottone' + songId);
            let container = $('#bottone_colore' + songId);
            
            // Cambia colore a gold (preferito)
            button.css('background', 'gold');
            
            // Cambia onclick function
            button.attr('onClick', 'elimina(' + songId + ')');
            
            // Feedback visivo
            console.log('Canzone aggiunta ai preferiti');
        },
        error: function() {
            alert('Errore nell\'aggiungere ai preferiti');
        }
    });
}

// Rimuovi canzone dai preferiti
function elimina(songId) {
    $.ajax({
        url: 'api/favorite.php',
        type: 'POST',
        data: {
            action: 'remove',
            song_id: songId
        },
        success: function(response) {
            let button = $('#bottone' + songId);
            
            // Cambia colore a grey (non preferito)
            button.css('background', 'grey');
            
            // Cambia onclick function
            button.attr('onClick', 'aggiungi(' + songId + ')');
            
            console.log('Canzone rimossa dai preferiti');
        },
        error: function() {
            alert('Errore nel rimuovere dai preferiti');
        }
    });
}
```

### 2. Player Controls

```javascript
// /JS/scriptPlayer.js

class MusicPlayer {
    constructor() {
        this.audio = document.querySelector('audio');
        this.playBtn = document.querySelector('#play-btn');
        this.pauseBtn = document.querySelector('#pause-btn');
        this.volumeControl = document.querySelector('#volume');
        this.progressBar = document.querySelector('#progress');
        this.currentTime = document.querySelector('#current-time');
        this.duration = document.querySelector('#duration');
    }
    
    init() {
        this.playBtn.addEventListener('click', () => this.play());
        this.pauseBtn.addEventListener('click', () => this.pause());
        this.volumeControl.addEventListener('input', (e) => this.setVolume(e));
        this.progressBar.addEventListener('input', (e) => this.seek(e));
        
        // Update UI when audio plays
        this.audio.addEventListener('timeupdate', () => this.updateProgress());
        this.audio.addEventListener('loadedmetadata', () => this.updateDuration());
    }
    
    play() {
        this.audio.play();
        this.playBtn.style.display = 'none';
        this.pauseBtn.style.display = 'inline';
    }
    
    pause() {
        this.audio.pause();
        this.playBtn.style.display = 'inline';
        this.pauseBtn.style.display = 'none';
    }
    
    setVolume(e) {
        this.audio.volume = e.target.value / 100;
    }
    
    seek(e) {
        this.audio.currentTime = (e.target.value / 100) * this.audio.duration;
    }
    
    updateProgress() {
        if (this.audio.duration) {
            let percent = (this.audio.currentTime / this.audio.duration) * 100;
            this.progressBar.value = percent;
            this.currentTime.textContent = this.formatTime(this.audio.currentTime);
        }
    }
    
    updateDuration() {
        this.duration.textContent = this.formatTime(this.audio.duration);
    }
    
    formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';
        
        let minutes = Math.floor(seconds / 60);
        let secs = Math.floor(seconds % 60);
        
        return minutes + ':' + (secs < 10 ? '0' : '') + secs;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    const player = new MusicPlayer();
    player.init();
});
```

### 3. Form Toggle (Login/Register)

```javascript
// /JS/scriptLogin.js

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');
    const loginLink = document.querySelector('.login-form .message a');
    const registerLink = document.querySelector('.register-form .message a');
    
    // Toggle tra login e registrazione
    loginLink.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
    });
    
    registerLink.addEventListener('click', function(e) {
        e.preventDefault();
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
    });
    
    // Validazione client-side
    registerForm.addEventListener('submit', function(e) {
        const username = this.querySelector('input[name="usr"]').value;
        const password = this.querySelector('input[name="psw"]').value;
        
        if (username.length < 8) {
            e.preventDefault();
            alert('Username minimo 8 caratteri');
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password minimo 8 caratteri');
        }
    });
});
```

---

## 📊 QUERY DATABASE COMUNI

### 1. SELECT - Recupera Canzoni

```php
// Tutte le canzoni (homepage)
$stmt = $myDB->prepare("SELECT Id, NomeCanzone, Autore, IMG, VID, fkUser 
                       FROM canzoni 
                       ORDER BY Id DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Process $row
}
```

```php
// Canzoni di uno specifico utente
$stmt = $myDB->prepare("SELECT Id, NomeCanzone, Autore, IMG, VID 
                       FROM canzoni 
                       WHERE fkUser = ? 
                       ORDER BY Id DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
```

```php
// Una specifica canzone
$stmt = $myDB->prepare("SELECT Id, NomeCanzone, Autore, IMG, VID, fkUser 
                       FROM canzoni 
                       WHERE Id = ? 
                       LIMIT 1");
$stmt->bind_param('i', $songId);
$stmt->execute();
$result = $stmt->get_result();
$song = $result->fetch_assoc();
```

### 2. SELECT - Preferiti

```php
// Check se canzone è nei preferiti dell'utente
$stmt = $myDB->prepare("SELECT 1 
                       FROM canzoniPreferite 
                       WHERE fkCanzone = ? AND fkUtente = ? 
                       LIMIT 1");
$stmt->bind_param('ii', $songId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$isFavorite = $result->num_rows > 0;
```

```php
// Tutte le canzoni preferite dell'utente
$stmt = $myDB->prepare("SELECT canzoni.* 
                       FROM canzoni 
                       INNER JOIN canzoniPreferite 
                           ON canzoni.Id = canzoniPreferite.fkCanzone 
                       WHERE canzoniPreferite.fkUtente = ? 
                       ORDER BY canzoniPreferite.id DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Process favorite song
}
```

### 3. INSERT - Aggiungi Dati

```php
// Nuovo utente
$stmt = $myDB->prepare("INSERT INTO utenti (username, password, nome, Data, Ora) 
                       VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $username, $hashed_pwd, $nome, $data, $ora);
$stmt->execute();
$newUserId = $myDB->insert_id; // Get auto-increment ID
```

```php
// Nuova canzone
$stmt = $myDB->prepare("INSERT INTO canzoni (NomeCanzone, Autore, IMG, VID, fkUser) 
                       VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('ssssi', $nome, $autore, $img, $vid, $userId);
$stmt->execute();
```

```php
// Aggiungi ai preferiti
$stmt = $myDB->prepare("INSERT INTO canzoniPreferite (fkCanzone, fkUtente) 
                       VALUES (?, ?)");
$stmt->bind_param('ii', $songId, $userId);
$stmt->execute();
```

### 4. UPDATE - Modifica Dati

```php
// Update metadati canzone
$stmt = $myDB->prepare("UPDATE canzoni 
                       SET NomeCanzone = ?, Autore = ? 
                       WHERE Id = ? AND fkUser = ?");
$stmt->bind_param('ssii', $newNome, $newAutore, $songId, $userId);
$stmt->execute();
```

### 5. DELETE - Rimuovi Dati

```php
// Rimuovi canzone
$stmt = $myDB->prepare("DELETE FROM canzoni 
                       WHERE Id = ? AND fkUser = ?");
$stmt->bind_param('ii', $songId, $userId);
$stmt->execute();
```

```php
// Rimuovi dai preferiti
$stmt = $myDB->prepare("DELETE FROM canzoniPreferite 
                       WHERE fkCanzone = ? AND fkUtente = ?");
$stmt->bind_param('ii', $songId, $userId);
$stmt->execute();
```

---

## ✅ VALIDAZIONE INPUT

### 1. Validazione Username

```php
function validateUsername($username) {
    $username = trim($username);
    
    // Check lunghezza
    if (strlen($username) < 8 || strlen($username) > 34) {
        return ["valid" => false, "error" => "Username deve essere 8-34 caratteri"];
    }
    
    // Check caratteri alfanumerici (permettere underscore)
    if (!preg_match('/^[a-zA-Z0-9_]{8,34}$/', $username)) {
        return ["valid" => false, "error" => "Username: solo lettere, numeri e underscore"];
    }
    
    return ["valid" => true];
}

// Uso
$validation = validateUsername($_POST['usr']);
if (!$validation['valid']) {
    echo $validation['error'];
}
```

### 2. Validazione Password

```php
function validatePassword($password) {
    // Check lunghezza
    if (strlen($password) < 8) {
        return ["valid" => false, "error" => "Password minimo 8 caratteri"];
    }
    
    // Opzionale: richiedi mix caratteri
    if (!preg_match('/[a-z]/', $password)) {
        return ["valid" => false, "error" => "Password deve contenere minuscole"];
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return ["valid" => false, "error" => "Password deve contenere maiuscole"];
    }
    if (!preg_match('/[0-9]/', $password)) {
        return ["valid" => false, "error" => "Password deve contenere numeri"];
    }
    
    return ["valid" => true];
}
```

### 3. Validazione Email

```php
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ["valid" => false, "error" => "Email non valida"];
    }
    
    return ["valid" => true];
}
```

### 4. Validazione File

```php
function validateImageFile($file) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'pjp', 'pjpeg', 'jfif'];
    $max_size = 2097152; // 2MB
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed_ext)) {
        return ["valid" => false, "error" => "Formato non supportato"];
    }
    
    if ($file['size'] > $max_size) {
        return ["valid" => false, "error" => "File troppo grande"];
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowed_mime = ['image/jpeg', 'image/png'];
    if (!in_array($mime, $allowed_mime)) {
        return ["valid" => false, "error" => "MIME type non valido"];
    }
    
    return ["valid" => true];
}
```

---

## 📂 GESTIONE FILE

### 1. Upload con Validazione Completa

```php
function uploadFile($file, $destination_dir, $allowed_ext, $max_size) {
    // Validazione base
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ["success" => false, "error" => "Errore upload: " . $file['error']];
    }
    
    // Estensione
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        return ["success" => false, "error" => "Estensione non consentita"];
    }
    
    // Dimensione
    if ($file['size'] > $max_size) {
        return ["success" => false, "error" => "File troppo grande"];
    }
    
    // MIME type check
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    // Generate unique filename
    $filename = uniqid('', true) . '.' . $ext;
    $filepath = $destination_dir . $filename;
    
    // Move file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ["success" => false, "error" => "Errore spostamento file"];
    }
    
    return ["success" => true, "filename" => $filename];
}

// Uso
$result = uploadFile(
    $_FILES['image'],
    '../IMG/COVER/',
    ['jpg', 'png', 'jpeg'],
    2097152
);

if ($result['success']) {
    echo "File salvato: " . $result['filename'];
} else {
    echo "Errore: " . $result['error'];
}
```

### 2. Eliminazione File

```php
function deleteFile($filepath) {
    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            return ["success" => true];
        } else {
            return ["success" => false, "error" => "Impossibile eliminare file"];
        }
    } else {
        return ["success" => false, "error" => "File non trovato"];
    }
}

// Uso
$result = deleteFile('../IMG/COVER/myimage.jpg');
```

### 3. List Files Directory

```php
function listFiles($directory, $extensions = null) {
    $files = [];
    
    if (!is_dir($directory)) {
        return ["success" => false, "error" => "Directory non trovata"];
    }
    
    $items = scandir($directory);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $filepath = $directory . $item;
        
        if (is_file($filepath)) {
            if ($extensions) {
                $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (!in_array($ext, $extensions)) continue;
            }
            
            $files[] = [
                'name' => $item,
                'size' => filesize($filepath),
                'date' => date('Y-m-d H:i:s', filemtime($filepath))
            ];
        }
    }
    
    return ["success" => true, "files" => $files];
}
```

---

## ⚠️ ERROR HANDLING

### 1. Try-Catch Pattern

```php
try {
    $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
    
    if ($myDB->connect_errno) {
        throw new Exception("Errore connessione: " . $myDB->connect_error);
    }
    
    $stmt = $myDB->prepare("SELECT * FROM utenti WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare error: " . $myDB->error);
    }
    
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        throw new Exception("Execute error: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    $stmt->close();
    $myDB->close();
    
    // Success
    echo json_encode(["success" => true, "user" => $user]);
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Errore nel processare la richiesta"
    ]);
}
```

### 2. Custom Error Handler

```php
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $error_message = "Error [$errno]: $errstr in $errfile on line $errline";
    error_log($error_message);
    
    // Non mostrare errori in produzione
    if (getenv('APP_ENV') === 'production') {
        http_response_code(500);
        echo json_encode(["error" => "Errore interno server"]);
    } else {
        echo $error_message;
    }
}

set_error_handler("customErrorHandler");
```

### 3. Logging Pattern

```php
function log_event($message, $level = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message\n";
    
    error_log($log_entry, 3, __DIR__ . '/logs/app.log');
}

// Uso
log_event("User login: " . $_SESSION['id'], 'INFO');
log_event("Database error: " . $myDB->error, 'ERROR');
log_event("SQL injection attempt detected", 'SECURITY');
```

---

## 🧪 TESTING MANUALE

### 1. Test Registrazione

```
1. Vai a http://localhost/topmusic/login.php
2. Form "Registrazione"
3. Inserisci:
   - Nome: Test User
   - Username: testuser123 (minimo 8 chars)
   - Password: SecurePass123 (minimo 8 chars)
4. Click "Registrati"
5. Verifica: username non duplicato
6. Verifica DB: SELECT * FROM utenti WHERE username='testuser123'
```

### 2. Test Login

```
1. Vai a http://localhost/topmusic/login.php
2. Form "Accedi"
3. Inserisci:
   - Username: testuser123
   - Password: SecurePass123
4. Click "Login"
5. Verifica redirect a profilo.php
6. Verifica $_SESSION['id'] e $_SESSION['nome'] impostati
```

### 3. Test Upload Canzone

```
1. Login come utente
2. Vai a Profilo → Tab "Carica Canzone"
3. Seleziona file:
   - Cover: immagine JPG/PNG (< 2MB)
   - Audio: file MP3/MP4 (< 20MB)
4. Inserisci:
   - Nome canzone: Test Song
   - Autore: Test Artist
5. Click "Carica"
6. Verifica success message
7. Verifica file in IMG/COVER/ e VID/
8. Verifica INSERT in DB: SELECT * FROM canzoni WHERE NomeCanzone='Test Song'
```

### 4. Test Ricerca

```
1. Vai a Homepage
2. Click pulsante ricerca 🔍
3. Digita nome canzone (es: "Without")
4. Verifica risultati AJAX in tempo reale
5. Click su risultato → Player.php?Id=X
```

### 5. Test Preferiti

```
1. Homepage
2. Click pulsante star grigio accanto a canzone
3. Verifica: si riempie (background gold)
4. Vai a Preferiti (cuore in alto)
5. Verifica: canzone è nella lista
6. Click star oro per rimuovere
7. Verifica: sparisce da Preferiti
```

### 6. Test Player

```
1. Homepage → Click copertina canzone
2. Verifica Player.php carica
3. Test controls:
   - Click Play ▶
   - Verifica audio riproduce
   - Regola volume
   - Sposta timeline
   - Click Pause ⏸
```

### 7. Database Connection Test

```php
// /test_db.php
<?php
require_once("config.php");
$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);

if ($myDB->connect_errno) {
    die("Errore: " . $myDB->connect_error);
}

echo "✓ Connessione riuscita<br>";
echo "Host: " . SERVER . "<br>";
echo "Database: " . DATABASE . "<br>";

$result = $myDB->query("SELECT COUNT(*) as count FROM canzoni");
$row = $result->fetch_assoc();
echo "Canzoni nel DB: " . $row['count'] . "<br>";

$myDB->close();
?>
```

---

**Versione**: 1.0 | **Data**: 14 Novembre 2025
