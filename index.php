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

	<body class="page-bg">
		<br><br><br>
		<div class="bar bar--top">
			<?php
				if (isset($_SESSION["id"])) {
				echo'<a href="fav.php"><img id="LeftI" src="IMG/PAGINA/star.png"></a>';
				}
			?>
			<div class="logo">
				<img src="IMG/PAGINA/logo.png" >
			</div>
		</div>
	<button id="ricerca"> <img src="IMG/PAGINA/cerca1.png" width=30px> </button>
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
          $risultato=$myDB->query("SELECT Id, NomeCanzone, Autore, IMG,fkUser FROM canzoni ORDER BY Id DESC");
          while ($row=$risultato->fetch_assoc()) {
		  echo '<div class="card-wrap"><div class="card">';
							if (isset($_SESSION["id"])) {
		//---------------------------------------------------------------------------------------------------------------------------------------------------------
				$Preferiti = mysqli_query($myDB, "SELECT fkCanzone,fkUtente FROM canzoniPreferite WHERE fkCanzone=".$row['Id']." AND fkUtente=".$_SESSION["id"]);
				     if (mysqli_num_rows($Preferiti) > 0){
						echo '<div id="bottone_colore'.$row["Id"].'"><button id="bottone'.$row["Id"].'"  onClick="elimina('.$row['Id'].')" class="bottones" ></button></div>';
					}else{
						echo '<div id="bottone_colore'.$row["Id"].'"><button id="bottone'.$row["Id"].'"  onClick="aggiungi('.$row['Id'].')" class="bottone" ></button></div>';
					}
		//---------------------------------------------------------------------------------------------------------------------------------------------------------
									if ($_SESSION["id"]==$row["fkUser"]) {
										echo '<a href="del_script.php?idc='.$row["Id"].'"onclick="return confirm(&#039Sei sicuro di voler cancellare '.$row["NomeCanzone"].' ?&#039);"><img src="IMG/PAGINA/del.png" id="del" >';
									}
							}
                                       echo '<a href="Player.php?Id='.$row["Id"].'" class="song-link">
                                       	       <img class="cover-img" src="IMG/COVER/'.$row["IMG"].'" >
                                       	       <div class="card-info" >
                                       	         <p class="song-title">
                                       	           <b>'.$row["NomeCanzone"].'</b>
                                       	         </p>
                                       	         <p class="song-author">'.$row["Autore"].'</p>
                                       	       </div>
                                       	     </div>
                                       	     </a>
                                       	  </div>';
              }
         ?>
         </div>
		 <a href="upload.php">
		 <img src="IMG/PAGINA/upload.png" id="upup">
		 </a>
  </body>
</html>
