<?php require_once 'includes/init.php'; 
require_once 'BD/connect.php'; 

// Comptage des signalements par état
try {
    $countNew = $pdo->query("SELECT COUNT(*) FROM signalement WHERE etat = 'en attente'")->fetchColumn();
    $countInProgress = $pdo->query("SELECT COUNT(*) FROM signalement WHERE etat = 'en cours'")->fetchColumn();
    $countResolved = $pdo->query("SELECT COUNT(*) FROM signalement WHERE etat = 'terminé'")->fetchColumn();
} catch (PDOException $e) {
    $countNew = $countInProgress = $countResolved = 0;
}

// 🔐 Sécurité : accès réservé aux admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Requête pour tous les signalements
try {
    $stmt = $pdo->prepare("SELECT signalement_id, lieu, etat FROM signalement ORDER BY signalement_id DESC");
    $stmt->execute();
    $signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors du chargement des signalements : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de Bord Administratif - Gestion des Signalements</title>
    <link rel="stylesheet" href="CSS/dashadmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="JS/menu-burger.js" defer></script>
</head>
<body>
<div class="menu-toggle" onclick="toggleSidebar()">
  <i class="fa fa-bars"></i>
</div>


    <div class="sidebar">
        <div class="side-header">
            <h3>Infra<span>Watch</span></h3>
        </div>
        
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(img/admin.jpeg)"></div>
                <h4>
                    <?php
                        echo isset($_SESSION['prenom'], $_SESSION['nom']) 
                            ? htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) 
                            : 'Administrateur';
                    ?>
                </h4>
                <small>Gestionnaire des Signalements</small>
            </div>

            <div class="side-menu">
                <ul>
                    <li><a href="dashadmin.php" class="active"><i class="fa-solid fa-tachometer-alt"></i><small>Tableau de Bord</small></a></li>
                    <li><a href="dashboard/liste_signalements.php"><i class="fa-solid fa-list-check"></i><small>Liste des Signalements</small></a></li>
                    <li><a href="Dashboard/gestion_utilisateurs.php"><i class="fa-solid fa-users"></i><small>Gestion des Utilisateurs</small></a></li>
                    <li><a href="dashboard/statistiques.php"><i class="fa-solid fa-chart-line"></i><small>Statistiques</small></a></li>
                    <li><a href="dashboard/Message.php"><i class="fa-solid fa-envelope"></i><small>Message</small></a></li>
                    <li><a href="logout.php"><i class="fa-solid fa-power-off"></i><small>Déconnexion</small></a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        
        <header>
            <div class="header-content">
                <div class="header-menu">
                    <div class="notify-icon"><i class="fa-solid fa-bell"></i><span class="notify">5</span></div>
                    <div class="user">
                        <div class="bg-img" style="background-image: url(img/admin.jpeg)"></div>
                    </div>
                </div>
            </div>
        </header>
        
        <main>
            <div class="page-header">
                <h1>Tableau de Bord Administratif</h1>
                <small>Accueil / Tableau de Bord</small>
            </div>
            
            <div class="page-content">
                <div class="analytics">
                    <!-- TODO: Données dynamiques si tu veux -->
                    <div class="card"><div class="card-head"><h2><?php echo $countNew; ?></h2><i class="fa-solid fa-exclamation-circle"></i></div><div class="card-progress"><small>Nouveaux Signalements</small><div class="card-indicator"><div class="indicator" style="width: 65%"></div></div></div></div>
                    <div class="card"><div class="card-head"><h2><?php echo $countInProgress; ?></h2><i class="fa-solid fa-spinner"></i></div><div class="card-progress"><small>Signalements en Cours</small><div class="card-indicator"><div class="indicator" style="width: 60%"></div></div></div></div>
                    <div class="card"><div class="card-head"><h2><?php echo $countResolved; ?></h2><i class="fa-solid fa-check-circle"></i></div><div class="card-progress"><small>Signalements Résolus</small><div class="card-indicator"><div class="indicator" style="width: 80%"></div></div></div></div>
                </div>

                <div class="records table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lieu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($signalements)): ?>
                                <tr>
                                    <td colspan="4" style="text-align:center;">Aucun signalement trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($signalements as $sig): ?>
                                    <?php
                                        $etat = $sig['etat'];
                                        $class = match($etat) {
                                            'en attente' => 'new',
                                            'en cours' => 'in-progress',
                                            'terminé' => 'resolved',
                                            default => ''
                                        };
                                    ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($sig['signalement_id']); ?></td>
                                        <td><?php echo htmlspecialchars($sig['lieu']); ?></td>
                                        <td><span class="status <?php echo $class; ?>"><?php echo ucfirst($etat); ?></span></td>
                                        <td>
                                            <a href="dashboard/detail-signalement.php?id=<?php echo $sig['signalement_id']; ?>" class="btn-detail">
                                                <i class="fa-solid fa-eye"></i> Détails
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>