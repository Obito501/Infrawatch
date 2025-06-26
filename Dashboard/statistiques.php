<?php
require_once '../includes/init.php';
require_once '../BD/connect.php';

// Protection admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Données principales
$total_signals = $pdo->query("SELECT COUNT(*) FROM signalement")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
$en_attente = $pdo->query("SELECT COUNT(*) FROM signalement WHERE etat = 'en attente'")->fetchColumn();
$termine = $pdo->query("SELECT COUNT(*) FROM signalement WHERE etat = 'terminé'")->fetchColumn();

// Top utilisateurs
$top_users = $pdo->query("
    SELECT CONCAT(prenom, ' ', nom) AS nom, COUNT(*) AS total
    FROM signalement s
    JOIN utilisateur u ON u.utilisateur_id = s.utilisateur_id
    GROUP BY s.utilisateur_id
    ORDER BY total DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Signalements par mois
$month_data = $pdo->query("
    SELECT MONTH(date_signalement) AS mois, COUNT(*) AS total
    FROM signalement
    WHERE YEAR(date_signalement) = YEAR(CURDATE())
    GROUP BY mois
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Signalements par année
$year_data = $pdo->query("
    SELECT YEAR(date_signalement) AS annee, COUNT(*) AS total
    FROM signalement
    GROUP BY annee
")->fetchAll(PDO::FETCH_KEY_PAIR);

function moisNom($i) {
    return ucfirst(date('F', mktime(0, 0, 0, $i, 1)));
}

$mois = range(1, 12);
$signals_by_month = array_map(fn($m) => $month_data[$m] ?? 0, $mois);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Statistiques</title>
    <link rel="stylesheet" href="../CSS/dashadmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../JS/menu-burger.js" defer></script>
</head>
<body>
<div class="menu-toggle" onclick="toggleSidebar()">
  <i class="fa fa-bars"></i>
</div>
<!-- 📌 Sidebar -->
<div class="sidebar">
    <div class="side-header"><h3>Infra<span>Watch</span></h3></div>
    <div class="side-content">
        <div class="profile">
            <div class="profile-img bg-img" style="background-image: url(../img/admin.jpeg)"></div>
            <h4><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></h4>
            <small>Gestionnaire des Signalements</small>
        </div>
        <div class="side-menu">
                <ul>
                    <li><a href="../dashadmin.php"><i class="fa-solid fa-tachometer-alt"></i><small>Tableau de Bord</small></a></li>
                    <li><a href="liste_signalements.php"><i class="fa-solid fa-list-check"></i><small>Liste des Signalements</small></a></li>
                    <li><a href="gestion_utilisateurs.php"><i class="fa-solid fa-users"></i><small>Gestion des Utilisateurs</small></a></li>
                    <li><a href="statistiques.php" class="active"><i class="fa-solid fa-chart-line"></i><small>Statistiques</small></a></li>
                    <li><a href="Message.php"><i class="fa-solid fa-envelope"></i><small>Message</small></a></li>
                    <li><a href="../logout.php"><i class="fa-solid fa-power-off"></i><small>Déconnexion</small></a></li>
                </ul>
            </div>
    </div>
</div>

<!-- 🎯 Contenu principal -->
<div class="main-content">
    <header>
        <div class="header-content">
            <div class="header-menu">
                <div class="notify-icon"><i class="fa-solid fa-bell"></i></div>
                <div class="user">
                    <div class="bg-img" style="background-image: url(../img/admin.jpeg);"></div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="page-header">
            <h1>📊 Statistiques</h1>
            <small>Accueil / Statistiques</small>
        </div>

        <!-- 🔢 Cartes Résumées -->
        <div class="analytics">
            <div class="card"><div class="card-head"><h2><?= $total_signals ?></h2><i class="fa-solid fa-database"></i></div><div class="card-progress"><small>Total signalements</small><div class="card-indicator"><div class="indicator" style="width:100%"></div></div></div></div>
            <div class="card"><div class="card-head"><h2><?= $total_users ?></h2><i class="fa-solid fa-users"></i></div><div class="card-progress"><small>Total utilisateurs</small><div class="card-indicator"><div class="indicator" style="width:100%"></div></div></div></div>
            <div class="card"><div class="card-head"><h2><?= $en_attente ?></h2><i class="fa-solid fa-hourglass-start"></i></div><div class="card-progress"><small>En attente</small><div class="card-indicator"><div class="indicator" style="width:60%"></div></div></div></div>
            <div class="card"><div class="card-head"><h2><?= $termine ?></h2><i class="fa-solid fa-check"></i></div><div class="card-progress"><small>Terminés</small><div class="card-indicator"><div class="indicator" style="width:70%"></div></div></div></div>
        </div>

        <!-- 📈 Graphique Mensuel -->
        <div class="records table-responsive">
            <h3>📆 Signalements par mois (<?= date('Y') ?>)</h3>
            <canvas id="chartMois"></canvas>
        </div>

        <!-- 📆 Graphique Annuel -->
        <div class="records table-responsive">
            <h3>📅 Signalements par année</h3>
            <canvas id="chartAnnee"></canvas>
        </div>

        <!-- 🏆 Top utilisateurs -->
        <div class="records table-responsive">
            <h3>🏅 Top utilisateurs</h3>
            <table>
                <thead><tr><th>Utilisateur</th><th>Signalements</th></tr></thead>
                <tbody>
                    <?php foreach ($top_users as $user): ?>
                        <tr><td><?= htmlspecialchars($user['nom']) ?></td><td><?= $user['total'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
    const moisLabels = <?= json_encode(array_map('moisNom', $mois)) ?>;
    const moisData = <?= json_encode($signals_by_month) ?>;
    const yearLabels = <?= json_encode(array_keys($year_data)) ?>;
    const yearData = <?= json_encode(array_values($year_data)) ?>;

new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: moisLabels,
        datasets: [{
            label: 'Signalements par mois',
            data: moisData,
            backgroundColor: '#007bff'
        }]
    }
});

new Chart(document.getElementById('chartAnnee'), {
    type: 'line',
    data: {
        labels: yearLabels,
        datasets: [{
            label: 'Signalements par année',
            data: yearData,
            borderColor: '#28a745',
            backgroundColor: 'rgba(40,167,69,0.2)',
            tension: 0.3,
            fill: true
        }]
    }
});

</script>

</body>
</html>