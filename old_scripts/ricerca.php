<?php
$link = mysqli_connect("localhost", "root", "", "my_topmusic");
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
if(isset($_REQUEST["term"])){

    $sql = "SELECT * FROM canzoni WHERE (NomeCanzone LIKE ? OR Autore LIKE ?)";

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "ss", $param_term,$param_term);
        $param_term = '%'.$_REQUEST["term"] . '%';
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    echo '<a href="Player.php?Id='.$row['Id'].'" style="text-decoration: none;color:black;">
                            <table >
                              <tr>
                                <td rowspan="2"><img src="IMG/COVER/'.$row['IMG'].'" style="width:46px;height:46px;"></td>
                                <td width=215px> <strong> '. $row["NomeCanzone"] . '</strong></td>
                              </tr>
                              <tr>
                                <td width=215px>'.$row["Autore"].'</td>
                              </tr>
                            </table>
                          </a>';
                }
            } else{
                echo '  <table>
                          <tr>
                              <td width=215px><strong>Nessun Risultato</strong></td>
                          </tr>
                        </table>';
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);
?>
