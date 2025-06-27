<?php
require_once '../includes/init.php';
require_once '../BD/connect.php';

// 🔐 Sécurité admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// 📦 Récupération des messages
$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY date_message DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Messages de Contact</title>
    <link rel="stylesheet" href="../CSS/dashadmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="../JS/menu-burger.js" defer></script>
</head>

<body>
    <div class="menu-toggle" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </div>
    <!-- 📌 Sidebar -->
    <div class="sidebar">
        <div class="side-header">
            <h3>Infra<span>Watch</span></h3>
        </div>
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
                    <li><a href="statistiques.php"><i class="fa-solid fa-chart-line"></i><small>Statistiques</small></a></li>
                    <li><a href="Message.php" class="active"><i class="fa-solid fa-envelope"></i><small>Message</small></a></li>
                    <li><a href="../logout.php"><i class="fa-solid fa-power-off"></i><small>Déconnexion</small></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content p-4">
        <h2>📩 Messages de Contact</h2>

        <?php if (empty($messages)): ?>
            <div class="alert alert-info">Aucun message reçu.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $m): ?>
                            <tr>
                                <td><?= $m['message_id'] ?></td>
                                <td><?= htmlspecialchars($m['nom']) ?></td>
                                <td><?= htmlspecialchars($m['email']) ?></td>
                                <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($m['date_message'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>