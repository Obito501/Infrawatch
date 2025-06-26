<?php
session_start();
session_destroy();
header("Location: login.php"); // Redirection vers la page de connexion après déconnexion
exit();
?>
