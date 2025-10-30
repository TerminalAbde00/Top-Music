<?php
	session_start();

          include("config.php");
          $myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
          if($myDB->connect_errno){
              echo "Errore di connessione";
              exit();
          }
          $Id_C = $_GET["idc"];
          $risultato=$myDB->query("SELECT Id, NomeCanzone, Autore, IMG, VID, fkUser FROM canzoni WHERE  id = $Id_C.");

          $row=$risultato->fetch_assoc();
       
       if (isset($_SESSION["id"])) {
	            if ($_SESSION["id"]==$row["fkUser"]) {


                 $sql= 'DELETE FROM canzoni WHERE id = '.$Id_C.'';
                 $path1 = 'IMG/COVER/'.$row["IMG"];
                 $path2 = 'VID/'.$row["VID"];
				
                 unlink($path1);
                 unlink($path2);

                 if ($myDB->query($sql) === TRUE) {
                         echo "Record deleted successfully";
                         header("Location: /");
                        } else {
                          echo "Error deleting record: " . $myDB->error;
                        }
                      
	                }
								}
       
      




				



		
         ?>
