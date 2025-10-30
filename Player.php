<!DOCTYPE html>
<html>

<head>
  <style media="screen">
    * {
   margin: 0;
   padding: 0;
 }

 .progress-bar {
   bottom: -20px;
   position: fixed;
   width: 100%;
   height: 25px;
   background-color: #eeeeee80;
   vertical-align: 2px;
   border-radius: 3px;
   cursor: pointer;
  transition: transform 250ms;
  z-index:9;
 }
 .progress-bar:hover {
   transform: translateY(-10px);
 }

 .now {
   position: absolute;
   left: 0;
   display: inline-block;
   height: 25px;
   width: 0%;
   background-color: #f7b42c;
   background: linear-gradient(315deg, #f7b42c 0%, #fc575e 74%);
 }


  </style>
  <style>
  .segnala{
    position:absolute;
    height:30px;
    width:30px;
    border-radius:100%;
    border:solid 3px;
    color:#da3434;
    z-index:7;
    margin: 33px;
    right:0;
    cursor: pointer;
    transition: transform 250ms;
  }
  .segnala:hover{
  transform:scale(1.2);  
  }
  #backback:hover{
    transform:scale(1.2);  
  }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="JS/scriptPlayer.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto+Condensed'><link rel="stylesheet" href="./CSS/style_SegnalazioniOk.css">
  <?php
  include("config.php");
  $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
  if($myDB->connect_errno){
      echo "Errore di connessione";
      exit();
  }

  $Id_C = $_GET['Id'];
  $risultato=$myDB->query("SELECT Id, NomeCanzone, Autore, IMG FROM canzoni WHERE Id = $Id_C");

      while ($row=$risultato->fetch_assoc()) {
          echo '<meta charset="utf-8">';
          echo '<link rel="stylesheet" href="CSS/style_Player.css">';
          echo '<link rel="icon" href="IMG/COVER/'.$row["IMG"].'">';
          echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">';
          echo '<title> '.$row["NomeCanzone"].' - '.$row["Autore"].'</title>';
      }
   ?>
</head>

<body>
<a href="/"><img src="IMG/PAGINA/ar.png" id="backback" style="transition: transform 250ms;width: 30px;z-index:7;margin: 33px;position: absolute;" class="back"></a>
<?php
$Preferiti = mysqli_query($myDB, "SELECT * FROM segnalazioni WHERE fkCanzone= ".$Id_C);
  if (mysqli_num_rows($Preferiti) < 5){
      echo '<div id="show" class="segnala">
                   <i style="font-size:18px;color:#da3434;padding:3px;"class="fa fa-flag" aria-hidden="true"></i>
                        </div>';
  }

?>
  <?php
  $Id_C = $_GET['Id'];
  $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
  if($myDB->connect_errno){
      echo "Errore di connessione";
      exit();
  }
  $risultato=$myDB->query("SELECT Id, NomeCanzone, Autore, IMG, VID FROM canzoni WHERE Id = $Id_C");
      while ($row=$risultato->fetch_assoc()) {

          echo  '<div class="black">';
          echo ' </div>';
          echo  '<div class="BG">';
          echo ' </div>';
          $FILE = $row["VID"];
          list($nomeF, $nomeF2, $estensioneF) = explode(".", $FILE);
          if ($estensioneF=="mp4" OR $estensioneF=="m4v") {
            echo '<video autoplay loop playsinline id="myVideo">';
            echo '<source src="VID/'.$row["VID"].'"  type="video/mp4">';
            echo '<div class="PS"></div>';
            echo '</video>';
          }
          if ($estensioneF=="mp3" OR $estensioneF=="m4a") {
            echo '<video autoplay loop playsinline id="myVideo2"">';
            $rand = rand(0,1);
            if ($rand == 0) {
              echo '<source src="VID/loop/loop.mp4"  type="video/mp4">';
            }
            if ($rand == 1) {
                echo '<source src="VID/loop/loop.mp4"  type="video/mp4">';
            }
            


            echo '<div class="PS"></div>';
            echo '</video>';
            echo '<audio src="VID/'.$row["VID"].'" autoplay loop id="myVideo"></audio>';
          }

          echo '<div>';
          echo '<div class="box">';
          if (mysqli_num_rows($Preferiti) >= 5){
               echo '<img src="IMG/PAGINA/rev.png" style="width: 87px;z-index:7;position: absolute;right: -27px;top: -28px;transform: rotate(38deg);">';
          }
          echo '<div class="cover" >';
          echo '<img id="angoli" src="IMG/COVER/'.$row["IMG"].'" width="200" height="200">';
          echo '</div>';
          echo '<div class="text">';
          echo '<p><strong style="font-family: Arial;text-transform: capitalize;">'.$row["NomeCanzone"].'</strong></p>';
          echo '</div>';
          echo '<div class="text">';
          echo '<p style="font-style: italic;text-transform: capitalize;">'.$row["Autore"].'</p>';
          echo '</div>';
          echo '<div >';
          echo '<button id="myBtn" onclick="myFunction()">Pause</button>';
          echo '</div></div></div>';


          echo '<div class="background" style="display:none;">
            <div class="container">
              <div class="screen">
                <div class="screen-body">
                  <div class="screen-body-item left">
                    <div class="app-title">
                      <span>SEGNALA</span>
                      <span>PROBLEMA</span>
                    </div>
                  </div>
                  <div class="screen-body-item">
                    <div class="app-form">
                      <form>
                        <div class="app-form-group">
                          <input class="app-form-control" placeholder="Nome della canzone" value="'.$row["NomeCanzone"].'" readonly>
                        </div>
                        <div class="app-form-group">
                          <input class="app-form-control" placeholder="Autore" value="'.$row["Autore"].'" readonly>
                        </div>

                        <div class="app-form-group message">
                          <input class="app-form-control" name="id" value="'.$row["Id"].'" style="display:none;">
                          <input class="app-form-control" placeholder="Descrivi il problema" name="Messaggio" required>
                        </div>
                        <div class="app-form-group buttons">
                          <p style="display:inline;margin-right: 5px;" class="app-form-button">ANNULLA</p>
                          <input type="submit" class="app-form-button" value="SEGNALA" name="submit">
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>';

      }

   ?>
   <div class="progress">
  <span class="start"></span>
  <div class="progress-bar">
    <div class="now"></div>
  </div>
  <span class="end"></span>
 </div>
   <!-- Scripts spostati in JS/scriptPlayer.js -->
  </body>
</html>
