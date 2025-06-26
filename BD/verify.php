<?php
require 'connect.php'; // Connexion à la base de données

if (isset($_GET["token"])) {
    $token = $_GET["token"];

    $stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE verification_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE utilisateur SET is_verified = 1, verification_token = '' WHERE utilisateur_id = ?");
        $update->execute([$user["utilisateur_id"]]);
        echo "Votre compte est activé ! Vous pouvez maintenant vous connecter.";
    } else {
        echo "Token invalide ou déjà utilisé.";
    }
}
?>
