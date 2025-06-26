<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <?php include 'includes\nav.php'; ?>
    
    <header class="header">
        <div class="header-content">
            <h1>Signalez et suivez l'état des infrastructures</h1>
            <p>Aidez votre ville à entretenir ses routes et infrastructures en signalant les problèmes en un clic.</p>
            <a href="signaler.php" class="header-button">Signaler un problème</a>
        </div>
    </header>

  <section class="about">
    <div class="text">
      <h1>c'est quoi infrawatch?</h1>
      <p>Dans de nombreuses villes, les infrastructures publiques telles que les routes, les ponts, les feux tricolores, ou encore les systèmes d’égout subissent des dégradations avec le temps, souvent sans être signalées à temps aux autorités ou services techniques.</p>
      <p>C’est dans cette optique que l’application web InfrWatch a été développée. Elle propose une plateforme simple, intuitive et accessible à tous les citoyens, leur permettant de signaler en quelques clics une infrastructure endommagée dans leur quartier.</p>
      <p>En facilitant la communication entre la population et les autorités locales, InfrWatch vise à instaurer un système participatif et transparent de gestion des infrastructures urbaines. Elle s’inscrit dans une démarche de smart city, où les technologies numériques sont mises au service du bien commun et de la gouvernance locale.</p>
    </div>
    <div class="image">
      <img src="img\paysage-urbain-bucarest-route-voitures-mouvement-plusieurs-batiments-residentiels-ciel-clair-vue-depuis-drone-roumanie_1268-16363.avif" alt="">
    </div>
  </section>
  
  <section class="how-to-use" aria-labelledby="how-to-use-title">
  <div class="container">
    <h2 id="how-to-use-title">🚀 Comment utiliser l'application ?</h2>
    <p class="intro">
      Notre application est conçue pour être simple, intuitive et accessible à tous.<br>
      Voici les étapes pour signaler une infrastructure endommagée en quelques clics.
    </p>

    <ol class="steps" role="list">
      <li class="step">
        <div class="icon" aria-hidden="true">🔐</div>
        <h3>1. Connexion</h3>
        <p>Créez un compte ou connectez-vous à votre espace personnel pour accéder à toutes les fonctionnalités.</p>
      </li>
      <li class="step">
        <div class="icon" aria-hidden="true">📷</div>
        <h3>2. Signalement</h3>
        <p>Remplissez un formulaire avec la localisation, une description et une photo de l’infrastructure concernée.</p>
      </li>
      <li class="step">
        <div class="icon" aria-hidden="true">📊</div>
        <h3>3. Suivi</h3>
        <p>Suivez l’évolution de votre signalement via votre tableau de bord et recevez des notifications.</p>
      </li>
    </ol>
  </div>
</section>


  <?php include 'includes\footer.php'; ?>

    
                 

</body>
</html>