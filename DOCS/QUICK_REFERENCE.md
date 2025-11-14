# ⚡ QUICK REFERENCE - TOP MUSIC

**Guida di consultazione veloce per sviluppatori**

---

## 📌 COMANDI RAPIDI

### MySQL
```bash
# Login MySQL
mysql -u root -p

# Seleziona DB
USE my_topmusic;

# Reset password utente (test)
UPDATE utenti SET password = PASSWORD('newpass123') WHERE username = 'testuser';

# Backup database
mysqldump -u root my_topmusic > backup.sql

# Ripristina backup
mysql -u root my_topmusic < backup.sql

# Vedi ultime canzoni caricate
SELECT * FROM canzoni ORDER BY Id DESC LIMIT 10;

# Canzoni di un utente
SELECT * FROM canzoni WHERE fkUser = 1;

# Utenti + numero canzoni
SELECT u.id, u.username, COUNT(c.Id) as song_count 
FROM utenti u LEFT JOIN canzoni c ON u.id = c.fkUser 
GROUP BY u.id;
```

### PHP CLI
```bash
# Test sintassi PHP
php -l filename.php

# Esegui script PHP
php -f filename.php

# Versione PHP
php -v

# Estensioni caricate
php -m

# Info configurazione
php -i | grep 'extension_dir'
```

---

## 📂 FILE PRINCIPALI - LOCAZIONI

```
config.php              →  Credenziali DB + costanti globali
index.php               →  Homepage
login.php               →  Login/Registrazione
profilo.php             →  Profilo utente + upload
Player.php              →  Lettore canzoni
fav.php                 →  Pagina preferiti

/api/auth.php           →  Login endpoint
/api/register.php       →  Registrazione endpoint
/api/upload_song.php    →  Upload endpoint
/api/search.php         →  Ricerca endpoint
/api/favorite.php       →  Preferiti endpoint
/api/delete_song.php    →  Eliminazione endpoint
/api/logout.php         →  Logout endpoint

/Admin/index.php        →  Dashboard admin
/Admin/localhost.sql    →  Database dump

/CSS/*.css              →  Fogli di stile
/JS/*.js                →  JavaScript
/IMG/COVER/             →  Cover canzoni
/VID/                   →  File audio/video
```

---

## 🔑 SESSION VARIABLES

| Variabile | Tipo | Uso | Esempio |
|-----------|------|-----|---------|
| `$_SESSION["id"]` | INT | User ID | 1, 18, 25 |
| `$_SESSION["nome"]` | STRING | User name | "Marco Rossi" |
| `$_SESSION["errore_login"]` | BOOL | Login error flag | true/false |
| `$_SESSION["errore_register"]` | BOOL | Register error flag | true/false |

### Verifica Autenticazione
```php
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["id"];
$userName = $_SESSION["nome"];
```

---

## 🔌 API ENDPOINTS MAPPA

```
METHOD   URL                          DESCRIZIONE
────────────────────────────────────────────────────
POST     /api/auth.php               Login
POST     /api/register.php           Registrazione
GET      /api/logout.php             Logout
POST     /api/upload_song.php        Carica canzone
GET      /api/search.php?q=term      Ricerca canzoni
POST     /api/favorite.php           Add/remove preferiti
GET      /api/delete_song.php?id=X   Elimina canzone
```

---

## 📊 DATABASE SCHEMA RAPIDO

### Tabelle
```
utenti              id, username, password, nome, email, Data, Ora
canzoni             Id, NomeCanzone, Autore, IMG, VID, fkUser
canzoniPreferite    id, fkCanzone, fkUtente
segnalazioni        id, fkCanzone, motivo, email, descrizione
```

### Relazioni
```
canzoni.fkUser          → utenti.id
canzoniPreferite.fkCanzone  → canzoni.Id
canzoniPreferite.fkUtente   → utenti.id
segnalazioni.fkCanzone  → canzoni.Id
```

---

## 🔐 SICUREZZA CHECKLIST

```
☐ Input trimmed + sanitized con htmlspecialchars()
☐ Tutti i $_POST/$_GET validati
☐ Query prepared statements (non concatenate)
☐ Password hashed con password_hash() / verified con password_verify()
☐ File upload con whitelist estensioni
☐ File upload con check MIME type
☐ File upload con size limit
☐ File upload con nomi unici (uniqid)
☐ Session autenticazione controllata
☐ HTTP headers coretti (Content-Type, etc)
☐ Errori non mostrati in produzione
☐ Errors loggati in file
```

---

## 🐛 DEBUGGING TIPS

### JavaScript Console
```javascript
// Browser → F12 → Console
console.log("Variable:", variable);
console.error("Error:", error);
console.table(arrayOfObjects);

// AJAX debug
$.ajax({
    ...
    success: function(data) {
        console.log("Response:", data);
    },
    error: function(xhr, status, error) {
        console.error("AJAX Error:", error);
        console.log("XHR:", xhr.responseText);
    }
});
```

