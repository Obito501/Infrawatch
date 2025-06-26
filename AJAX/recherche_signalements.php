<?php
require_once '../BD/connect.php';

$q = $_GET['q'] ?? '';

$sql = "
    SELECT s.signalement_id, s.lieu, s.date_signalement, s.etat, t.nom AS type
    FROM signalement s
    LEFT JOIN type_signalement t ON s.type_signalement_id = t.type_signalement_id
    WHERE s.lieu LIKE :q OR s.etat LIKE :q OR t.nom LIKE :q
    ORDER BY s.date_signalement DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['q' => "%$q%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($results)) {
    echo '<tr><td colspan="6" class="text-center">Aucun signalement trouvé.</td></tr>';
    exit;
}

foreach ($results as $s) {
    $etatClass = match($s['etat']) {
        'en attente' => 'new',
        'en cours' => 'in-progress',
        'terminé' => 'resolved',
        default => ''
    };

    echo "<tr>
        <td>#{$s['signalement_id']}</td>
        <td>" . htmlspecialchars($s['lieu']) . "</td>
        <td>" . date('d/m/Y', strtotime($s['date_signalement'])) . "</td>
        <td><span class='status {$etatClass}'>" . ucfirst($s['etat']) . "</span></td>
        <td>" . htmlspecialchars($s['type']) . "</td>
        <td>
            <a href='../detail-signalement.php?id={$s['signalement_id']}' class='btn btn-sm btn-outline-primary'>Détails</a>
        </td>
    </tr>";
}