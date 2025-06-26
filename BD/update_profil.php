<?php
require_once '../includes/init.php';
require_once '../BD/connect.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['utilisateur_id'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (empty($new) || empty($confirm) || $new !== $confirm) {
    $_SESSION['message'] = "Les mots de passe ne correspondent pas.";
    $_SESSION['type'] = "error";
    header("Location: ../Dashboard/profil.php");
    exit();
}

$stmt = $pdo->prepare("SELECT password FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user || !password_verify($current, $user['password'])) {
    $_SESSION['message'] = "Mot de passe actuel incorrect.";
    $_SESSION['type'] = "error";
    header("Location: ../Dashboard/profil.php");
    exit();
}

// Update mot de passe
$new_hashed = password_hash($new, PASSWORD_DEFAULT);
$update = $pdo->prepare("UPDATE utilisateur SET password = ? WHERE utilisateur_id = ?");
$update->execute([$new_hashed, $id]);

$_SESSION['message'] = "Mot de passe mis à jour avec succès.";
$_SESSION['type'] = "success";
header("Location: ../Dashboard/profil.php");
exit();
