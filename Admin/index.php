<html lang="en" dir="ltr">
  <head>  
    <meta charset="utf-8">
      <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <title>Admin</title>
  </head>
  <body>
    <?php
session_start();

// IMPORTANTE: Implementare autenticazione proper tramite database
// Non usare credenziali hardcoded in produzione
// Questa è una misura temporanea per dimostrare il concetto

// Se session admin non è impostata, verifica le credenziali (solo per transizione da vecchio sistema)
if(!isset($_SESSION["Admin"]) || $_SESSION["Admin"] !== 'yes'){
    // Verifica credenziali da GET (solo per transizione - DEPRECATO)
    $us = isset($_GET['us']) ? $_GET['us'] : '';
    $ps = isset($_GET['ps']) ? $_GET['ps'] : '';
    
    // Hash della password admin di default (MD5 di 'admin')
    if($us == 'admin' && $ps == 'b1735d59e2802bb7c20caba423e9fe3d'){
        $_SESSION["Admin"] = 'yes';
    }
}

if(!isset($_SESSION["Admin"]) || $_SESSION["Admin"] !== 'yes'){
    echo "Non Autorizzato";
    exit();
}

?>

<h1 style="text-align: center;">Canzoni Segnalate</h1>


        <?php
          include("config.php");
          $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
          if($myDB->connect_errno){
              echo "Errore di connessione";
              exit();
          }
          $risultato=$myDB->query("SELECT segnalazioni.id AS IDSegnalazione,segnalazioni.descrizione, canzoni.id AS IDCanzone,canzoni.NomeCanzone,canzoni.Autore,canzoni.IMG,canzoni.VID,canzoni.fkUser FROM segnalazioni INNER JOIN canzoni ON segnalazioni.fkCanzone = canzoni.id ORDER BY segnalazioni.id DESC");
echo "<table>
         <thead>
          <tr>
              <th>ID Segnalazione</th>
              <th>Descrizione</th>
              <th>ID Canzone</th>
              <th>Nome Canzone</th>
              <th>Autore</th>
              <th>IMG</th>
              <th>FILE</th>
              <th>ID Utente</th>
              <th>Visualizza</th>
              <th>Modifica</th>
              <th>Elimina Segnalazione</th>
            </tr>
      </thead>";

          while ($row=$risultato->fetch_assoc()) {

echo   '<tr>
          <td>'.$row["IDSegnalazione"].'</td>
          <td>'.$row["descrizione"].'</td>
          <td>'.$row["IDCanzone"].'</td>
          <td>'.$row["NomeCanzone"].'</td>
          <td>'.$row["Autore"].'</td>
          <td>'.$row["IMG"].'</td>
          <td>'.$row["VID"].'</td>
          <td>'.$row["fkUser"].'</td>
          <td><a class="button" href="../Player.php?Id='.$row["IDCanzone"].'">Visualizza</a></td>
          <td><a class="button"href="ModificaAdmin.php?id='.$row["IDCanzone"].'" >Modifica</a></td>
          <td><a class="button"href="Script_CancellaSegnalazione.php?id='.$row["IDCanzone"].'">EliminaSegnalazione</a></td>
        </tr>';
              }
echo "  </table>";




echo "<h1 style='text-align: center;'>Canzoni</h1>";

 $risultato1=$myDB->query("SELECT * FROM canzoni");
echo "<table>
         <thead>
            <tr>
              <th>ID Canzone</th>
              <th>Nome Canzone</th>
              <th>Autore</th>
              <th>IMG</th>
              <th>FILE</th>
              <th>ID Utente</th>
              <th>Visualizza</th>
              <th>Modifica</th>
              <th>Elimina</th>
            </tr>
          </thead>";

          while ($row=$risultato1->fetch_assoc()) {

echo   '<tr>        
          <td>'.$row["Id"].'</td>
          <td>'.$row["NomeCanzone"].'</td>
          <td>'.$row["Autore"].'</td>
          <td>'.$row["IMG"].'</td>
          <td>'.$row["VID"].'</td>
          <td>'.$row["fkUser"].'</td>
          <td><a class="button" href="../Player.php?Id='.$row["Id"].'">Visualizza</a></td>
          <td><a class="button" href="ModificaAdmin.php?id='.$row["Id"].'">Modifica</a></td>
          <td><a class="button" href="Script_EliminaAdmin.php?id='.$row["Id"].'"onclick="return confirm(&#039Sei sicuro di voler cancellare '.$row["NomeCanzone"].' ?&#039);">Elimina</a></td>
        </tr>';
              }
echo "  </table>";
?>

  </body>
</html>


