# TOP MUSIC - Piattaforma di Condivisione Musicale

Piattaforma web per caricare, ascoltare e condividere musica, sviluppata con PHP, MySQL e JavaScript.

## 🎯 Caratteristiche Principali

- ✅ **Upload Canzoni**: Carica cover e file audio/video
- ✅ **Player Multimediale**: Riproduzione MP3/MP4 con controlli
- ✅ **Sistema Preferiti**: Salva e gestisci le tue canzoni preferite
- ✅ **Ricerca Live**: Cerca canzoni in tempo reale
- ✅ **Segnalazione Contenuti**: Sistema di moderazione
- ✅ **Pannello Admin**: Gestione canzoni e segnalazioni
- ✅ **Autenticazione Utenti**: Login e registrazione sicuri

## 📁 Struttura Progetto

```
📦 TOP MUSIC
├── 📁 api/                    # API RESTful sicure
│   ├── favorite.php           # Gestione preferiti
│   ├── search.php             # Ricerca canzoni
│   └── delete_song.php        # Cancellazione canzoni
│
├── 📁 JS/                     # JavaScript esterni
│   ├── scriptIndex.js         # Logica pagina principale
│   └── scriptPlayer.js        # Logica player
│
├── 📁 old_scripts/            # Vecchi script (deprecati)
│   ├── addfav.php             # ⚠️ DEPRECATO
│   ├── delfav.php             # ⚠️ DEPRECATO
│   ├── del_script.php         # ⚠️ DEPRECATO
│   ├── ricerca.php            # ⚠️ DEPRECATO
│   └── README.md
│
├── 📁 CSS/                    # Fogli di stile
├── 📁 IMG/                    # Immagini (cover, UI)
├── 📁 VID/                    # File audio/video
├── 📁 Admin/                  # Pannello amministrazione
│
├── 📄 index.php               # Homepage
├── 📄 Player.php              # Player canzoni
├── 📄 upload.php              # Upload canzoni
├── 📄 fav.php                 # Pagina preferiti
├── 📄 register.php            # Registrazione
├── 📄 login.script.php        # Login
└── 📄 config.php              # Configurazione DB
```

## 🔒 Sicurezza

### Implementazioni Recenti:

✅ **Prepared Statements** - Prevengono SQL Injection  
✅ **Validazione Input** - Controllo parametri in entrata  
✅ **Protection XSS** - `htmlspecialchars()` su output  
✅ **Autenticazione** - Sessioni e verifica permessi  
✅ **HTTP Status Codes** - Errori gestiti correttamente  
✅ **Separazione API** - Endpoint dedicati e sicuri  

### Vecchi Script:
Gli script nella cartella `old_scripts/` sono deprecati e contengono vulnerabilità. **Non utilizzare in produzione.**

## 🚀 Setup

### Requisiti:
- XAMPP/WAMP/LAMP
- PHP 7.4+
- MySQL 5.7+
- Apache

### Installazione:
1. Clona il repository in `htdocs/`
2. Importa il database da `Admin/localhost.sql`
3. Configura `config.php` con le tue credenziali DB
4. Avvia Apache e MySQL
5. Visita `http://localhost/`

## 🛠️ Tecnologie

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Librerie**: jQuery 3.5.1
- **Pattern**: RESTful API

## 📝 API Endpoints

### Preferiti
```
GET /api/favorite.php?action=add&Id={songId}
GET /api/favorite.php?action=remove&Id={songId}
```

### Ricerca
```
GET /api/search.php?term={searchTerm}
```

### Cancellazione
```
GET /api/delete_song.php?idc={songId}
```

## 👥 Autore

Sviluppato come progetto universitario/portfolio.

## 📅 Ultimo Aggiornamento

Ottobre 2025 - Refactoring completo con focus su sicurezza e organizzazione