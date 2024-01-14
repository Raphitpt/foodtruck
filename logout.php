<?php
// Démarrez la session
session_start();
require 'bootstrap.php';
// Détruisez toutes les variables de session
$_SESSION = array();

// Si vous utilisez des cookies de session, détruisez-les également
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Détruisez la session
session_destroy();

// Redirigez l'utilisateur vers la page de connexion (ou une autre page de votre choix)
header("Location: accueil.php");
exit();
