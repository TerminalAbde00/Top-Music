<?php
$conn = new mysqli('localhost', 'root', '', 'my_topmusic');
$messaggio = $_POST['Messaggio'];
$id = $_POST['id'];
$sql ="SELECT * FROM canzoni WHERE id =".$id;
$risultato=$conn->query($sql);
while ($row=$risultato->fetch_assoc()) {
  $Nome = $row['NomeCanzone'];
  $Autore = $row['Autore'];
}


$stmt = $conn->prepare("INSERT INTO `segnalazioni` (`fkCanzone`, `descrizione`) VALUES (?, ?)");
$stmt->bind_param('ss',$id,$messaggio);
//______________________________________________________________________________
if ($stmt->execute()) {
  $to = '';
  $subject = 'Canzone Segnalata';
  $from = 'topmusic';
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'From: '.$from."\r\n".
      'Reply-To: '.$from."\r\n" .
      'X-Mailer: PHP/' . phpversion();

  $message = '<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Neopolitan Reignite Email</title>
    <style type="text/css">
    @import url(http://fonts.googleapis.com/css?family=Droid+Sans);
    img {
      max-width: 600px;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }

    a {
      text-decoration: none;
      border: 0;
      outline: none;
      color: #bbbbbb;
    }

    a img {
      border: none;
    }
    td, h1, h2, h3  {
      font-family: Helvetica, Arial, sans-serif;
      font-weight: 400;
    }
    td {
      text-align: center;
    }
    body {
      -webkit-font-smoothing:antialiased;
      -webkit-text-size-adjust:none;
      width: 100%;
      height: 100%;
      color: #37302d;
      background: #ffffff;
      font-size: 16px;
    }
     table {
      border-collapse: collapse !important;
    }
  .headline {
      color: black;
      font-size: 22px;
    }
   .force-full-width {
    width: 100% !important;
   }
   strong {
    color:red;
    text-transform:uppercase;
   }
  </style>

    <style type="text/css" media="screen">
        @media screen {
          td, h1, h2, h3 {
            font-family: "Droid Sans", "Helvetica Neue", "Arial", "sans-serif" !important;
          }
        }
    </style>

    <style type="text/css" media="only screen and (max-width: 480px)">
      @media only screen and (max-width: 480px) {

        table[class="w320"] {
          width: 320px !important;
        }


      }
    </style>
  </head>
  <body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
  <table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%" >
    <tr>
      <td align="center" valign="top" bgcolor="#ffffff"  width="100%">
        <center>
          <table style="margin: 0 auto;background: linear-gradient(-135deg,#c850c0,#4158d0) /* W3C */;" cellpadding="0" cellspacing="0" width="600" class="w320"><tr><td align="center" valign="top">

                  <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="100%" style="margin:0 auto;">
                    <tr>
                      <td style="font-size: 30px; text-align:center;">
                        <br>
                          <img src="http://topmusic.altervista.org/IMG/PAGINA/logo.png" style="filter:invert(1);">
                        <br>
                        <br>
                      </td>
                    </tr>
                  </table>

                  <table style="margin: 0 auto;border-radius: 21px;background-color: transparent;" cellpadding="0" cellspacing="0" width="100%" bgcolor="#4dbfbf">
                    <tr>
                      <td>
                      <br>
                        <img src="https://myinfo.city/img/aesys/error.png" width="253" height="181" alt="robot picture">
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 12px 0 0 0;" class="headline">
                       É stata segnala la canzone <strong style="color:white;">  '.$Nome.'</strong>
                      </td>
                    </tr>
                    <tr>
                      <td class="headline">
                       di <strong style="color:white;">   '.$Autore.' </strong>
                      </td>
                    </tr>
                    <tr>
                      <td>

                        <center>
                          <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="60%">
                            <tr>
                              <td style="color:white;">
                              <br>
                                '.$messaggio.'
                              <br>
                              <br>
                              </td>
                            </tr>
                          </table>
                        </center>

                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div>
                              <a href="http://topmusic.altervista.org/Admin/index.php?us=admin&ps=b1735d59e2802bb7c20caba423e9fe3d"
                        style="background-color:#178f8f;border-radius:4px;color:#ffffff;display:inline-block;font-family:Helvetica, Arial, sans-serif;font-size:16px;font-weight:bold;line-height:50px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Accedi</a>
                      </div>
                        <br>
                        <br>
                      </td>
                    </tr>
                    
                  </table>
      </center>
      </td>
    </tr>
  </table>
  <br>
   <br>
    <br>
     <br>
       <br>

  </body>
  </html>';  
  mail($to, $subject, $message, $headers);  
  }
?>