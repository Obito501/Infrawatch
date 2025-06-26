<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../BD/connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("Accès refusé.");
}

$term = $_GET['q'] ?? '';
$term = "%$term%";

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? ORDER BY nom");
$stmt->execute([$term, $term, $term]);
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
    <tbody>
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
