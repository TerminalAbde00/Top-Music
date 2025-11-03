<?php
session_start();
require_once("../config.php");

// Verifica autenticazione
if (!isset($_SESSION["id"])) {
    http_response_code(401);
    die(json_encode(["error" => "Non autorizzato"]));
}

// Verifica metodo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(["error" => "Metodo non consentito"]));
}

// Connessione database
$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if ($myDB->connect_errno) {
    http_response_code(500);
    die(json_encode(["error" => "Errore di connessione al database"]));
}

$errors = [];
$IMG_U_name_new = null;
$VID_U_name_new = null;

// Validazione e upload cover
if (isset($_FILES['IMG']) && $_FILES['IMG']['error'] === UPLOAD_ERR_OK) {
    $IMG_U = $_FILES['IMG'];
    $IMG_U_ext = strtolower(pathinfo($IMG_U['name'], PATHINFO_EXTENSION));
    $allowed1 = ['png', 'pjp', 'jpg', 'pjpeg', 'jpeg', 'jfif'];
    
    if (!in_array($IMG_U_ext, $allowed1)) {
        $errors[] = "Formato immagine non valido";
    } elseif ($IMG_U['size'] > 2097152) { // 2MB
        $errors[] = "Immagine troppo grande (max 2MB)";
    } else {
        $IMG_U_name_new = uniqid('', true) . '.' . $IMG_U_ext;
        $IMG_U_des = '../IMG/COVER/' . $IMG_U_name_new;
        
        if (!move_uploaded_file($IMG_U['tmp_name'], $IMG_U_des)) {
            $errors[] = "Errore nel caricamento dell'immagine";
        }
    }
} else {
    $errors[] = "Immagine richiesta";
}

// Validazione e upload video/audio
if (isset($_FILES['VID']) && $_FILES['VID']['error'] === UPLOAD_ERR_OK) {
    $VID_U = $_FILES['VID'];
    $VID_U_ext = strtolower(pathinfo($VID_U['name'], PATHINFO_EXTENSION));
    $allowed2 = ['mp4', 'm4v', 'mp3', 'm4a'];
    
    if (!in_array($VID_U_ext, $allowed2)) {
        $errors[] = "Formato file audio/video non valido";
    } elseif ($VID_U['size'] > 2097150982) { // ~20MB
        $errors[] = "File troppo grande (max 20MB)";
    } else {
        $VID_U_name_new = uniqid('', true) . '.' . $VID_U_ext;
        $VID_U_des = '../VID/' . $VID_U_name_new;
        
        if (!move_uploaded_file($VID_U['tmp_name'], $VID_U_des)) {
            $errors[] = "Errore nel caricamento del file audio/video";
        }
    }
} else {
    $errors[] = "File audio/video richiesto";
}

// Validazione parametri form
if (!isset($_POST['NomeCanzone']) || empty(trim($_POST['NomeCanzone']))) {
    $errors[] = "Nome canzone richiesto";
}
if (!isset($_POST['Autore']) || empty(trim($_POST['Autore']))) {
    $errors[] = "Nome autore richiesto";
}

// Se ci sono errori, cancella i file caricati e restituisci errore
if (!empty($errors)) {
    if ($IMG_U_name_new && file_exists('IMG/COVER/' . $IMG_U_name_new)) {
        unlink('../IMG/COVER/' . $IMG_U_name_new);
    }
    if ($VID_U_name_new && file_exists('VID/' . $VID_U_name_new)) {
        unlink('../VID/' . $VID_U_name_new);
    }
    http_response_code(400);
    echo json_encode(["errors" => $errors]);
    $myDB->close();
    exit();
}

// Inserisci nel database
$fkUser = $_SESSION['id'];
$NomeC = htmlspecialchars(trim($_POST['NomeCanzone']), ENT_QUOTES, 'UTF-8');
$Autore = htmlspecialchars(trim($_POST['Autore']), ENT_QUOTES, 'UTF-8');
$IMG = $IMG_U_name_new;
$VID = $VID_U_name_new;

$stmt = $myDB->prepare("INSERT INTO canzoni (NomeCanzone, Autore, IMG, VID, fkUser) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('ssssi', $NomeC, $Autore, $IMG, $VID, $fkUser);

if ($stmt->execute()) {
    http_response_code(200);
    header("Location: /");
} else {
    // Cancella file se inserimento fallisce
    if (file_exists('../IMG/COVER/' . $IMG_U_name_new)) {
        unlink('../IMG/COVER/' . $IMG_U_name_new);
    }
    if (file_exists('../VID/' . $VID_U_name_new)) {
        unlink('../VID/' . $VID_U_name_new);
    }
    http_response_code(500);
    echo json_encode(["error" => "Errore nell'inserimento nel database"]);
}

$stmt->close();
$myDB->close();
exit();
?>
