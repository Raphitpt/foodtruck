<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

echo head('Suppresions des messages');

$messages = "SELECT * FROM messages";
$messages = $dbh->query($messages);
$messages = $messages->fetchAll();

$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();


if (isset($_GET['id_message'])) {
    $id_message = (int)$_GET['id_message'];

    $sql = 'DELETE FROM messages WHERE id_message = :id_message';
    // exécuter
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':id_message' => $id_message,
    ]);
}
// redirection vers la page de listing
header('Location: messageUtilisateurs.php');

?>

