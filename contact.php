<?php if (isset($_SESSION['message'])): ?>
  <div class="alert alert-success text-center">
    <?= htmlspecialchars($_SESSION['message']) ?>
  </div>
  <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Gestion des Infrastructures</title>
    <link rel="stylesheet" href="CSS\contact.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="preload" as="image" href="img/photo-1522071820081-009f0129c71c.jpg" fetchpriority="high">    
</head>
<body>
<?php include 'includes\nav.php'; ?>
<header class="contact-hero">
    <h1>Nous contacter</h1>
    <p>Une question ? Une remarque ? Parlons-en !</p>
</header>

<section class="contact-section">
    <div class="contact-container">

      <!-- Formulaire -->
      <div class="form-side">
        <h2>Écrivez-nous</h2>
        <form action="BD/contact_process.php" method="post">
          <input type="text" name="name" placeholder="Nom complet" required>
          <input type="email" name="email" placeholder="Adresse email" required>
          <textarea name="message" rows="5" placeholder="Votre message..." required></textarea>
          <button type="submit">Envoyer</button>
        </form>
      </div>

      <!-- Infos de contact -->
      <div class="info-side">
        <h2>Coordonnées</h2>
        <p><strong>Email :</strong> contact@Infrawatchorg</p>
        <p><strong>Téléphone :</strong> +243 840000000</p>
        <p><strong>Adresse :</strong> 123 Avenue de l'Éducation, Kinshasa, RDC</p>
        <h3>Suivez-nous</h3>
        <ul>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Instagram</a></li>
          <li><a href="#">LinkedIn</a></li>
        </ul>
      </div>

    </div>
</section>
</body>
</html>