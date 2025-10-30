 <?php
session_start();

include("config.php");
$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if($myDB->connect_errno){
    echo "Errore di connessione";
    exit();
}
//______________________________________________________________________________

if (isset($_FILES['IMG'])) {
$IMG_U=$_FILES['IMG'];

$IMG_U_name = $IMG_U['name'];
$IMG_U_tmp = $IMG_U['tmp_name'];
$IMG_U_size = $IMG_U['size'];
$IMG_U_error = $IMG_U['error'];

$IMG_U_ext = explode('.',$IMG_U_name);
$IMG_U_ext = strtolower(end($IMG_U_ext));
$allowed1 = array('png','pjp','jpg','pjpeg','jpg','jpeg','jfif');
if (in_array($IMG_U_ext, $allowed1)) {
  if ($IMG_U_error === 0) {
    if ($IMG_U_size <= 2097152) {
      $IMG_U_name_new = uniqid('',true).'.'.$IMG_U_ext;
      $IMG_U_des = 'IMG/COVER/'. $IMG_U_name_new;
      if (move_uploaded_file($IMG_U_tmp,$IMG_U_des)) {
        echo "Immagine Caricata";
                }

            }
     }
}
}

if (isset($_FILES['VID'])) {
$VID_U=$_FILES['VID'];

$VID_U_name = $VID_U['name'];
$VID_U_tmp = $VID_U['tmp_name'];
$VID_U_size = $VID_U['size'];
$VID_U_error = $VID_U['error'];

$VID_U_ext = explode('.',$VID_U_name);
$VID_U_ext = strtolower(end($VID_U_ext));
$allowed2 = array('mp4','m4v','mp3','m4a');
if (in_array($VID_U_ext, $allowed2)) {
  if ($VID_U_error === 0) {
  if ($IMG_U_size <= 2097150982) {
      $VID_U_name_new = uniqid('',true).'.'.$VID_U_ext;
      $VID_U_des = 'VID/'. $VID_U_name_new;
      if (move_uploaded_file($VID_U_tmp,$VID_U_des)) {
  echo "Video Caricato";
                }
}

     }
}
}
if (in_array($IMG_U_ext, $allowed1)) {
  if (in_array($VID_U_ext, $allowed2)) {
    $fkUser= $_SESSION['id'];
    $NomeC = $_POST['NomeCanzone'];
    $Autore = $_POST['Autore'];
    $IMG = $IMG_U_name_new;
    $VID = $VID_U_name_new;

    $stmt = $myDB->prepare("INSERT INTO canzoni (NomeCanzone, Autore, IMG, VID, fkUser) VALUES (?,?,?,?,?)");
    $stmt->bind_param('sssss',$NomeC,$Autore,$IMG,$VID,$fkUser);
    //______________________________________________________________________________
    if ($stmt->execute()) {
      echo "Canzone Caricata Con Successo !!!";
      header("Location: /");
      } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($myDB);
      }

    mysqli_close($myDB);
 }else {
   echo "Errore";
 }
}else {
  echo "Errore";
}

?>
