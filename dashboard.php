<?php 
require_once 'includes/init.php';
require 'BD/connect.php'; 

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];

try {
    $stmt = $pdo->prepare("SELECT signalement_id, lieu, etat FROM signalement WHERE utilisateur_id = ?");
    $stmt->execute([$utilisateur_id]);
    $signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfraSignal - Dashboard</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

     <nav class="navbar-mobile">
        <?php include 'includes\nav.php'; ?>
    </nav>
    <!--  Sidebar -->
    <div class="sidebar">
        <div class="side-header">
            <h3>Infra<span>Watch</span></h3>
        </div>

        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(img/user.jpeg)"></div>
                <h4>
                    <?php
                        if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
                            echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']);
                        } else {
                            echo 'Utilisateur';
                        }
                    ?>
                </h4>
                <small>Citoyen</small>
            </div>

            <div class="side-menu">
                <ul>
                    <li><a href="index.php"><i class="fa-solid fa-house"></i><small>Accueil</small></a></li>
                    <li><a href="signaler.php"><i class="fa-solid fa-exclamation-triangle"></i><small>Signaler</small></a></li>
                    <li><a href="dashboard.php" class="active"><i class="fa-solid fa-list"></i><small>Suivi</small></a></li>
                    <li><a href="Dashboard/Profil.php"><i class="fa-solid fa-user"></i><small>Profil</small></a></li>
                    <li><a href="contact.php"><i class="fa-solid fa-headset"></i><small>Assistance</small></a></li>
                    <li><a href="logout.php"><i class="fa-solid fa-power-off"></i><small>Déconnexion</small></a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-content">
                <div class="header-menu">
                    <!-- <div class="notify-icon"><i class="fa-solid fa-bell"></i><span class="notify"></span></div> -->
                    <div class="user">
                        <div class="bg-img" style="background-image: url(img/user.jpeg); width: 40px; height: 40px; border-radius: 50%; background-size: cover;"></div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="page-header">
                <h1>Suivi des Signalements</h1>
                <small>Accueil / Signalements</small>
            </div>

            <div class="analytics">
                <!-- cards ici -->
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
                                        'en attente' => 'waiting',
                                        'en cours' => 'in-progress',
                                        'terminé' => 'resolved',
                                        default => ''
                                    };
                                ?>
                                <tr>
                                    <td data-label="#">#<?php echo htmlspecialchars($sig['signalement_id']); ?></td>
                                    <td data-label="Lieu"><?php echo htmlspecialchars($sig['lieu']); ?></td>
                                    <td data-label="Statut">
                                        <span class="status <?php echo $class; ?>">
                                            <?php echo ucfirst($etat); ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions">
                                        <a href="Dashboard/detail.php?id=<?php echo $sig['signalement_id']; ?>" class="btn-detail">
                                            <i class="fa-solid fa-eye"></i> Détails
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>
</html>