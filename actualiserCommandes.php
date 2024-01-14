<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

// Effectuer la requête SQL pour récupérer les données actualisées
$hist = $dbh->query("SELECT * FROM commandes INNER JOIN users ON commandes.id_user = users.id_user WHERE statut = 'En cours'");
$hist = $hist->fetchAll();

// Générer le nouveau contenu du tableau
foreach ($hist as $histo) {
    echo "<tr>";
    echo "<td>{$histo['id_commande']}</td>";
    $details = json_decode($histo['detail_commande'], true);
    echo "<td><ul>";
    foreach ($details as $detail) {
        echo "<li>{$detail['nom']} x {$detail['quantite']}</li>";
    }
    echo "</ul></td>";
    echo "<td>{$histo['nom']} {$histo['prenom']}</td>";
    echo "<td>{$histo['date_commande']}</td>";
    echo "<td>{$histo['date_retrait']}</td>";
    echo "<td>{$histo['statut']}</td>";
    echo "<td>{$histo['commentaire']}</td>";
    echo "<td>{$histo['total']}€</td>";
    echo "<td><a href='./addPtsFid.php?id_commande={$histo['id_commande']}'>Valider</a></td>";
    echo "<td><a href='./suppCommande.php?id_commande={$histo['id_commande']}'>Supprimer</a></td>";
    echo "</tr>";
}
