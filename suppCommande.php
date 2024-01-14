<?php

require 'bootstrap.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

$id_commande = isset($_GET['id_commande']) ? $_GET['id_commande'] : null;

// Vérifier si la commande existe avant de la supprimer
$checkCommande = "SELECT * FROM commandes WHERE id_commande = :id_commande";
$statementCheck = $dbh->prepare($checkCommande);
$statementCheck->bindParam(':id_commande', $id_commande, PDO::PARAM_INT);
$statementCheck->execute();
$commande = $statementCheck->fetch();

if ($commande) {
    // Supprimer la commande
    $deleteCommande = "DELETE FROM commandes WHERE id_commande = :id_commande";
    $statementDelete = $dbh->prepare($deleteCommande);
    $statementDelete->bindParam(':id_commande', $id_commande, PDO::PARAM_INT);
    $success = $statementDelete->execute();

    if ($success) {
        header('Location: orderCheck.php');
    } else {
        echo "Une erreur s'est produite lors de la suppression de la commande.";
    }
} else {
    echo "La commande n'existe pas.";
    // Gérer le cas où la commande n'existe pas, peut-être rediriger l'utilisateur ou afficher un message approprié.
}

?>