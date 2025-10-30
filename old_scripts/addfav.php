<?php
	session_start();
$conn = new mysqli('localhost', 'root', '', 'my_topmusic');
$canzone=$_GET['Id'];
$utente=$_SESSION["id"];
$sql="INSERT INTO `canzoniPreferite` (`fkCanzone`, `fkUtente`) VALUES ('$canzone', '$utente')";
if ($conn->query($sql) === TRUE) {
    	echo '<button style="background:gold;" id="bottone'.$canzone.'"  onClick="elimina('.$canzone.')" class="bottones" ></button>';
}
else
{
    echo "failed";
}
?>
