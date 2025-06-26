  <?php
  require_once '../includes/init.php';
  require_once '../BD/connect.php';

  if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../login.php");
    exit();
  }

  $id = $_SESSION['utilisateur_id'];

  // Récupération des infos
  $stmt = $pdo->prepare("SELECT nom, prenom, email FROM utilisateur WHERE utilisateur_id = ?");
  $stmt->execute([$id]);
  $user = $stmt->fetch();

  if (!$user) {
    die("Utilisateur introuvable.");
  }

  // Compte des signalements
  $countStmt = $pdo->prepare("SELECT COUNT(*) FROM signalement WHERE utilisateur_id = ?");
  $countStmt->execute([$id]);
  $nbSignalements = $countStmt->fetchColumn();
  ?>

  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Utilisateur</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
      body {
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        color: #212529;
        font-family: 'Segoe UI', 'Roboto', sans-serif;
        line-height: 1.6;
      }

      .profile-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
      }

      .profile-card img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 4px solid #007BFF;
        object-fit: cover;
        margin-bottom: 15px;
      }

      .profile-card h2 {
        margin: 10px 0 5px;
        font-size: 22px;
        font-weight: 600;
      }

      .profile-card p {
        margin: 4px 0;
        color: #6c757d;
        font-size: 15px;
      }

      .profile-details {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      }

      .profile-details h3 {
        margin-bottom: 25px;
        font-size: 24px;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 10px;
      }

      .back-dashboard {
        display: inline-block;
        margin: 20px;
        padding: 12px 18px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.2s ease;
      }

      .back-dashboard:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
      }

      .back-dashboard i {
        margin-right: 6px;
      }
    </style>
  </head>
  <nav class="navbar-mobile">
        <?php include '../includes/nav2.php'; ?>
  </nav>
  <body>

    <div class="container py-5">
      <div class="row justify-content-center g-4">

        <!-- Carte Profil -->
        <div class="col-12 col-md-4">
          <div class="profile-card">
            <img src="https://th.bing.com/th?q=Photo+De+Profil+Bonhomme+Gris&w=120&h=120&c=1" alt="Photo de profil">
            <h2><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
            <p><?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Signalements :</strong> <?= $nbSignalements ?></p>
          </div>
        </div>

        <!-- Formulaire -->
        <div class="col-12 col-md-6">
          <div class="profile-details">
            <h3>Modifier mon mot de passe</h3>

            <?php if (isset($_SESSION['message'])): ?>
              <div class="alert alert-<?= $_SESSION['type'] ?? 'success' ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <?php unset($_SESSION['message'], $_SESSION['type']); ?>
            <?php endif; ?>

            <form method="POST" action="../BD/update_profil.php" onsubmit="return checkPassword();">
              <div class="mb-3">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-outline-secondary w-100">Enregistrer les modifications</button>
            </form>
          </div>
        </div>

      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Validation JS -->
    <script>
      function checkPassword() {
        const newPassword = document.getElementById("new_password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (newPassword !== confirmPassword) {
          alert("Les mots de passe ne correspondent pas.");
          return false;
        }
        return true;
      }
    </script>

  </body>

  </html>