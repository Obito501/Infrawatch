<?php
header("Content-Type: application/json");
require_once "../config/database.php"; // Connexion à MySQL

if (!isset($_GET["id"])) {
    echo json_encode(["error" => "ID du signalement manquant."]);
    exit;
}

$id = intval($_GET["id"]);
$sql = "SELECT * FROM signalement WHERE signalement_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$signalement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$signalement) {
    echo json_encode(["error" => "Signalement introuvable."]);
    exit;
}

// Retourner les données sous format JSON
echo json_encode($signalement);
?>
