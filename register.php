<?php require_once 'includes/init.php'; ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="CSS/login.css">
</head>
<body>

<div class="form-container">
    <h2>Inscription</h2>

    <!-- Affichage des erreurs -->
    <?php if(isset($_SESSION['error'])): ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <!-- Affichage du succès -->
    <?php if(isset($_SESSION['success'])): ?>
        <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form method="POST" action="BD\register_process.php">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
</div>

</body>
</html>