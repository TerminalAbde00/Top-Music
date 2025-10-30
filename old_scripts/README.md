# Vecchi Script (Deprecati)

Questa cartella contiene i vecchi script PHP che sono stati sostituiti dalle nuove API nella cartella `api/`.

## File deprecati:

### 1. `addfav.php`
- **Sostituito da:** `api/favorite.php?action=add`
- **Problema:** Credenziali database hardcoded, niente prepared statements
- **Nuovo:** API sicura con autenticazione e validazione

### 2. `delfav.php`
- **Sostituito da:** `api/favorite.php?action=remove`
- **Problema:** Credenziali database hardcoded, niente prepared statements
- **Nuovo:** API sicura con autenticazione e validazione

### 3. `del_script.php`
- **Sostituito da:** `api/delete_song.php`
- **Problema:** SQL injection, nessuna validazione parametri
- **Nuovo:** Verifica proprietà canzone, cancellazione sicura

### 4. `ricerca.php`
- **Sostituito da:** `api/search.php`
- **Problema:** Possibile XSS, nessun limite risultati
- **Nuovo:** Protezione XSS, LIMIT risultati, htmlspecialchars

### 5. `Script_Upload.php`
- **Sostituito da:** `api/upload_song.php`
- **Problema:** Vulnerabilità upload, validatione debole, errori gestione
- **Nuovo:** Validazione robusta, gestione errori completa, sicurezza file upload

### 6. `login.script.php`
- **Sostituito da:** `api/auth.php`
- **Problema:** Nessuna validazione input, errori gestione, vulnerabilità
- **Nuovo:** Validazione completa, sanitizzazione, gestione errori robusta

### 7. `register.php`
- **Sostituito da:** `api/register.php`
- **Problema:** Nessuna validazione input, sanitizzazione debole
- **Nuovo:** Validazione completa, sanitizzazione, auto-login dopo registrazione

## Note:
- Questi file sono mantenuti solo per backup/retrocompatibilità
- Non devono essere utilizzati in produzione
- Verranno rimossi definitivamente in una prossima versione

## Data Migrazione: 25 Ottobre 2025
