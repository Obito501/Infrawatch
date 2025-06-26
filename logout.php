<?php
session_start();

// Suppression de toutes les variables de session
$_SESSION = [];

// Destruction de la session
session_destroy();

// Redirection vers la page de login ou d'accueil
header("Location: index.php");
exit();
?>
