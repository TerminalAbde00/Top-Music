<?php
session_start();
if($_SESSION["Admin"]==true){
include("config.php");
$id=$_GET['id'];
$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if($myDB->connect_errno){
    echo "Errore di connessione";
    exit();
}
$risultato=$myDB->query("SELECT * FROM canzoni WHERE id =".$id);
echo '<form action="Script_Update.php" method="post">';
while ($row=$risultato->fetch_assoc()) {
  echo '
  <h1>Modifica '.$row["NomeCanzone"].' di '.$row["Autore"].' </h1>
  <table>
          <tr>
            <td><strong>Nome Canzone: </strong></td>
            <td ><input style="width:300px;" type="text" value="'.$row["NomeCanzone"].'" name="NomeCanzone"></td>
          </tr>
          <tr>
            <td style="float:right"><strong>Autore: </strong></td>
            <td><input style="width:300px;" type="text" value="'.$row["Autore"].'" name="Autore"></td>
          </tr>
          <tr>
            <td style="float:right"><strong>IMG: </strong></td>
            <td><input style="width:300px;" type="text" value="'.$row["IMG"].'" name="IMG"></td>
          </tr>
          <tr>
            <td style="float:right"><strong>VID: </strong></td>
            <td><input style="width:300px;" type="text" value="'.$row["VID"].'" name="VID"></td>
          </tr>
          <tr>
            <td style="float:right"></td>
            <td><input style="width:300px;" type="submit"name="id" value="'.$id.'"></td>
          </tr>
        </table>';
}
echo '</form>';

}else{
echo "Non Autorizzato";
}

 ?>