### PHP Debugging
```php
// Print variables
var_dump($variable);
print_r($array);

// Log to file
error_log("Debug: " . print_r($var, true));

// Query debug
echo "Debug SQL: " . $stmt->error;
```

### Browser DevTools
```
F12 → Network tab:      AJAX requests
F12 → Application tab:  Cookies, Session storage
F12 → Console tab:      JavaScript errors
F12 → Sources tab:      Breakpoints, debugging
```

---

## 📋 COMUM ERRORS & FIXES

| Errore | Causa | Fix |
|--------|-------|-----|
| "Undefined variable" | Variabile non inizializzata | Controllare isset() |
| "Headers already sent" | Output prima di header() | Rimuovere spazi/output |
| "Call to undefined function" | Funzione non esiste | Include file corretto |
| "Unexpected T_STRING" | Syntax error | Controllare virgolette/parentesi |
| "CORS error" | Cross-origin request | Aggiungere CORS headers |
| "File not found" | Path errato | Controllare percorso assoluto |

---

## 🚀 DEPLOY CHECKLIST

```
Pre-deployment:
☐ Testa tutte le features localmente
☐ Esegui security audit
☐ Backup database completo
☐ Check file permissions

Deployment:
☐ Aggiorna config.php credenziali produzione
☐ Disabilita debug/error display
☐ Configura HTTPS/SSL
☐ Setup WAF (Web Application Firewall)
☐ Configura backup automatici

Post-deployment:
☐ Testa site in produzione
☐ Monitor error logs
☐ Check performance
☐ Testa caricamento file
☐ Verifica session funziona
```

---

## 📞 SUPPORTO FEATURES

### Supportate ✅
- Upload MP3/MP4
- Ricerca live
- Player HTML5
- Session-based auth
- Prepared statements
- BCRYPT password hashing
- Preferiti/bookmarks
- Admin panel

### TODO 🟡
- CSRF tokens
- Email verification
- 2FA (two-factor auth)
- Rate limiting
- Caching
- CDN integration
- Analytics
- Social login

### NON Supportati ❌
- OAuth/SAML
- Streaming (HLS/DASH)
- Playlist sharing
- Comments/ratings
- Podcast hosting
- DRM content

---

## 🎯 FILE SIZE LIMITS

```
IMG (Cover):  2 MB   (2097152 bytes)
VID (Audio):  20 MB  (20971509 bytes)
Total form:   30 MB  (sumatoria)
```

Modifica in:
- `api/upload_song.php` linea 33, 57
- `php.ini` → `upload_max_filesize`, `post_max_size`

---

## 🔗 LINK UTILI

```
Homepage:           http://localhost/topmusic/
Login:              http://localhost/topmusic/login.php
Profilo:            http://localhost/topmusic/profilo.php
Admin:              http://localhost/topmusic/Admin/index.php
phpMyAdmin:         http://localhost/phpmyadmin
XAMPP Control:      http://localhost/xampp
```

---

## 📚 DOCUMENTAZIONE CORRELATA

- `DOCUMENTAZIONE.md` - Documentazione completa
- `GUIDA_PRATICA.md` - Code examples
- `SECURITY_FIXES.md` - Fix di sicurezza
- `ANALISI_COMPLETA_POST_P0.md` - Analisi completa
- `README.md` - Overview progetto

---

## 🆘 TROUBLESHOOTING RAPIDO

```
❌ "Errore di connessione database"
→ MySQL running? XAMPP Control Panel

❌ "Upload fallisce"
→ Check cartella IMG/COVER e VID permissions

❌ "Sessione non persiste"
→ session.save_path folder esiste?

❌ "Ricerca non funziona"
→ Check AJAX in browser DevTools (F12 Network)

❌ "Player non riproduce"
→ File esiste in VID/? Check browser console

❌ "Login fallisce"
→ Username/password corretti? Check DB

❌ "Caratteri strani (????)"
→ Set charset UTF-8 nel DB
```

---

## 💡 PERFORMANCE TIPS

1. **Indexare campi ricerca**: `CREATE INDEX idx_canzone ON canzoni(NomeCanzone);`
2. **Pagination**: Aggiungere LIMIT/OFFSET alle query
3. **Lazy loading**: Immagini caricate su scroll
4. **Caching**: Cache AJAX results
5. **Minify CSS/JS**: In produzione
6. **Gzip compression**: Abilitare in Apache
7. **CDN immagini**: Per file statici
8. **Database connection pooling**: For high traffic

---

## 📊 MONITORA METRICHE

```bash
# Dimensione database
SELECT 
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
FROM information_schema.tables
WHERE table_schema = 'my_topmusic';

# Canzoni per utente
SELECT fkUser, COUNT(*) as count 
FROM canzoni 
GROUP BY fkUser 
ORDER BY count DESC;

# Preferiti più popolari
SELECT fkCanzone, COUNT(*) as favorites
FROM canzoniPreferite
GROUP BY fkCanzone
ORDER BY favorites DESC
LIMIT 10;
```

---

**Ultima modifica**: 14 Novembre 2025  
**Versione**: 1.0
