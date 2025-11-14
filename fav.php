<?php
	session_start();
?>
<!DOCTYPE html>
<html >
  <head>
		<title>TOP MUSIC - Discover new music everyday</title>
  	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style_HomeOk.css">
    <meta charset="utf-8">
    <link rel="icon" href="IMG/PAGINA/top.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="JS/scriptIndex.js"></script>
  </head>

  <body style="background-color:#f2f2f2">
		<br><br><br>
		<div class="bar" style="z-index:7;top:0;">
		<?php
			if (isset($_SESSION["id"])) {
			echo'<a href="/"><img id="LeftI" src="IMG/PAGINA/home.png" style="transition: transform 250ms;top:0;left: 0;width: 62px;margin-left:27px;margin-top:6px;position: absolute;cursor: pointer;"></a>';
			}
		?>
			<div class="logo">
				<img style="margin-left: -16px;" src="IMG/PAGINA/prefe.png" >
			</div>

		</div>

		<button id="ricerca"style="width: 40px; filter: invert(1);  position: fixed; z-index: 9; right: 0; top: 0; margin-right: 40px;margin-top: 20px;"> <img src="IMG/PAGINA/cerca1.png" width=30px> </button>

		    <div id="box" class="search-box visuallyhidden hidden" >
		        <input type="text" autocomplete="off" placeholder="Cerca la tua canzone" />
		        <div class="result"></div>
		    </div>

<div id="app" class="container">
        <?php
          include("config.php");
          $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
          if($myDB->connect_errno){
              echo "Errore di connessione";
              exit();
          }
          // Usa prepared statement per evitare SQL injection
          $stmt = $myDB->prepare("SELECT canzoni.*,canzoniPreferite.fkCanzone,canzoniPreferite.fkUtente FROM canzoni INNER JOIN canzoniPreferite ON canzoni.Id=canzoniPreferite.fkCanzone WHERE canzoniPreferite.fkUtente = ? ORDER BY canzoniPreferite.id DESC");
          $stmt->bind_param('i', $_SESSION["id"]);
          $stmt->execute();
          $risultato = $stmt->get_result();
		  if (mysqli_num_rows($risultato) == 0){
          
          echo "Nessuna canzone tra i preferiti.";
          }
          while ($row=$risultato->fetch_assoc()) {
          echo '
				    <div class="card-wrap">
				      <div class="card">';
							if (isset($_SESSION["id"])) {


		// Usa prepared statement per evitare SQL injection
		$stmtFav = $myDB->prepare("SELECT fkCanzone,fkUtente FROM canzoniPreferite WHERE fkCanzone = ? AND fkUtente = ?");
		$stmtFav->bind_param('ii', $row['Id'], $_SESSION["id"]);
		$stmtFav->execute();
		$Preferiti = $stmtFav->get_result();
		$stmtFav->close();
		
		if ($Preferiti->num_rows > 0){
					echo '<div id="bottone_colore'.$row["Id"].'"><button style="background:gold;" id="bottone'.$row["Id"].'"  onClick="elimina('.$row['Id'].')" class="bottones" ></button></div>';
		}else{
				 echo '<div id="bottone_colore'.$row["Id"].'"><button style="background:grey;" id="bottone'.$row["Id"].'"  onClick="aggiungi('.$row['Id'].')" class="bottone" ></button></div>';
		}
											if ($_SESSION["id"]==$row["fkUser"]) {
											 echo '<a href="api/delete_song.php?idc='.$row["Id"].'"onclick="return confirm(&#039Sei sicuro di voler cancellare '.$row["NomeCanzone"].' ?&#039);"><img src="IMG/PAGINA/del.png" id="del" >';
													}
												}
				       echo '<a href="Player.php?Id='.$row["Id"].'" style="color: black;text-decoration: none;">
				       <img style=" width: 240px;  height: 240px;" src="IMG/COVER/'.$row["IMG"].'" >
				       <div style="padding: 3px 12px;" >
				         <p style="font-size: 20px;width: 99%;font-style: unset;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
				           <b>'.$row["NomeCanzone"].'</b>
				         </p>
				         <p>'.$row["Autore"].'</p>
				       </div>
				     </div>
				     </a>
				  </div>';

              }

         ?>
</div>
  </body>
</html>
