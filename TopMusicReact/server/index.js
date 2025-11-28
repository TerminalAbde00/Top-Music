const express = require('express');
const db = require('./config/db');
const cors = require('cors');

const app = express();

const PORT = 3002;
app.use(cors());
app.use(express.json());

// Route to get all posts
app.get("/api/get", (req, res) => {
    db.query("SELECT * FROM Canzoni ORDER BY id DESC LIMIT 20;", (err, result) => {
        if (err) {
            console.log(err);
        }
        res.send(result);
    });
});

// Route to get a song by ID
app.get("/api/getFromId/:id", (req, res) => {
    const id = req.params.id;
    db.query("SELECT * FROM Canzoni WHERE id = ?", id, (err, result) => {
        if (err) {
            console.log(err);
        }
        res.send(result);
    });
});

// Route to search for songs
app.get("/api/search", (req, res) => {
    const term = req.query.term;
    db.query("SELECT * FROM Canzoni WHERE NomeCanzone LIKE ? OR Autore LIKE ?", [`%${term}%`, `%${term}%`], (err, result) => {
        if (err) {
            console.log(err);
            res.status(500).send("Errore durante la ricerca");
        } else {
            res.send(result);
        }
    });
});

// Route to check if username or email already exists
app.post("/api/check_username", (req, res) => {
    const { username, email } = req.body;

    // Query to check if username or email exists
    const query = "SELECT COUNT(*) AS count FROM Utenti WHERE username = ? OR email = ?";
    db.query(query, [username, email], (err, result) => {
        if (err) {
            console.log(err);
            return res.status(500).json({ error: "Errore durante la verifica dell'username e dell'email" });
        }

        const count = result[0].count;

        if (count > 0) {
            // Check if username exists
            db.query("SELECT COUNT(*) AS usernameCount FROM Utenti WHERE username = ?", [username], (err, usernameResult) => {
                if (err) {
                    console.log(err);
                    return res.status(500).json({ error: "Errore durante la verifica dell'username" });
                }

                // Check if email exists
                db.query("SELECT COUNT(*) AS emailCount FROM Utenti WHERE email = ?", [email], (err, emailResult) => {
                    if (err) {
                        console.log(err);
                        return res.status(500).json({ error: "Errore durante la verifica dell'email" });
                    }

                    const usernameExists = usernameResult[0].usernameCount > 0;
                    const emailExists = emailResult[0].emailCount > 0;

                    // Send response
                    res.json({ usernameExists, emailExists });
                });
            });
        } else {
            // If no matches found, send response
            res.json({ usernameExists: false, emailExists: false });
        }
    });
});

// Route per la registrazione dell'utente
app.post("/api/register", (req, res) => {
    const { username, password, name, email } = req.body;

    // Verifica che tutti i campi siano stati forniti
    if (!username || !password || !name || !email) {
        return res.status(400).json({ error: "Tutti i campi sono obbligatori" });
    }

    // Hash della password (usa un metodo sicuro come bcrypt in produzione)
    const MD5Password = require('crypto').createHash('md5').update(password).digest('hex');

    // Data e ora correnti
    const Data = new Date().toLocaleDateString('it-IT');
    const Ora = new Date().toLocaleTimeString('it-IT');

    // Query per inserire l'utente nel database
    const query = "INSERT INTO Utenti (username, password, nome, Data, Ora, email) VALUES (?, ?, ?, ?, ?, ?)";
    db.query(query, [username, MD5Password, name, Data, Ora, email], (err, result) => {
        if (err) {
            console.error("Errore durante la registrazione:", err);
            return res.status(500).json({ error: "Errore durante la registrazione" });
        }

        // Se la registrazione ha successo, invia una risposta positiva
        res.status(200).json({ success: true, message: "Registrazione completata con successo" });
    });
});

// Route per il login
app.post("/api/login", (req, res) => {
    console.log(req.body); // Controlla i dati ricevuti

    const { username, password } = req.body;

    if (!username || !password) {
        return res.status(400).json({ error: "Username e password sono obbligatori" });
    }

    console.log(username);
    console.log(password);
    // Hash della password per confrontarla con quella nel database
    const MD5Password = require('crypto').createHash('md5').update(password).digest('hex');
    console.log(MD5Password);


    // Query per verificare le credenziali
    const query = "SELECT id, nome, password FROM utenti WHERE username = ?";
    console.log(query);
    db.query(query, [username], (err, result) => {
        if (err) {
            console.error("Errore durante il login:", err);
            return res.status(500).json({ error: "Errore durante il login" });
        }

        if (result.length > 0) {
            const user = result[0];
        
            if (MD5Password === user.password) {
                // Login riuscito
                res.status(200).json({ success: true, message: "Login riuscito", user: { id: user.id, nome: user.nome } });
        
            } else {
                // Password errata
                res.status(200).json({ success: false, message: "Password errata" });
        
            }
        } else {
            // Utente non trovato
            res.status(200).json({ success: false, message: "Utente non trovato" });
        }
    });
});

app.listen(PORT, () => {
    console.log(`Server is running on ${PORT}`);
});