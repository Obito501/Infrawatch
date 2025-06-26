<?php
require_once 'includes/init.php'; // adapte le chemin si besoin
require 'BD/connect.php';

echo "<h2>Nettoyage des chemins photo dans la base</h2>";

$sql = "SELECT signalement_id, photo FROM signalement WHERE photo IS NOT NULL AND photo != ''";
$stmt = $pdo->query($sql);
$signalements = $stmt->fetchAll();

$total = 0;
$corriges = 0;

foreach ($signalements as $sig) {
    $total++;
    $id = $sig['signalement_id'];
    $photo = $sig['photo'];

    // Extraire juste le nom du fichier, au cas où 'uploads/' serait déjà présent
    $filename = basename($photo);

    if ($photo !== $filename) {
        // Met à jour la base pour ne garder que le nom du fichier
        $update = $pdo->prepare("UPDATE signalement SET photo = ? WHERE signalement_id = ?");
        $update->execute([$filename, $id]);
        echo "<p style='color:green;'>✅ Signalement ID $id corrigé : <code>$filename</code></p>";
        $corriges++;
    } else {
        echo "<p style='color:gray;'>➖ Signalement ID $id déjà correct.</p>";
    }
}

echo "<hr><strong>Total : $total signalement(s), $corriges corrigé(s).</strong>";
?>

