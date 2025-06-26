<?php
 require_once "includes/init.php"
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title><link rel="stylesheet" href="CSS/login.css">
</head>
<body>

<div class="form-container">
    <h2>Connexion</h2>

    <?php if(isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="error">
            <ul>
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <p class="success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form method="POST" action="BD/login_process.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>

    <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
</div>

</body>
</html>