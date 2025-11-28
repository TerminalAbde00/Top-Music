<?php
session_start();

// Se già loggato, redirect alla pagina upload
if(isset($_SESSION["id"])){
    header("Location: upload.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="IMG/PAGINA/top.png">
    <link rel="stylesheet" href="CSS/style_Caricamentoo.css">
    <title>Login - TOP MUSIC</title>
</head>
<body>
    <a href="/" class="back-link">
        <img src="IMG/PAGINA/ar.png" id="backback" class="back-icon">
    </a>
    
    <div class="login-page">
        <div class="form">
            <!-- Form Registrazione -->
            <form class="register-form" method="post" action="api/register.php" 
                <?php
                if(isset($_SESSION["errore_register0"]) && $_SESSION["errore_register0"]==true){
                    echo 'style="display: block;"';
                    unset($_SESSION["errore_register0"]);
                }
                ?>>
                <p class="form-title">Registrazione</p>
                <input type="text" placeholder="Nome" name="nme" required maxlength="34">
                <input type="text" placeholder="Username" name="usr" required maxlength="34" minlength="8">
                <input type="password" placeholder="Password" name="psw" required minlength="8">
                <button type="submit" name="submit">Registrati</button>
                <?php
                if(isset($_SESSION["errore_register"]) && $_SESSION["errore_register"]==true){
                    echo '<p class="error-message">Username già esistente!</p>';
                    unset($_SESSION["errore_register"]);
                }
                ?>
                <p class="message">Sei già registrato? <a href="#">Accedi</a></p>
            </form>
            
            <!-- Form Login -->
            <form class="login-form" method="post" action="api/auth.php"
                <?php
                if(isset($_SESSION["errore_register1"]) && $_SESSION["errore_register1"]==true){
                    echo 'style="display: none;"';
                    unset($_SESSION["errore_register1"]);
                }
                ?>>
                <p class="form-title">Accedi</p>
                <input type="text" placeholder="Username" name="usr" required maxlength="34">
                <input type="password" placeholder="Password" name="pwd" required minlength="8">
                <button type="submit" name="submit">Login</button>
                <?php
                if(isset($_SESSION["errore_login"]) && $_SESSION["errore_login"]==true){
                    echo '<p class="error-message">Username o Password errati!</p>';
                    unset($_SESSION["errore_login"]);
                }
                ?>
                <p class="message">Non sei ancora registrato? <a href="#">Crea nuovo account</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="JS/scriptLogin.js"></script>
</body>
</html>
