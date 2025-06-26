<?php require_once '../includes/init.php'; ?>
<?php
require 'connect.php';  // Charge la connexion

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Vérification des mots de passe
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../register.php");
        exit();
    }

    // Vérification si l'email est déjà utilisé
    try {
        $check = $pdo->prepare("SELECT email FROM utilisateur WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header("Location: ../register.php");
            exit();
        }

        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertion dans la base de données
        $sql = "INSERT INTO utilisateur (nom, prenom, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $hashed_password]);

        $_SESSION['success'] = "Inscription réussie ! Vous pouvez vous connecter.";
        header("Location: ../dashboard.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur SQL : " . $e->getMessage();
        header("Location: ../register.php");
        exit();
    }
}
?>

