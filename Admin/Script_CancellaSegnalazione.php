<?php
session_start();
if($_SESSION["Admin"]==true){
include("config.php");
$id= $_GET['id'];
$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
$sql="DELETE FROM segnalazioni WHERE fkCanzone=".$id;
if ($conn->query($sql) === TRUE) {
header("Location: index.php");
}
}else{
echo "Non Autorizzato";
}
 ?>
