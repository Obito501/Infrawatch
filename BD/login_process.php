<?php 
require_once '../includes/init.php';
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors'][] = "Adresse email invalide.";
        header("Location: ../login.php");
        exit();
    }

    try {
        // Récupération du rôle en plus
        $stmt = $pdo->prepare("SELECT utilisateur_id, email, password, nom, prenom, role FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            // Session setup
            $_SESSION['utilisateur_id'] = $user['utilisateur_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['success'] = "Connexion réussie !";

            // Redirection selon le rôle
            if ($user['role'] === 'admin') {
                header("Location: ../dashadmin.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit();
        } else {
            $_SESSION['errors'][] = "Email ou mot de passe incorrect.";
            header("Location: ../login.php");
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['errors'][] = "Erreur interne. Veuillez réessayer plus tard.";
        error_log("Erreur SQL : " . $e->getMessage());
        header("Location: ../login.php");
        exit();
    }
}
?>