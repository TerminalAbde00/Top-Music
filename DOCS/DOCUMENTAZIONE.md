# 📚 DOCUMENTAZIONE COMPLETA - TOP MUSIC

**Piattaforma di Condivisione Musicale**  
**Data**: Novembre 2025  
**Versione**: 1.0 (Post P0 Security Fixes)

---

## 📑 INDICE

1. [Panoramica Progetto](#panoramica-progetto)
2. [Architettura Tecnica](#architettura-tecnica)
3. [Database](#database)
4. [API Reference](#api-reference)
5. [Autenticazione](#autenticazione)
6. [Upload e Media](#upload-e-media)
7. [Guida Installazione](#guida-installazione)
8. [Guida Sviluppatori](#guida-sviluppatori)
9. [Sicurezza](#sicurezza)
10. [Troubleshooting](#troubleshooting)

---

## 🎯 PANORAMICA PROGETTO

### Descrizione
**TOP MUSIC** è una web application che consente agli utenti di:
- 📤 Caricare canzoni (MP3, MP4) con cover artwork
- 🎵 Ascoltare musica tramite player integrato
- ⭐ Salvare canzoni preferite
- 🔍 Ricercare canzoni in tempo reale
- 🚨 Segnalare contenuti inappropriati
- 👨‍💼 Gestire il proprio profilo

### Stack Tecnologico
```
Frontend:  HTML5, CSS3, JavaScript (jQuery 3.6+)
Backend:   PHP 8.0+
Database:  MySQL 8.0+
Server:    Apache (XAMPP)
```

### Caratteristiche Chiave
- ✅ Autenticazione sicura con BCRYPT hashing
- ✅ Prepared Statements per SQL Injection Prevention
- ✅ Upload file validati con whitelist estensioni
- ✅ Session-based authorization
- ✅ Responsive design (mobile-first)
- ✅ AJAX ricerca live

---

## 🏗️ ARCHITETTURA TECNICA

### Struttura Directory

```
c:\xampp\htdocs\
├── 📄 index.php                    # Homepage principale
├── 📄 login.php                    # Login/Registrazione
├── 📄 Player.php                   # Player multimediale
├── 📄 profilo.php                  # Profilo utente + upload
├── 📄 fav.php                      # Pagina preferiti
├── 📄 segnala.php                  # Form segnalazioni
├── 📄 config.php                   # Configurazione DB
│
├── 📁 api/                         # API RESTful
│   ├── auth.php                    # Login endpoint
│   ├── register.php                # Registrazione endpoint
│   ├── upload_song.php             # Upload endpoint
│   ├── search.php                  # Ricerca endpoint
│   ├── favorite.php                # Gestione preferiti
│   ├── delete_song.php             # Eliminazione canzoni
│   └── logout.php                  # Logout endpoint
│
├── 📁 Admin/                       # Pannello amministrazione
│   ├── index.php                   # Dashboard admin
│   ├── ModificaAdmin.php           # Modifica canzoni
│   ├── Script_Update.php           # Script update DB
│   ├── Script_EliminaAdmin.php     # Elimina utenti
│   ├── Script_CancellaSegnalazione.php  # Moderation
│   └── localhost.sql               # Database dump
│
├── 📁 CSS/                         # Fogli di stile
│   ├── style_HomeOk.css            # Homepage
│   ├── style_Player.css            # Player
│   ├── style_profile.css           # Profilo
│   ├── style_Caricamentoo.css      # Upload/Login
│   └── style_SegnalazioniOk.css    # Segnalazioni
│
├── 📁 JS/                          # JavaScript
│   ├── scriptIndex.js              # Homepage logic
│   ├── scriptPlayer.js             # Player controls
│   └── scriptLogin.js              # Auth UI toggle
│
├── 📁 IMG/                         # Immagini
│   ├── COVER/                      # Cover canzoni (.jpg, .png)
│   └── PAGINA/                     # Assets UI (logo, icone)
│
├── 📁 VID/                         # Media files
│   ├── *.mp3                       # Audio files
│   ├── *.mp4                       # Video files
│   └── loop/                       # Background videos
│
└── 📁 old_scripts/                 # Script deprecati (da rimuovere)
```

### Flow Diagrams

#### 1. User Registration Flow
```
login.php (register-form)
    ↓ POST
api/register.php
    ├─ Validazione input (username, password, nome)
    ├─ Check username duplicato
    ├─ Hash password con BCRYPT
    └─ INSERT INTO utenti
        └─ Redirect login.php
```

#### 2. User Login Flow
```
login.php (login-form)
    ↓ POST
api/auth.php
    ├─ SELECT FROM utenti WHERE username = ?
    ├─ password_verify() (supporta BCRYPT + MD5 fallback)
    ├─ $_SESSION["id"] = $user_id
    ├─ $_SESSION["nome"] = $user_name
    └─ Redirect profilo.php
```

#### 3. Upload Song Flow
```
profilo.php (upload-form)
    ↓ POST multipart/form-data
api/upload_song.php
    ├─ Verifica autenticazione ($_SESSION["id"])
    ├─ Validazione IMG (PNG, JPG max 2MB)
    ├─ Validazione VID (MP3, MP4 max 20MB)
    ├─ Genera nomi unici con uniqid()
    ├─ move_uploaded_file() → IMG/COVER/ + VID/
    ├─ Sanitizza NomeCanzone, Autore
    ├─ INSERT INTO canzoni
    └─ JSON response success/error
```

#### 4. Search Flow
```
index.php / fav.php
    ↓ AJAX input typing
JS/scriptIndex.js
    ↓ $.ajax() GET
api/search.php
    ├─ Prepared Statement LIKE query
    ├─ SELECT canzoni
    └─ JSON response array
```

---

## 🗄️ DATABASE

### Schema Principale

#### Tabella: `utenti`
```sql
CREATE TABLE utenti (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  username        VARCHAR(34) UNIQUE NOT NULL,
  password        VARCHAR(255) NOT NULL,        -- BCRYPT hash (60 chars)
  nome            VARCHAR(34) NOT NULL,
  email           VARCHAR(100),
  Data            VARCHAR(20),                  -- Format: "DD-MM-YYYY"
  Ora             VARCHAR(20)                   -- Format: "HH:MM:SS"
);
```

#### Tabella: `canzoni`
```sql
CREATE TABLE canzoni (
  Id              INT PRIMARY KEY AUTO_INCREMENT,
  NomeCanzone     VARCHAR(30) NOT NULL,
  Autore          VARCHAR(30) NOT NULL,
  IMG             VARCHAR(50) NOT NULL,         -- Filename in IMG/COVER/
  VID             VARCHAR(50),                  -- Filename in VID/
  fkUser          INT DEFAULT NULL,             -- FK to utenti(id)
  FOREIGN KEY (fkUser) REFERENCES utenti(id)
);
```

#### Tabella: `canzoniPreferite`
```sql
CREATE TABLE canzoniPreferite (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  fkCanzone       INT NOT NULL,                 -- FK to canzoni(id)
  fkUtente        INT NOT NULL,                 -- FK to utenti(id)
  FOREIGN KEY (fkCanzone) REFERENCES canzoni(id),
  FOREIGN KEY (fkUtente) REFERENCES utenti(id)
);
```

#### Tabella: `segnalazioni`
```sql
CREATE TABLE segnalazioni (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  fkCanzone       INT,
  motivo          VARCHAR(255),
  email           VARCHAR(100),
  descrizione     TEXT,
  data_creazione  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Indici Consigliati
```sql
CREATE INDEX idx_username ON utenti(username);
CREATE INDEX idx_canzone_autore ON canzoni(NomeCanzone, Autore);
CREATE INDEX idx_fav_utente ON canzoniPreferite(fkUtente);
CREATE INDEX idx_fav_canzone ON canzoniPreferite(fkCanzone);
```

---

## 🔌 API REFERENCE

### 1. AUTENTICAZIONE

#### POST `/api/auth.php` - Login
**Descrizione**: Effettua login utente

**Request**:
```html
<form method="POST" action="/api/auth.php">
  <input name="usr" type="text">    <!-- username -->
  <input name="pwd" type="password"> <!-- password -->
</form>
```

**Response**:
- **Success**: Redirect a `profilo.php` + Session ID impostato
- **Error**: Redirect a `login.php` con `$_SESSION["errore_login"] = true`

**Error Handling**:
```php
if(isset($_SESSION["errore_login"]) && $_SESSION["errore_login"]==true){
    echo '<p class="error-message">Username o Password errati!</p>';
    unset($_SESSION["errore_login"]);
}
```

---

#### POST `/api/register.php` - Registrazione
**Descrizione**: Crea nuovo account utente

**Request**:
```html
<form method="POST" action="/api/register.php">
  <input name="nme" type="text">    <!-- nome completo (max 34 chars) -->
  <input name="usr" type="text">    <!-- username (8-34 chars) -->
  <input name="psw" type="password"> <!-- password (min 8 chars) -->
</form>
```

**Validazione Lato Server**:
- Username: 8-34 caratteri, unico nel DB
- Password: Minimo 8 caratteri, hashed con BCRYPT
- Nome: Max 34 caratteri

**Response**:
- **Success**: Redirect a `login.php` con redirect automatico ai tab corretti
- **Error**: `$_SESSION["errore_register"] = true` + Redirect

**Errori Possibili**:
```
1. Metodo non POST            → HTTP 405
2. DB connection error        → HTTP 500
3. Username già esistente     → Session error + display message
4. Campi vuoti/invalidi       → Session error
```

---

#### GET `/api/logout.php` - Logout
**Descrizione**: Termina sessione utente

**Response**:
```php
// Destroy session
session_destroy();
// Regenerate ID
session_start();
session_regenerate_id(true);
// Redirect
header("Location: ../index.php");
```

---

### 2. CANZONI

#### POST `/api/upload_song.php` - Upload Canzone
**Descrizione**: Carica nuova canzone con cover artwork

**Autenticazione**: Richiesta `$_SESSION["id"]` (user loggato)

**Request**:
```html
<form method="POST" action="/api/upload_song.php" enctype="multipart/form-data">
  <input name="IMG" type="file" accept=".jpg,.jpeg,.png,.pjp,.pjpeg,.jfif">
  <input name="VID" type="file" accept=".mp3,.mp4,.m4v,.m4a">
  <input name="NomeCanzone" type="text" maxlength="30">
  <input name="Autore" type="text" maxlength="30">
</form>
```

**Validazione File**:
```
IMG (Cover):
  - Formati: PNG, JPG, JPEG, PJP, PJPEG, JFIF
  - Max size: 2 MB (2097152 bytes)
  - Salvo in: IMG/COVER/

VID (Audio/Video):
  - Formati: MP3, MP4, M4V, M4A
  - Max size: 20 MB (20971509 bytes)
  - Salvo in: VID/
```

**Response**: JSON
```json
{
  "success": true,
  "message": "Canzone caricata con successo",
  "song_id": 123
}

// Errore
{
  "success": false,
  "error": "Descrizione errore"
}
```

**Errori Possibili**:
```
- Non autenticato           → HTTP 401
- Metodo non POST          → HTTP 405
- Formato file non valido  → "Formato immagine non valido"
- File troppo grande       → "Immagine troppo grande (max 2MB)"
- Errore upload            → "Errore nel caricamento dell'immagine"
```

---

#### GET `/api/delete_song.php?idc=123` - Elimina Canzone
**Descrizione**: Rimuove canzone dal DB e filesystems

**Parametri**:
- `idc`: ID canzone (integer)

**Permessi**: Solo proprietario canzone o admin

**Response**: Redirect a pagina precedente

---

#### GET `/api/search.php?q=termini` - Ricerca Canzoni
**Descrizione**: Ricerca live canzoni per nome/autore

**Metodo**: GET (chiamato da AJAX)

**Parametri**:
```
q: termine ricerca (obbligatorio)
```

**Query Prepared Statement**:
```sql
SELECT * FROM canzoni 
WHERE NomeCanzone LIKE ? OR Autore LIKE ? 
LIMIT 20
```

**Response**: JSON array
```json
[
  {
    "Id": 1,
    "NomeCanzone": "Gatti",
    "Autore": "Pop Smoke & Travis Scott",
    "IMG": "5.jpg",
    "VID": "pop.gatti.mp4",
    "fkUser": 18
  },
  ...
]
```

---

### 3. PREFERITI

#### POST `/api/favorite.php` - Aggiungi/Rimuovi Preferito
**Descrizione**: Gestisce lista preferiti utente

**Request**: AJAX POST
```javascript
$.ajax({
  url: 'api/favorite.php',
  type: 'POST',
  data: {
    action: 'add',      // 'add' o 'remove'
    song_id: 123
  }
});
```

**Logica Lato Client** (JS):
```javascript
function aggiungi(id) {
  $.ajax({
    url: 'api/favorite.php',
    type: 'POST',
    data: { 
      action: 'add', 
      song_id: id 
    },
    success: function(response) {
      // Update UI button color to gold
      $('#bottone' + id).css('background', 'gold');
      // Change onclick to elimina
      $('#bottone' + id).attr('onclick', 'elimina(' + id + ')');
    }
  });
}

function elimina(id) {
  $.ajax({
    url: 'api/favorite.php',
    type: 'POST',
    data: { 
      action: 'remove', 
      song_id: id 
    },
    success: function(response) {
      // Update UI button color to grey
      $('#bottone' + id).css('background', 'grey');
      // Change onclick to aggiungi
      $('#bottone' + id).attr('onclick', 'aggiungi(' + id + ')');
    }
  });
}
```

---

## 🔐 AUTENTICAZIONE

### Password Security

#### Registration Hash
```php
// Registrazione: BCRYPT
$hashedPassword = password_hash($_POST['psw'], PASSWORD_BCRYPT);
// INSERT INTO utenti (username, password, ...) VALUES (?, ?, ...)
```

**Parametri BCRYPT**:
- Algorithm: `PASSWORD_BCRYPT`
- Cost: 10 (default)
- Salt: Auto-generated

#### Login Verification
```php
// Login: password_verify() con fallback MD5
if (password_verify($password, $stored_password) || md5($password) == $stored_password) {
    // Login success
    $_SESSION["id"] = $user_id;
}
```

**Supporto Legacy**:
- Utenti nuovi: Password con BCRYPT
- Utenti vecchi: Login possibile con MD5
- Upgrade: Prossimo cambio password → BCRYPT

### Session Management

#### Inizio Sessione
```php
session_start();
$_SESSION["id"] = $user_id;
$_SESSION["nome"] = $user_name;
```

#### Check Autenticazione
```php
// In profilo.php, upload.php, etc.
if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit();
}
```

#### Logout
```php
session_destroy();
session_regenerate_id(true);
```

---

## 📤 UPLOAD E MEDIA

### Upload Song Workflow

1. **Form Submission** (`profilo.php`)
```html
<form method="POST" action="api/upload_song.php" 
      enctype="multipart/form-data">
  <input type="file" name="IMG" required>
  <input type="file" name="VID" required>
  <input type="text" name="NomeCanzone" required>
  <input type="text" name="Autore" required>
  <button type="submit">Carica Canzone</button>
</form>
```

2. **Server-Side Processing** (`api/upload_song.php`)
```php
// 1. Verifica autenticazione
if (!isset($_SESSION["id"])) die("Non autorizzato");

// 2. Validazione file IMG
if (in_array($ext, ['png','jpg','jpeg'])) {
    $newname = uniqid('', true) . '.' . $ext;
    move_uploaded_file($tmp, '../IMG/COVER/' . $newname);
}

// 3. Validazione file VID
if (in_array($ext, ['mp3','mp4','m4v','m4a'])) {
    $newname = uniqid('', true) . '.' . $ext;
    move_uploaded_file($tmp, '../VID/' . $newname);
}

// 4. Sanitize input
$nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
$autore = htmlspecialchars($autore, ENT_QUOTES, 'UTF-8');

// 5. INSERT into DB
$stmt = $myDB->prepare("INSERT INTO canzoni 
                       (NomeCanzone, Autore, IMG, VID, fkUser) 
                       VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('ssssi', $nome, $autore, $img, $vid, $userId);
```

### Naming Convention
```
IMG/COVER/:  60a1b2c3d4e5f6.12345678.jpg
VID/:        60a1b2c3d4e5f6.87654321.mp3

Format: uniqid('', true) + '.' + extension
Vantaggi:
  - Nomi unici (timestamp + random)
  - Prevenzione directory traversal
  - Facile tracking/eliminazione
```

---

## 📖 GUIDA INSTALLAZIONE

### Prerequisiti
```
- PHP 8.0+ con estensioni mysqli
- MySQL 8.0+
- Apache con mod_rewrite
- XAMPP (consigliato per development)
```

### Setup Locale (XAMPP)

#### 1. Clona/Copia Progetto
```bash
# Copia cartella progetto in:
C:\xampp\htdocs\topmusic\

# Verifica struttura
dir C:\xampp\htdocs\topmusic\
```

#### 2. Crea Database MySQL
```bash
# Apri phpMyAdmin
http://localhost/phpmyadmin

# Opzione A: Importa dump
1. Seleziona nuovo database: my_topmusic
2. Vai a "Importa"
3. Carica: Admin/localhost.sql
4. Click "Esegui"

# Opzione B: Script SQL manuale
CREATE DATABASE my_topmusic CHARACTER SET utf8mb4;
USE my_topmusic;
# ... esegui comandi da localhost.sql ...
```

#### 3. Configura Database Credentials
```php
// config.php
<?php
define("SERVER","localhost");
define("UTENTE","root");
define("PASSWORD","");
define("DATABASE","my_topmusic");
?>
```

#### 4. Verifica Permessi Directory
```bash
# Crea cartelle se non esistono
mkdir IMG\COVER
mkdir VID\loop

# Assegna permessi di scrittura (Windows):
# Proprietà → Sicurezza → Modifica → Full Control
```

#### 5. Start XAMPP
```bash
# Avvia Apache + MySQL da XAMPP Control Panel
# Verifica: http://localhost/topmusic/

# Oppure da PowerShell:
& 'C:\xampp\apache_start.bat'
& 'C:\xampp\mysql_start.bat'
```

### Accesso Iniziale

#### Admin Panel
```
URL: http://localhost/topmusic/Admin/index.php
Credentials: (Leggi Admin/index.php per password)
```

#### User Test Account
```
Username: testuser
Password: (Crea tramite /login.php)
```

---

## 👨‍💻 GUIDA SVILUPPATORI

### Aggiungere Nuova Feature

#### Esempio: Aggiungere Tab nel Profilo

1. **Modifica `profilo.php`** - HTML tab button
```html
<button class="tab-button active" onclick="openTab('tab1')">
  Le Mie Canzoni
</button>
<button class="tab-button" onclick="openTab('tab2')">
  Nova Tab
</button>
```

2. **Aggiungi contenuto tab**
```html
<div id="tab1" class="tab-content">
  <!-- Contenuto canzoni -->
</div>

<div id="tab2" class="tab-content hidden">
  <!-- Nuovo contenuto -->
</div>
```

3. **Aggiungi JS logic** (in `profilo.php` o file esterno)
```javascript
function openTab(tabName) {
    // Nascondi tutti i tab
    var tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.add('hidden'));
    
    // Mostra tab selezionato
    document.getElementById(tabName).classList.remove('hidden');
    
    // Update button active state
    var buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}
```

### Creare Nuova API Endpoint

#### Template API
```php
<?php
// /api/new_endpoint.php
session_start();
require_once("../config.php");

// ===== VALIDATION =====

// Verifica metodo HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(["error" => "Metodo non consentito"]));
}

// Verifica autenticazione (se richiesta)
if (!isset($_SESSION["id"])) {
    http_response_code(401);
    die(json_encode(["error" => "Non autorizzato"]));
}

// ===== DATABASE CONNECTION =====

$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($myDB->connect_errno) {
    http_response_code(500);
    die(json_encode(["error" => "Errore database"]));
}

// ===== INPUT VALIDATION =====

if (!isset($_POST['param1']) || empty(trim($_POST['param1']))) {
    http_response_code(400);
    die(json_encode(["error" => "Parametro richiesto: param1"]));
}

$param1 = htmlspecialchars(trim($_POST['param1']), ENT_QUOTES, 'UTF-8');

// ===== PREPARED STATEMENT =====

$stmt = $myDB->prepare("SELECT * FROM tabella WHERE campo = ?");
if (!$stmt) {
    http_response_code(500);
    die(json_encode(["error" => "Errore prepare: " . $myDB->error]));
}

$stmt->bind_param('s', $param1);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die(json_encode(["error" => "Record non trovato"]));
}

// ===== SUCCESS RESPONSE =====

$row = $result->fetch_assoc();
$stmt->close();
$myDB->close();

http_response_code(200);
echo json_encode([
    "success" => true,
    "data" => $row
]);
?>
```

### Debug & Logging

#### Error Logging
```php
// Log queries/errors
error_log("[API endpoint] " . $myDB->error);

// Development: Show errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Production: Hide errors
ini_set('display_errors', 0);
error_reporting(E_ALL);
```

#### JavaScript Debugging
```javascript
// Console logging
console.log("Variable:", variable);
console.error("Error:", error);

// Breakpoints in DevTools
// F12 → Sources → Click linea per breakpoint
```

---

## 🔒 SICUREZZA

### Minacce & Mitigazioni

| Minaccia | Stato | Mitigazione |
|----------|-------|---|
| SQL Injection | ✅ FIXED | Prepared Statements + bind_param() |
| XSS (Cross-Site Scripting) | ✅ FIXED | htmlspecialchars(ENT_QUOTES, UTF-8) |
| Password Weak | ✅ FIXED | BCRYPT con cost=10 |
| Session Hijacking | ⚠️ PARTIAL | HTTPS mancante, SameSite cookie assente |
| CSRF (Cross-Site Request Forgery) | ⚠️ NOT IMPLEMENTED | Aggiungere token CSRF |
| File Upload RCE | ✅ FIXED | Whitelist estensioni, nomi randomizzati |
| Broken Auth | ✅ FIXED | Session regeneration, password_verify() |

### Best Practices Implementate

#### 1. Input Validation
```php
// ✅ Sempre validare input
$username = trim($_POST['usr']);
if (empty($username) || strlen($username) < 8) {
    die("Username invalido");
}
```

#### 2. Output Encoding
```php
// ✅ Escaping output
echo htmlspecialchars($song['NomeCanzone'], ENT_QUOTES, 'UTF-8');
// ✅ Per URL
echo urlencode($data);
// ✅ Per JSON
echo json_encode($array);
```

#### 3. SQL Injection Prevention
```php
// ❌ WRONG
$result = $myDB->query("SELECT * FROM canzoni WHERE id = " . $_GET['id']);

// ✅ CORRECT
$stmt = $myDB->prepare("SELECT * FROM canzoni WHERE id = ?");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
```

#### 4. Password Security
```php
// ✅ Hash new passwords
$hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

// ✅ Verify on login
if (password_verify($_POST['password'], $stored_hash)) {
    // Login success
}
```

### Checklist Produzione

```
☐ HTTPS enabled (SSL certificate)
☐ Database credentials in .env (non in config.php)
☐ error_reporting(0), display_errors OFF
☐ Log file configured
☐ Database backups automatici
☐ Security headers (X-Frame-Options, CSP)
☐ Rate limiting su login/upload
☐ CSRF tokens su tutte le form
☐ Security.txt file
☐ Privacy policy + Terms of Service
☐ WAF (Web Application Firewall) configurato
☐ DDoS protection
☐ Regular security audit
```

---

## 🆘 TROUBLESHOOTING

### Errori Comuni

#### 1. "Errore di connessione al database"
```
Causa: MySQL non running o credenziali sbagliate

Soluzione:
1. Verifica XAMPP: Apache + MySQL sono ON?
2. Check config.php credenziali (root/blank di default)
3. Restart MySQL: XAMPP Control Panel → MySQL "Start"
4. Testa connexione: 
   php -r "mysqli_connect('localhost','root','','my_topmusic') or die('Error');"
```

#### 2. "Fatal error: call to undefined function mysqli_connect"
```
Causa: Estensione mysqli non caricata

Soluzione:
1. Apri php.ini (in C:\xampp\php\)
2. Cerca: ;extension=mysqli
3. Rimuovi il `;` → extension=mysqli
4. Restart Apache
```

#### 3. "Upload files failing"
```
Causa: Permessi cartella IMG/COVER/ o VID/

Soluzione (Windows):
1. Tasto destro su cartella
2. Proprietà → Sicurezza → Modifica
3. Seleziona "Users" → Full Control
4. Applica a sottocartelle/file

Soluzione (Linux):
chmod 755 IMG/COVER/
chmod 755 VID/
```

#### 4. "Session not persisting"
```
Causa: session.save_path non esiste

Soluzione:
1. Crea cartella C:\xampp\tmp\sessions
2. Modifica php.ini: session.save_path = "C:\xampp\tmp\sessions"
3. Restart Apache
```

#### 5. "Characters display as ?????"
```
Causa: Database charset issues

Soluzione:
1. Verifica config.php:
   SET NAMES utf8mb4;
2. Aggiungi a connection:
   $myDB->set_charset("utf8mb4");
3. Ricrea tabelle con: CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
```

### Debug Mode

#### Attiva Debug Logging
```php
// Aggiungi in config.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');
```

#### Check Database
```sql
-- Verifica connessione
SHOW DATABASES;

-- Seleziona DB
USE my_topmusic;

-- Mostra tabelle
SHOW TABLES;

-- Verifica struttura
DESCRIBE utenti;
DESCRIBE canzoni;

-- Query test
SELECT COUNT(*) FROM canzoni;
SELECT COUNT(*) FROM utenti;
```

#### Browser DevTools
```
F12 → Network: Monitora AJAX calls
F12 → Console: Controlla errori JavaScript
F12 → Application → Cookies: Verifica session cookie
```

---

## 📝 NOTE FINALI

### Contribuire al Progetto

1. **Pull requests**: Seguire PSR-12 (PHP code style)
2. **Security fixes**: Segnala vulnerabilità via email (non su issues pubbliche)
3. **Documentation**: Aggiorna questa documentazione quando aggiungi features

### Versioni Supportate

```
PHP:   8.0+ (required)
MySQL: 8.0+ (recommended 5.7+)
Node:  Non usato
```

### Roadmap Futuro

- [ ] API Documentation (Swagger/OpenAPI)
- [ ] Unit Tests (PHPUnit)
- [ ] CSRF Token implementation
- [ ] Email verification (registration)
- [ ] Two-factor authentication (2FA)
- [ ] Podcast hosting
- [ ] Social sharing features
- [ ] Analytics dashboard

---

**Ultima modifica**: 14 Novembre 2025  
**Autore**: TOP MUSIC Development Team  
**Licenza**: Proprietaria
