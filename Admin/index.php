<!DOCTYPE html>
<html lang="it" dir="ltr">
<head>  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>🔐 Admin Panel - TOP MUSIC</title>
</head>
<body>
<?php
session_start();
require_once("config.php");

// AUTENTICAZIONE ADMIN
if(!isset($_SESSION["Admin"]) || $_SESSION["Admin"] !== 'yes'){
    $us = isset($_GET['us']) ? trim($_GET['us']) : '';
    $ps = isset($_GET['ps']) ? $_GET['ps'] : '';
    
    // Hash MD5 di "admin"
    $admin_password_hash = 'b1735d59e2802bb7c20caba423e9fe3d';
    $ps_hash = md5($ps);
    
    if($us == 'admin' && $ps_hash == $admin_password_hash){
        $_SESSION["Admin"] = 'yes';
        header("Location: index.php");
        exit();
    } else if($us == '' && $ps == '') {
        // Prima visita, mostra login
    } else if($us != '' || $ps != '') {
        // Tentativo di login fallito
        $error_msg = '❌ Credenziali non valide!';
    }
    
    echo '
    <div class="login-container">
        <div class="login-box">
            <h1>🔐 Admin Login</h1>';
    
    if(isset($error_msg)) {
        echo '<p style="color: red; text-align: center; margin-bottom: 15px;">' . $error_msg . '</p>';
    }
    
    echo '
            <form method="GET">
                <input type="text" name="us" placeholder="Username" required value="' . htmlspecialchars($us) . '">
                <input type="password" name="ps" placeholder="Password" required>
                <button type="submit">Accedi</button>
            </form>
            <p class="login-note">📝 </p>
        </div>
    </div>';
    exit();
}

// DATABASE CONNECTION
$myDB = new mysqli(SERVER, UTENTE, PASSWORD, DATABASE);
if($myDB->connect_errno){
    die('<div class="error-container"><p>❌ Errore connessione database</p></div>');
}

// STATISTICHE DASHBOARD
$stats_songs = 0;
$stats_users = 0;
$stats_reports = 0;
$stats_favorites = 0;

$result_songs = $myDB->query("SELECT COUNT(*) as count FROM canzoni");
if($result_songs) {
    $stats_songs = $result_songs->fetch_assoc()['count'];
}

$result_users = $myDB->query("SELECT COUNT(*) as count FROM utenti");
if($result_users) {
    $stats_users = $result_users->fetch_assoc()['count'];
}

$result_reports = $myDB->query("SELECT COUNT(*) as count FROM segnalazioni");
if($result_reports) {
    $stats_reports = $result_reports->fetch_assoc()['count'];
}

$result_favorites = $myDB->query("SELECT COUNT(*) as count FROM canzoniPreferite");
if($result_favorites) {
    $stats_favorites = $result_favorites->fetch_assoc()['count'];
}
?>

<!-- NAVBAR ADMIN -->
<nav class="admin-navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <h1>🎵 TOP MUSIC - ADMIN PANEL</h1>
        </div>
        <div class="navbar-menu">
            <button class="tab-btn active" onclick="showTab('dashboard')">📊 Dashboard</button>
            <button class="tab-btn" onclick="showTab('reports')">🚨 Segnalazioni</button>
            <button class="tab-btn" onclick="showTab('songs')">🎵 Canzoni</button>
            <button class="tab-btn" onclick="showTab('users')">👥 Utenti</button>
            <a href="../index.php" class="nav-link">← Torna al sito</a>
        </div>
    </div>
</nav>

<!-- DASHBOARD OVERVIEW -->
<div id="dashboard" class="tab-content active">
    <div class="dashboard-container">
        <h2>📈 Overview Dashboard</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🎵</div>
                <div class="stat-content">
                    <h3>Canzoni Totali</h3>
                    <p class="stat-value"><?php echo $stats_songs; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h3>Utenti Registrati</h3>
                    <p class="stat-value"><?php echo $stats_users; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-content">
                    <h3>Preferiti Totali</h3>
                    <p class="stat-value"><?php echo $stats_favorites; ?></p>
                </div>
            </div>
            
            <div class="stat-card alert">
                <div class="stat-icon">🚨</div>
                <div class="stat-content">
                    <h3>Segnalazioni Pendenti</h3>
                    <p class="stat-value"><?php echo $stats_reports; ?></p>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>ℹ️ Informazioni Sistema</h3>
            <table class="info-table">
                <tr>
                    <td><strong>Database:</strong></td>
                    <td><?php echo DATABASE; ?></td>
                </tr>
                <tr>
                    <td><strong>PHP Version:</strong></td>
                    <td><?php echo phpversion(); ?></td>
                </tr>
                <tr>
                    <td><strong>Data Accesso:</strong></td>
                    <td><?php echo date('d/m/Y H:i:s'); ?></td>
                </tr>
                <tr>
                    <td><strong>Server:</strong></td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- TAB SEGNALAZIONI -->
