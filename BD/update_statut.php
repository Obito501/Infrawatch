<?php
require_once __DIR__ . '../includes/init.php';
require_once __DIR__ . '/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['signalement_id'] ?? null;
    $etat = $_POST['nouvel_etat'] ?? null;

    $validStates = ['en attente', 'en cours', 'terminé'];

    if (!$id || !in_array($etat, $validStates)) {
        $_SESSION['message'] = "Requête invalide.";
        $_SESSION['message_type'] = "danger";
        header("Location: ../dashadmin.php");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE signalement SET etat = ? WHERE signalement_id = ?");
    $stmt->execute([$etat, $id]);

    $_SESSION['message'] = "✅ Statut mis à jour avec succès.";
    $_SESSION['message_type'] = "success";
    header("Location: ../detail-signalement.php?id=" . $id);
    exit();
}
