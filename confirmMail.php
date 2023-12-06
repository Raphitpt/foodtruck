<?php
require './bootstrap.php';

session_start();

// si c'est GET avec token et mail
if (isset($_GET['token']) && isset($_GET['email'])) {
    // on récupère le token et le mail
    $token = $_GET['token'];
    $email = $_GET['email'];

    // on vérifie que le token et le mail correspondent
    $sql = "SELECT * FROM users WHERE mailverif = :token AND email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['token' => $token, 'email' => $email]);
    $user = $stmt->fetch();

    // si on a un utilisateur
    if ($user) {
        // on met à jour le mailverif
        $sql = "UPDATE users SET mailverif = NULL, active = 1 WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['id_user' => $user['id_user']]);
        $success = 'Votre compte est maintenant activé !';
        header('Location: index.php');
    } else {
        $error = 'Le lien de confirmation est invalide';
    }
} else {
    $error = 'Le lien de confirmation est invalide';
}