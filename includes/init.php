<?php
// ⚙️ Configuration des paramètres de session avant son démarrage
ini_set('session.gc_maxlifetime', 900); // Session inactive auto-kill après 900s (15 min)
ini_set('session.cookie_lifetime', 0);   // Le cookie expire à la fermeture du navigateur

// 🚀 Lancer la session si aucune n'est active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🕒 Déconnexion après 15 minutes d'inactivité
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
    session_unset();     // Supprimer les variables
    session_destroy();   // Détruire la session
    header("Location: index.php");
    exit();
}
$_SESSION['last_activity'] = time(); // ⏱️ Mise à jour du timer

// 📅 Timezone par défaut
date_default_timezone_set('Europe/Paris');

// 🛡️ Sécurité anti-XSS
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}