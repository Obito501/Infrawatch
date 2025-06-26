<?php
require_once '../includes/init.php';
require_once '../BD/connect.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("ID invalide.");
}

// Récupérer le signalement avec l'utilisateur lié
$stmt = $pdo->prepare("
    SELECT s.*, u.nom AS user_nom, u.prenom AS user_prenom
    FROM signalement s
    LEFT JOIN utilisateur u ON s.utilisateur_id = u.utilisateur_id
    WHERE s.signalement_id = ?
");
$stmt->execute([$id]);
$signalement = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$signalement) {
    die("Signalement introuvable.");
}

// Récupérer le type de signalement
$typeStmt = $pdo->prepare("SELECT nom FROM type_signalement WHERE type_signalement_id = ?");
$typeStmt->execute([$signalement['type_signalement_id']]);
$typeSignalement = $typeStmt->fetchColumn() ?? 'Inconnu';

// Gérer classes CSS du statut
$etatClass = match ($signalement['etat']) {
    'en attente' => 'new',
    'en cours' => 'in-progress',
    'terminé' => 'resolved',
    default => 'secondary'
};

// Message flash
$message = $_SESSION['message'] ?? null;
$type = $_SESSION['message_type'] ?? 'info';
unset($_SESSION['message'], $_SESSION['message_type']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails Signalement</title>
    <link rel="stylesheet" href="../CSS/detail-signalement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<div class="main-content p-4">

    <h2>Détails du Signalement</h2>
    <small><a href="../dashadmin.php"><i class="fa-solid fa-arrow-left"></i> Retour au Dashboard</a></small>

    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <table class="mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Lieu</th>
                <th>Description</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Déclaré par</th>
                <th>Photo</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#<?= htmlspecialchars($signalement['signalement_id']) ?></td>
                <td><?= htmlspecialchars($signalement['lieu']) ?></td>
                <td><?= htmlspecialchars($signalement['description']) ?></td>
                <td><?= date('d M Y', strtotime($signalement['date_signalement'])) ?></td>
                <td><span class="status <?= $etatClass ?>"><?= ucfirst($signalement['etat']) ?></span></td>
                <td><?= htmlspecialchars($signalement['user_prenom'] . ' ' . $signalement['user_nom']) ?></td>
                <td>
                    <?php if (!empty($signalement['photo'])): ?>
                        <a href="<?= htmlspecialchars($signalement['photo']) ?>" target="_blank">Voir</a>
                    <?php else: ?>
                        Aucune
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($typeSignalement) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- FORMULAIRE DE CHANGEMENT DE STATUT -->
    <div class="mt-5" style="max-width: 300px;">
        <h4>Modifier le statut :</h4>
        <form action="BD/update_statut.php" method="POST">
            <input type="hidden" name="signalement_id" value="<?= $signalement['signalement_id'] ?>">
            <select name="nouvel_etat" class="form-select mb-2" required>
                <option disabled selected>-- Choisir un état --</option>
                <option value="en attente" <?= $signalement['etat'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
                <option value="en cours" <?= $signalement['etat'] === 'en cours' ? 'selected' : '' ?>>En cours</option>
                <option value="terminé" <?= $signalement['etat'] === 'terminé' ? 'selected' : '' ?>>Terminé</option>
            </select>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>