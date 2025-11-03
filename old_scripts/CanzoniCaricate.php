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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script>
			$(document).ready(function(){
			    $('.search-box input[type="text"]').on("keyup input", function(){
			        var inputVal = $(this).val();
			        var resultDropdown = $(this).siblings(".result");
			        if(inputVal.length){
			            $.get("ricerca.php", {term: inputVal}).done(function(data){
			                resultDropdown.html(data);
			            });
			        } else{
			            resultDropdown.empty();
			        }
			    });

			});
		</script>
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
				<img style="margin-left: -16px;" src="IMG/PAGINA/canzonicaricate.png" >
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
          $risultato=$myDB->query("SELECT * FROM canzoni WHERE fkUser =".$_SESSION['id']);
		  if (mysqli_num_rows($risultato) == 0){
          
          echo "Non hai ancora caricato nessuna canzone.";
          }
          while ($row=$risultato->fetch_assoc()) {
          echo '
				    <div class="card-wrap">
				      <div class="card">';
							if (isset($_SESSION["id"])) {

		//---------------------------------------------------------------------------------------------------------------------------------------------------------
		$Preferiti = mysqli_query($myDB, "SELECT fkCanzone,fkUtente FROM canzoniPreferite WHERE fkCanzone=".$row['Id']." AND fkUtente=".$_SESSION["id"]);
			 if (mysqli_num_rows($Preferiti) > 0){
						echo '<div id="bottone_colore'.$row["Id"].'"><button style="background:gold;" id="bottone'.$row["Id"].'"  onClick="elimina('.$row['Id'].')" class="bottones" ></button></div>';
			 }else{
					 echo '<div id="bottone_colore'.$row["Id"].'"><button style="background:grey;" id="bottone'.$row["Id"].'"  onClick="aggiungi('.$row['Id'].')" class="bottone" ></button></div>';
			 }
		//---------------------------------------------------------------------------------------------------------------------------------------------------------
											if ($_SESSION["id"]==$row["fkUser"]) {
											 echo '<a href="del_script.php?idc='.$row["Id"].'"onclick="return confirm(&#039Sei sicuro di voler cancellare '.$row["NomeCanzone"].' ?&#039);"><img src="IMG/PAGINA/del.png" id="del" >';
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
<a href="upload.php">
         <img src="IMG/PAGINA/upload.png" id="upup" style="z-index: 12;position: fixed;bottom: 9px;right: 11px;width: 70px;padding: 14px;">
         </a>
<script>
				 	function aggiungi(id) {
				 			var xmlhttp = new XMLHttpRequest();
				 			xmlhttp.onreadystatechange = function() {
				 					if ( xmlhttp.readyState == 4 ) {
				 							if ( xmlhttp.status == 200 ) {
				 									//alert('aggiunto');
				 									document.getElementById("bottone_colore"+id).innerHTML = xmlhttp.responseText;
				 							} else {
				 									alert( 'C’è stato un’errore' );
				 							}
				 					}
				 			}
				 			xmlhttp.open( 'GET', 'addfav.php?Id='+id, true );
				 			xmlhttp.send();
				 	}
				 	function elimina(id) {
				 			var xmlhttp = new XMLHttpRequest();
				 			xmlhttp.onreadystatechange = function() {
				 					if ( xmlhttp.readyState == 4 ) {
				 							if ( xmlhttp.status == 200 ) {
				 									//alert('eliminato');
				 									document.getElementById("bottone_colore"+id).innerHTML = xmlhttp.responseText;

				 							} else {
				 									alert( 'C’è stato un’errore' );
				 							}
				 					}
				 			}
				 			xmlhttp.open( 'GET', 'delfav.php?Id='+id, true );
				 			xmlhttp.send();
				 	}
				 </script>

				 <script>
			   let box = document.getElementById('box'),
			       btn = document.getElementById('ricerca');

			   btn.addEventListener('click', function () {

			     if (box.classList.contains('hidden')) {
			       box.classList.remove('hidden');
			       setTimeout(function () {
			         box.classList.remove('visuallyhidden');
			       }, 20);
			     } else {
			       box.classList.add('visuallyhidden');
			       box.addEventListener('transitionend', function(e) {
			         box.classList.add('hidden');
			       }, {
			         capture: false,
			         once: true,
			         passive: false
			       });
			     }

			   }, false);
			   </script>
  </body>
</html>
