<?php
require_once '../includes/init.php';
require_once '../BD/connect.php';

// 🔐 Vérif session utilisateur
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("ID invalide.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// ⚠️ On récupère uniquement un signalement appartenant à l'utilisateur connecté
$stmt = $pdo->prepare("
    SELECT s.*, ts.nom AS type_nom
    FROM signalement s
    LEFT JOIN type_signalement ts ON s.type_signalement_id = ts.type_signalement_id
    WHERE s.signalement_id = ? AND s.utilisateur_id = ?
");
$stmt->execute([$id, $utilisateur_id]);
$signalement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$signalement) {
    die("Aucun signalement trouvé ou vous n’y avez pas accès.");
}

// CSS du statut
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
    <title>Détails de mon Signalement</title>
    <link rel="stylesheet" href="../CSS/detail-signalement.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="main-content p-4">
    <h2>Détails de mon Signalement</h2>
    <small><a href="../dashboard.php"><i class="fa-solid fa-arrow-left"></i> Retour à mes signalements</a></small>

    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <table class="mt-4 table table-bordered bg-white">
        <tr>
            <th>ID</th>
            <td>#<?= htmlspecialchars($signalement['signalement_id']) ?></td>
        </tr>
        <tr>
            <th>Lieu</th>
            <td><?= htmlspecialchars($signalement['lieu']) ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?= nl2br(htmlspecialchars($signalement['description'])) ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?= date('d M Y', strtotime($signalement['date_signalement'])) ?></td>
        </tr>
        <tr>
            <th>Statut</th>
            <td><span class="status <?= $etatClass ?>"><?= ucfirst($signalement['etat']) ?></span></td>
        </tr>
        <tr>
            <th>Photo</th>
            <td>
                <?php if (!empty($signalement['photo'])): ?>
                    <a href="../uploads/<?= htmlspecialchars($signalement['photo']) ?>" target="_blank">Voir la photo</a>
                <?php else: ?>
                    Aucune photo fournie.
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Type</th>
            <td><?= htmlspecialchars($signalement['type_nom']) ?></td>
        </tr>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
