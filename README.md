# 🎵 TOP MUSIC - Piattaforma di Condivisione Musicale

Una moderna piattaforma web per caricare, ascoltare e condividere musica con interfaccia intuitiva e funzionalità avanzate.


---

## 🎯 Caratteristiche Principali

- ✅ **Upload Canzoni**: Carica cover (JPG/PNG) e file audio/video (MP3/MP4)
- ✅ **Player Multimediale**: Riproduzione HTML5 con controlli volume, timeline, fullscreen
- ✅ **Sistema Preferiti**: Salva, gestisci e sincronizza le tue canzoni preferite
- ✅ **Ricerca Live**: Ricerca in tempo reale con AJAX per nome canzone/artista
- ✅ **Segnalazione Contenuti**: Sistema di moderazione per contenuti inappropriati
- ✅ **Pannello Admin**: Gestione completa canzoni, utenti e segnalazioni
- ✅ **Autenticazione Utenti**: Login/registrazione sicuri con BCRYPT hashing
- ✅ **Profilo Personale**: Gestione canzoni caricate e preferiti
- ✅ **Design Responsive**: Mobile-first, compatibile con tutti i dispositivi
- ✅ **Prepared Statements**: Protezione totale da SQL Injection

## 📁 Struttura Progetto

```
📦 TOP MUSIC
├── 📁 DOCS/                   # 📚 DOCUMENTAZIONE COMPLETA
│   ├── README.md              # Indice docs
│   ├── DOCUMENTAZIONE.md      # Guida completa (150+ sezioni)
│   ├── GUIDA_PRATICA.md       # Code examples (20+ snippet)
│   └── QUICK_REFERENCE.md     # Lookup rapido
│
├── 📁 api/                    # 🔌 API RESTful sicure
│   ├── auth.php               # Login endpoint
│   ├── register.php           # Registrazione endpoint
│   ├── upload_song.php        # Upload file endpoint
│   ├── search.php             # Ricerca AJAX endpoint
│   ├── favorite.php           # Gestione preferiti
│   ├── delete_song.php        # Cancellazione canzoni
│   └── logout.php             # Logout endpoint
│
├── 📁 Admin/                  # 👨‍💼 Pannello amministrazione
│   ├── index.php              # Dashboard admin
│   ├── ModificaAdmin.php      # Modifica canzoni
│   ├── Script_Update.php      # Update canzoni
│   ├── Script_EliminaAdmin.php # Gestione utenti
│   ├── Script_CancellaSegnalazione.php # Moderation
│   ├── config.php             # Config admin
│   ├── style.css              # Stile admin
│   └── localhost.sql          # Database dump
│
├── 📁 CSS/                    # 🎨 Fogli di stile
│   ├── style_HomeOk.css       # Homepage
│   ├── style_Player.css       # Player
│   ├── style_profile.css      # Profilo utente
│   ├── style_Caricamentoo.css # Upload/Login
│   └── style_SegnalazioniOk.css # Segnalazioni
│
├── 📁 JS/                     # 📜 JavaScript
│   ├── scriptIndex.js         # Homepage AJAX
│   ├── scriptPlayer.js        # Player controls
│   └── scriptLogin.js         # Form toggle
│
├── 📁 IMG/                    # 🖼️ Immagini
│   ├── COVER/                 # Cover canzoni
│   └── PAGINA/                # Assets UI (logo, icone)
│
├── 📁 VID/                    # 🎵 Media files
│   ├── *.mp3                  # File audio
│   ├── *.mp4                  # File video
│   └── loop/                  # Background videos
│
├── 📄 index.php               # 🏠 Homepage
├── 📄 login.php               # 🔐 Login/Registrazione
├── 📄 Player.php              # 🎵 Player canzoni
├── 📄 profilo.php             # 👤 Profilo utente
├── 📄 fav.php                 # ⭐ Pagina preferiti
├── 📄 segnala.php             # 🚨 Segnalazioni
├── 📄 config.php              # ⚙️ Configurazione DB
└── 📄 .gitignore              # Git ignore rules
```

## 🔒 Sicurezza

### 🔐 Best Practices Implementate

✅ **Prepared Statements** - `bind_param()` per tutte le query  
✅ **Password Hashing** - BCRYPT (cost=10) per nuove registrazioni  
✅ **Input Validation** - Trim, sanitize, type check su tutti i campi  
✅ **Output Encoding** - HTML entities, URL encoding, JSON encoding  
✅ **Session Management** - Session regeneration, CSRF-like patterns  
✅ **HTTP Methods** - POST per modifiche, GET per letture  
✅ **HTTP Status Codes** - 401, 403, 404, 405, 500 appropriati  
✅ **Error Handling** - Errori loggati, non mostrati in produzione  


