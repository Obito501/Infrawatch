<?php require_once 'init.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">InfraWatch</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Liens à gauche -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="../signaler.php">Signaler</a></li>
                <li class="nav-item"><a class="nav-link" href="../dashboard.php">Suivi</a></li>
                <li class="nav-item"><a class="nav-link" href="profil.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="../contact.php">Contact</a></li>
            </ul>


            <!-- Connexion / Déconnexion à droite -->
            <div class="d-flex ms-3">
                <?php if (!isset($_SESSION['utilisateur_id'])): ?>
                    <a href="login.php">
                        <button class="btn btn-outline-light">Se connecter</button>
                    </a>
                <?php else: ?>
                    <a href="../logout.php">
                        <button class="btn btn-success">
                            <?= htmlspecialchars($_SESSION['prenom'] ?? 'Mon compte') ?> - Déconnexion
                        </button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>      
</body>
</html>