<?php require_once 'includes/init.php';
require 'BD/connect.php';

// Récupérer dynamiquement les types de signalement
$stmt = $pdo->query("SELECT nom FROM type_signalement");
$types = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler un Problème - InfraWatch</title>
    <link rel="stylesheet" href="CSS/signaler.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="notification" id="notif"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <?php include 'includes/nav.php'; ?>

    <main class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="form-container">
            <h1 class="text-center"><i class="fas fa-exclamation-triangle"></i> Signaler un Problème</h1>

            <form id="signalementForm" action="BD/signale_process.php" method="POST" enctype="multipart/form-data">

                <div class="mb-4">
                    <label for="description" class="form-label">Description du problème :</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="date_signalement" class="form-label">Date de signalement :</label>
                    <input type="date" id="date_signalement" name="date_signalement" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="mb-4">
                    <label for="type_signalement" class="form-label">Type de signalement :</label>
                    <select id="type_signalement" name="type_signalement" class="form-select" required>
                        <option value=""> Sélectionner un type </option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="lieu" class="form-label">Lieu :</label>
                    <div class="input-group">
                        <input type="text" id="lieu" name="lieu" class="form-control" placeholder="Ex: Masikita 106, ngaliema" required>
                        <button type="button" id="btn-localiser" class="btn btn-outline-secondary" title="Utiliser ma position">
                            <i class="fa-solid fa-location-crosshairs"></i>
                        </button>
                    </div>
                    <div id="localisation-info" class="form-text text-muted"></div>
                </div>

                <div class="mb-4">
                    <label for="photo" class="form-label">Ajouter une photo :</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                </div>

            </form>
        </div>
    </main>

    <div class="footer">
        <p>&copy; 2025 InfraWatch. Tous droits réservés.</p>
    </div>

    <script>
        window.onload = function () {
            const notif = document.getElementById("notif");
            if (notif) notif.style.display = "block";
        };
    </script>
    <script src="JS/carte_signal.js"></script>
</body>
</html>