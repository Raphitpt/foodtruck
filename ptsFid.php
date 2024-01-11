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

// Récupérer les détails de la commande
$commande = "SELECT * FROM commandes WHERE id_commande = $id_commande";
$commande = $dbh->query($commande);
$commande = $commande->fetch();

// Vérifier si la commande existe
if ($commande) {
    // Mettre à jour le statut de la commande à "Récupérée"
    $updateStatut = "UPDATE commandes SET statut = 'Récupérée' WHERE id_commande = $id_commande";
    $dbh->exec($updateStatut);

    // Calculer la valeur pour pts_fidelite (1/10 du prix total de la commande)
    $pts_fidelite = $commande['total'] / 10;

    // Mettre à jour le champ pts_fidelite de la table users
    $updatePtsFidelite = "UPDATE users SET pts_fidelite = :pts_fidelite WHERE id_user = :id_user";

    $statement = $dbh->prepare($updatePtsFidelite);
    $statement->bindParam(':pts_fidelite', $pts_fidelite, PDO::PARAM_INT);
    $statement->bindParam(':id_user', $commande['id_user'], PDO::PARAM_INT);

    $success = $statement->execute();

    if ($success) {
        header('Location: orderCheck.php');
    } else {
        echo "Une erreur s'est produite lors de la mise à jour des points de fidélité.";
    }

} else {
    echo "La commande n'existe pas.";
    // Gérer le cas où la commande n'existe pas, peut-être rediriger l'utilisateur ou afficher un message approprié.
}

?>