<div id="reports" class="tab-content">
    <div class="section-container">
        <h2>🚨 Canzoni Segnalate (<?php echo $stats_reports; ?>)</h2>
        
        <?php
        $stmt = $myDB->prepare("
            SELECT 
                s.id AS segnalazione_id,
                s.descrizione,
                c.Id AS canzone_id,
                c.NomeCanzone,
                c.Autore,
                c.IMG,
                c.fkUser,
                u.nome as user_name
            FROM segnalazioni s
            INNER JOIN canzoni c ON s.fkCanzone = c.Id
            LEFT JOIN utenti u ON c.fkUser = u.id
            ORDER BY s.id DESC
        ");
        
        if (!$stmt) {
            echo '<p class="error">❌ Errore query: ' . $myDB->error . '</p>';
        } else {
            if (!$stmt->execute()) {
                echo '<p class="error">❌ Errore esecuzione: ' . $stmt->error . '</p>';
            } else {
                $result = $stmt->get_result();
                
                if ($result->num_rows == 0) {
                    echo '<p class="success">✅ Nessuna segnalazione pendente</p>';
                } else {
                echo '<div class="table-responsive">';
                echo '<table class="admin-table">';
                echo '<thead><tr>';
                echo '<th>🆔 ID</th>';
                echo '<th>🎵 Canzone</th>';
                echo '<th>👤 Artista</th>';
                echo '<th>📝 Descrizione</th>';
                echo '<th>🔍 Segnalazione</th>';
                echo '<th>👁️ Azioni</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                
                while ($row = $result->fetch_assoc()) {
                    echo '<tr class="report-row">';
                    echo '<td class="id-cell">#' . $row['segnalazione_id'] . '</td>';
                    echo '<td><strong>' . htmlspecialchars($row['NomeCanzone']) . '</strong></td>';
                    echo '<td>' . htmlspecialchars($row['Autore']) . '</td>';
                    echo '<td class="desc-cell">' . htmlspecialchars(substr($row['descrizione'], 0, 50)) . '...</td>';
                    echo '<td>Segnalazione #' . $row['segnalazione_id'] . '</td>';
                    echo '<td class="actions">';
                    echo '<a href="../Player.php?Id=' . $row['canzone_id'] . '" class="btn-small btn-view" title="Visualizza">👁️</a>';
                    echo '<a href="ModificaAdmin.php?id=' . $row['canzone_id'] . '" class="btn-small btn-edit" title="Modifica">✏️</a>';
                    echo '<a href="Script_CancellaSegnalazione.php?id=' . $row['segnalazione_id'] . '" class="btn-small btn-delete" title="Risolvi" onclick="return confirm(\'Archiviare questa segnalazione?\')">✓</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
                echo '</div>';
                }
            }
            $stmt->close();
        }
        ?>
    </div>
</div>

<!-- TAB CANZONI -->
<div id="songs" class="tab-content">
    <div class="section-container">
        <h2>🎵 Gestione Canzoni (<?php echo $stats_songs; ?>)</h2>
        
        <?php
        $stmt = $myDB->prepare("
            SELECT 
                c.Id,
                c.NomeCanzone,
                c.Autore,
                c.IMG,
                c.fkUser,
                u.nome as user_name,
                (SELECT COUNT(*) FROM canzoniPreferite WHERE fkCanzone = c.Id) as favorite_count
            FROM canzoni c
            LEFT JOIN utenti u ON c.fkUser = u.id
            ORDER BY c.Id DESC
        ");
        
        if (!$stmt) {
            echo '<p class="error">❌ Errore query</p>';
        } else {
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 0) {
                echo '<p>Nessuna canzone nel sistema</p>';
            } else {
                echo '<div class="table-responsive">';
                echo '<table class="admin-table">';
                echo '<thead><tr>';
                echo '<th>🆔 ID</th>';
                echo '<th>🎵 Canzone</th>';
                echo '<th>👤 Artista</th>';
                echo '<th>📤 Caricata da</th>';
                echo '<th>⭐ Preferiti</th>';
                echo '<th>👁️ Azioni</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                
                while ($row = $result->fetch_assoc()) {
                    echo '<tr class="song-row">';
                    echo '<td class="id-cell">#' . $row['Id'] . '</td>';
                    echo '<td><strong>' . htmlspecialchars($row['NomeCanzone']) . '</strong></td>';
                    echo '<td>' . htmlspecialchars($row['Autore']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['user_name'] ?? 'Sistema') . '</td>';
                    echo '<td><span class="badge">' . $row['favorite_count'] . ' ⭐</span></td>';
                    echo '<td class="actions">';
                    echo '<a href="../Player.php?Id=' . $row['Id'] . '" class="btn-small btn-view" title="Visualizza">👁️</a>';
                    echo '<a href="ModificaAdmin.php?id=' . $row['Id'] . '" class="btn-small btn-edit" title="Modifica">✏️</a>';
                    echo '<a href="Script_EliminaAdmin.php?id=' . $row['Id'] . '" class="btn-small btn-delete" title="Elimina" onclick="return confirm(\'Eliminare ' . htmlspecialchars($row['NomeCanzone']) . '?\')">🗑️</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
                echo '</div>';
            }
            $stmt->close();
        }
        ?>
    </div>
</div>

<!-- TAB UTENTI -->
<div id="users" class="tab-content">
    <div class="section-container">
        <h2>👥 Gestione Utenti (<?php echo $stats_users; ?>)</h2>
        
        <?php
        $stmt = $myDB->prepare("
            SELECT 
                u.id,
                u.nome,
                u.username,
                u.email,
                u.Data,
                (SELECT COUNT(*) FROM canzoni WHERE fkUser = u.id) as song_count,
                (SELECT COUNT(*) FROM canzoniPreferite WHERE fkUtente = u.id) as favorite_count
            FROM utenti u
            ORDER BY u.id DESC
        ");
        
        if (!$stmt) {
            echo '<p class="error">❌ Errore query</p>';
        } else {
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 0) {
                echo '<p>Nessun utente registrato</p>';
            } else {
                echo '<div class="table-responsive">';
                echo '<table class="admin-table">';
                echo '<thead><tr>';
                echo '<th>🆔 ID</th>';
                echo '<th>👤 Nome Completo</th>';
                echo '<th>✉️ Username</th>';
                echo '<th>📧 Email</th>';
                echo '<th>🎵 Canzoni</th>';
                echo '<th>⭐ Preferiti</th>';
                echo '<th>📅 Data Iscrizione</th>';
                echo '<th>👁️ Azioni</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                
                while ($row = $result->fetch_assoc()) {
                    echo '<tr class="user-row">';
                    echo '<td class="id-cell">#' . $row['id'] . '</td>';
                    echo '<td><strong>' . htmlspecialchars($row['nome']) . '</strong></td>';
                    echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email'] ?? 'N/A') . '</td>';
                    echo '<td><span class="badge">' . $row['song_count'] . ' 🎵</span></td>';
                    echo '<td><span class="badge">' . $row['favorite_count'] . ' ⭐</span></td>';
                    echo '<td>' . (isset($row['Data']) ? $row['Data'] : 'N/A') . '</td>';
                    echo '<td class="actions">';
                    echo '<a href="Script_EliminaAdmin.php?id=' . $row['id'] . '&type=user" class="btn-small btn-delete" title="Elimina" onclick="return confirm(\'Eliminare utente ' . htmlspecialchars($row['nome']) . '?\')">🗑️</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
                echo '</div>';
            }
            $stmt->close();
        }
        
        $myDB->close();
        ?>
    </div>
</div>

<!-- FOOTER -->
<footer class="admin-footer">
    <p>🔐 Admin Panel | TOP MUSIC v1.0 | <?php echo date('Y'); ?> © Tutti i diritti riservati</p>
    <p><a href="?logout=1" onclick="if(confirm('Sei sicuro di voler uscire?')) { location.href='index.php?us=&ps='; return false; }">🚪 Logout</a></p>
</footer>

<script>
function showTab(tabName) {
    // Nascondi tutti i tab
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Mostra il tab selezionato
    document.getElementById(tabName).classList.add('active');
    
    // Aggiorna button attivo
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    if(event && event.target) event.target.classList.add('active');
}

// Smooth animations
document.addEventListener('DOMContentLoaded', function() {
    // Fade in effet
    document.querySelectorAll('.tab-content').forEach(el => {
        el.style.animation = 'fadeIn 0.3s ease-in';
    });
});
</script>

</body>
</html>


