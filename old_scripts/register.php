<?php
session_start();

include("config.php");
          $conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);

if (!$conn)
{
  die("Connection failed: " . mysqli_connect_error());
}
//_____________________________________________________________________________________________

$username = $_POST['usr'];
$password = $_POST['psw'];
$nme = $_POST['nme'];
$MD5Password = md5($password);
$Data = date("d-m-Y ");
$Ora =  date("H:i:s");

$stmt = $conn->prepare("SELECT username FROM utenti WHERE username= ?");
$stmt->bind_param('s',$username);
$stmt->execute();
$stmt->bind_result($usr);
$stmt->fetch();

if ($usr === null) {
          $stmt = $conn->prepare("INSERT INTO utenti (username, password, nome, Data, Ora) VALUES (?,?,?,?,?)");
          $stmt->bind_param('sssss',$username,$MD5Password,$nme,$Data,$Ora);
          if ($stmt->execute()) {
            unset($_SESSION["errore_register"]);
            header("Location: upload.php");
            } else {
                echo "Errore";
            }

}else{
                  $_SESSION["errore_register"]=true;
                  $_SESSION["errore_register0"]=true;
                  $_SESSION["errore_register1"]=true;
                  header("Location: upload.php");
}
mysqli_close($conn);


?>
