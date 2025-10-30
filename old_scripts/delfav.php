<?php
	session_start();
$conn = new mysqli('localhost', 'root', '', 'my_topmusic');
$canzone=$_GET['Id'];
$utente=$_SESSION["id"];
$sql="DELETE FROM canzoniPreferite WHERE fkCanzone=".$canzone." AND fkUtente=".$utente;
if ($conn->query($sql) === TRUE) {
    //echo "Eliminata";
		echo '<button style="background:grey;" id="bottone'.$canzone.'"  onClick="aggiungi('.$canzone.')" class="bottones" ></button>';
}
else
{
    echo "failed";
}
?>
