<?php
session_start();
if($_SESSION["Admin"]==true){
include("config.php");
$id = $_POST['id'];
$NomeCanzone = $_POST['NomeCanzone'];
$Autore = $_POST['Autore'];
$IMG = $_POST['IMG'];
$VID = $_POST['VID'];
$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
$sql="UPDATE canzoni SET NomeCanzone = '$NomeCanzone', Autore = '$Autore', IMG = '$IMG', VID = '$VID' WHERE id=".$id;
echo $sql;

if ($conn->query($sql) === TRUE) {
header("Location: index.php");
}
}else{
echo "Non Autorizzato";
}
 ?>
