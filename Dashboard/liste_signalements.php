<?php
require_once '../includes/init.php';
require_once '../BD/connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des Signalements</title>
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
                    <li><a href="#" class="active"><i class="fa-solid fa-list-check"></i><small>Liste des Signalements</small></a></li>
                    <li><a href="gestion_utilisateurs.php"><i class="fa-solid fa-users"></i><small>Gestion des Utilisateurs</small></a></li>
                    <li><a href="statistiques.php"><i class="fa-solid fa-chart-line"></i><small>Statistiques</small></a></li>
                    <li><a href="Message.php"><i class="fa-solid fa-envelope"></i><small>Message</small></a></li>
                    <li><a href="../logout.php"><i class="fa-solid fa-power-off"></i><small>Déconnexion</small></a></li>
                </ul>
            </div>
    </div>
</div>

<div class="main-content">
  <header> ... </header>

  <main>
    <div class="page-header">
      <h1>📋 Liste des Signalements</h1>
      <small>Accueil / Signalements</small>
    </div>

    <input type="text" id="recherche" class="form-control mb-3" placeholder="🔍 Rechercher par lieu, statut ou type...">

    <div class="records table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Lieu</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Type</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="resultats-signalements">
          <?php
          $stmt = $pdo->query("
            SELECT s.signalement_id, s.lieu, s.date_signalement, s.etat, t.nom AS type
            FROM signalement s
            LEFT JOIN type_signalement t ON s.type_signalement_id = t.type_signalement_id
            ORDER BY s.date_signalement DESC
          ");
          $signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach ($signalements as $s):
              $etatClass = match($s['etat']) {
                  'en attente' => 'new',
                  'en cours' => 'in-progress',
                  'terminé' => 'resolved',
                  default => ''
              };
          ?>
          <tr>
            <td>#<?= $s['signalement_id'] ?></td>
            <td><?= htmlspecialchars($s['lieu']) ?></td>
            <td><?= date('d/m/Y', strtotime($s['date_signalement'])) ?></td>
            <td><span class="status <?= $etatClass ?>"><?= ucfirst($s['etat']) ?></span></td>
            <td><?= htmlspecialchars($s['type']) ?></td>
            <td>
              <a href="detail-signalement.php?id=<?= $s['signalement_id'] ?>" class="btn btn-sm btn-outline-primary">Détails</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script>
document.getElementById('recherche').addEventListener('keyup', function () {
    const q = this.value;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../AJAX/recherche_signalements.php?q=' + encodeURIComponent(q), true);
    xhr.onload = function () {
        document.getElementById('resultats-signalements').innerHTML = this.responseText;
    };
    xhr.send();
});
</script>

</body>
</html>