---

## 📊 Stack Tecnologico

### Backend
- **PHP**: 8.0+ (8.1+ consigliato)
- **Database**: MySQL 8.0+ (5.7+ minimo)
- **Framework**: Vanilla PHP (no framework dipendenze)
- **Package Manager**: Composer

### Frontend
- **HTML5**: Markup semantico
- **CSS3**: Responsive design, flexbox
- **JavaScript**: ES6+, jQuery 3.6+
- **AJAX**: XMLHttpRequest nativo + jQuery

### Server
- **Web Server**: Apache 2.4+
- **PHP Extensions**: mysqli, gd (optional), fileinfo
- **SSL/TLS**: HTTPS (obbligatorio in produzione)


---

## 🚀 Quick Start

### 1️⃣ Installazione Locale

```bash
# Clona repository
git clone <repo-url> c:\xampp\htdocs\topmusic
cd c:\xampp\htdocs\topmusic

# Configura database
# - Importa Admin/localhost.sql in MySQL
# - Modifica config.php con tue credenziali

# Avvia XAMPP
# - Apache: ON
# - MySQL: ON

# Visita applicazione
# http://localhost/topmusic/
```

### 2️⃣ Primo Accesso

```
1. Homepage: http://localhost/topmusic/
2. Click "Login" → "Registrazione"
3. Crea account:
   - Nome: Test User
   - Username: testuser123 (min 8 char)
   - Password: SecurePass123 (min 8 char)
4. Login con credenziali
5. Profilo → Carica prima canzone!
```

### 3️⃣ Prova Features

- 📤 Upload canzone (MP3/MP4 + JPG/PNG cover)
- 🔍 Ricerca canzoni live
- ⭐ Aggiungi ai preferiti
- 🎵 Riproduci player
- 👤 Accedi profilo
- 🚨 Segnala contenuto

---

## 📚 Documentazione

La documentazione completa si trova in **`DOCS/`**:

## 🔌 API Endpoints

Tutti gli endpoint richiedono **POST** (salvo diversamente specificato).

### Autenticazione
```
POST   /api/register.php     → Registrazione utente
POST   /api/auth.php         → Login
GET    /api/logout.php       → Logout
```

### Canzoni
```
POST   /api/upload_song.php  → Upload nuova canzone
GET    /api/search.php       → Ricerca (AJAX)
GET    /api/delete_song.php  → Elimina canzone
```

### Preferiti
```
POST   /api/favorite.php     → Add/Remove preferiti
```

## 🗄️ Database

### Tabelle Principali
```sql
utenti              -- Utenti registrati
canzoni             -- Canzoni caricate
canzoniPreferite    -- Preferiti utenti
segnalazioni        -- Segnalazioni contenuti
```

### Importare Database
```bash
# Opzione 1: phpMyAdmin
# 1. Vai a http://localhost/phpmyadmin
# 2. Importa Admin/localhost.sql

# Opzione 2: MySQL CLI
mysql -u root -p my_topmusic < Admin/localhost.sql
```

### Configurazione Database
Modifica `config.php`:
```php
define("SERVER","localhost");    // Host
define("UTENTE","root");         // Username
define("PASSWORD","");           // Password
define("DATABASE","my_topmusic"); // Database name
```


## 👨‍💻 Sviluppo

### Prerequisiti di Sistema

```
✅ PHP 8.0+
✅ MySQL 8.0+
✅ Apache 2.4+
✅ Git
✅ Composer (opzionale)
✅ VS Code (editor consigliato)
```

### Ambiente Sviluppo

```bash
# Clone
git clone <repo-url>
cd topmusic

# Setup DB
mysql -u root < Admin/localhost.sql

# Run XAMPP
# Apache: START
# MySQL: START
# visita: http://localhost/topmusic/
```

## 📄 Licenza

Questo progetto è **proprietario** e sviluppato come portfolio/progetto universitario.

**Uso consentito**: Educational, personal, internal use  
**Uso vietato**: Commerciale senza autorizzazione

---

## 👥 Autore & Team

Sviluppato da **Abde**

---


## 🔗 Link Rapidi

| Risorsa | URL |
|---------|-----|
| **Homepage** | http://localhost/topmusic/ |
| **Login** | http://localhost/topmusic/login.php |
| **Profilo** | http://localhost/topmusic/profilo.php |
| **Admin Panel** | http://localhost/topmusic/Admin/ |
| **phpMyAdmin** | http://localhost/phpmyadmin |


**🎉 Grazie per aver usato TOP MUSIC!**
