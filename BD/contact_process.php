<?php
require_once '../includes/init.php';
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom     = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($nom) || empty($email) || empty($message)) {
        $_SESSION['message'] = "Tous les champs sont obligatoires.";
        header("Location: ../contact.php");
        exit();
    }

    // 1️⃣ Enregistrement du message dans contact_messages
    $stmt = $pdo->prepare("INSERT INTO contact_messages (nom, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $message]);

    // 2️⃣ Notification pour l'admin
    $titre   = "Nouveau message de contact";
    $contenu = "$nom a envoyé un message.";
    $date    = date('Y-m-d H:i:s');
    $statut  = "non lu";
    $type    = "contact";
    $lien    = "admin/messages_contact.php";

    $notif = $pdo->prepare("INSERT INTO notification (titre, contenu, date_notification, statut, type, lien) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $notif->execute([$titre, $contenu, $date, $statut, $type, $lien]);

    $_SESSION['message'] = "✅ Merci pour votre message !";
    header("Location: ../contact.php");
    exit();
}
