<?php
require_once("../config.php");

header('Content-Type: text/html; charset=utf-8');

// Connessione database
$conn = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);

if ($conn->connect_errno) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (isset($_REQUEST["term"])) {
    $sql = "SELECT Id, NomeCanzone, Autore, IMG FROM canzoni WHERE (NomeCanzone LIKE ? OR Autore LIKE ?) LIMIT 8";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $param_term = '%' . htmlspecialchars($_REQUEST["term"], ENT_QUOTES, 'UTF-8') . '%';
        mysqli_stmt_bind_param($stmt, "ss", $param_term, $param_term);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<a href="Player.php?Id=' . htmlspecialchars($row['Id'], ENT_QUOTES, 'UTF-8') . '" style="text-decoration: none; color: black;">
                            <table>
                              <tr>
                                <td rowspan="2"><img src="IMG/COVER/' . htmlspecialchars($row['IMG'], ENT_QUOTES, 'UTF-8') . '" style="width:46px;height:46px;"></td>
                                <td width=215px><strong>' . htmlspecialchars($row["NomeCanzone"], ENT_QUOTES, 'UTF-8') . '</strong></td>
                              </tr>
                              <tr>
                                <td width=215px>' . htmlspecialchars($row["Autore"], ENT_QUOTES, 'UTF-8') . '</td>
                              </tr>
                            </table>
                          </a>';
                }
            } else {
                echo '<table>
                        <tr>
                            <td width=215px><strong>Nessun Risultato</strong></td>
                        </tr>
                      </table>';
            }
        } else {
            echo "ERROR: Could not execute query";
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
exit();
?>
