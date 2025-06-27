<?php
require_once '../includes/init.php';
require_once '../BD/connect.php';

// 🔐 Admin uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 🔧 Traitement
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? null;
    $id = $_POST['utilisateur_id'] ?? null;

    if ($action && $id && is_numeric($id)) {
        if ($action === 'supprimer') {
            $pdo->prepare("DELETE FROM utilisateur WHERE utilisateur_id = ?")->execute([$id]);
            $_SESSION['message'] = "Utilisateur supprimé.";
        }

        if ($action === 'changer_role') {
            $nouveau_role = $_POST['nouveau_role'] ?? 'utilisateur';
            $pdo->prepare("UPDATE utilisateur SET role = ? WHERE utilisateur_id = ?")->execute([$nouveau_role, $id]);
            $_SESSION['message'] = "Rôle mis à jour.";
        }

        header("Location:gestion_utilisateurs.php");
        exit();
    }
}

$stmt = $pdo->query("SELECT * FROM utilisateur ORDER BY nom");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../CSS/dashadmin.css">   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="../JS/Menu-burger.js" defer></script>
</head>
<body>
<div class="menu-toggle" onclick="toggleSidebar()">
  <i class="fa fa-bars"></i>
</div>

<!-- 📌 SIDEBAR -->
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
                    <li><a href="gestion_utilisateurs.php" class="active"><i class="fa-solid fa-users"></i><small>Gestion des Utilisateurs</small></a></li>
                    <li><a href="statistiques.php"><i class="fa-solid fa-chart-line"></i><small>Statistiques</small></a></li>
                    <li><a href="Message.php"><i class="fa-solid fa-envelope"></i><small>Message</small></a></li>
                    <li><a href="../logout.php"><i class="fa-solid fa-power-off"></i><small>Déconnexion</small></a></li>
                </ul>
            </div>
    </div>
</div>

<!-- 🧾 MAIN CONTENT -->
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
            <h1>👥 Gestion des Utilisateurs</h1>
            <small>Accueil / Utilisateurs</small>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="records table-responsive">
            <input type="text" id="recherche" class="form-control mb-3" placeholder="🔍 Rechercher nom, prénom ou email...">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Changer Rôle</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody id="resultats-utilisateurs">
                    <?php foreach ($utilisateurs as $u): ?>
                        <tr>
                            <td><?= $u['utilisateur_id'] ?></td>
                            <td><?= htmlspecialchars($u['nom']) ?></td>
                            <td><?= htmlspecialchars($u['prenom']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : 'primary' ?>"><?= $u['role'] ?></span></td>

                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="action" value="changer_role">
                                    <input type="hidden" name="utilisateur_id" value="<?= $u['utilisateur_id'] ?>">
                                    <select name="nouveau_role" class="form-select form-select-sm">
                                        <option value="utilisateur" <?= $u['role'] === 'utilisateur' ? 'selected' : '' ?>>utilisateur</option>
                                        <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>admin</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-success" type="submit">Valider</button>
                                </form>
                            </td>

                            <td>
                                <?php if ($u['utilisateur_id'] != $_SESSION['utilisateur_id']): ?>
                                    <form method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="utilisateur_id" value="<?= $u['utilisateur_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Vous</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- ✅ Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('recherche').addEventListener('keyup', function () {
    const q = this.value;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../PHP/recherche_utilisateurs.php?q=' + encodeURIComponent(q), true);
    xhr.onload = function () {
        document.getElementById('resultats-utilisateurs').innerHTML = this.responseText;
    };
    xhr.send();
});
</script>

</body>
</html>