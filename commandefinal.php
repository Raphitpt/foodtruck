<?php
require './bootstrap.php';
session_start();
header('Content-Type: application/json');
$inputJSON = file_get_contents('php://input');

$input = json_decode($inputJSON, true);

print_r($input);
if ($input && isset($input['panier'])) {
    $panier = $input['panier'];

    if (!empty($panier)) {
        $panier = json_encode($panier);
        $sql = "INSERT INTO `commandes` (detail_commande, id_user, date_retrait, commentaire, total) VALUES (:panier, :id_user, :date_retrait, :commentaire, :total)";
        $query = $dbh->prepare($sql);
        $query->bindValue(':panier', $panier, PDO::PARAM_STR);
        $query->bindValue(':id_user', $input['id_user'], PDO::PARAM_INT);
        $query->bindValue(':commentaire', $input['commentaire'], PDO::PARAM_STR);
        $query->bindValue(':date_retrait', $input['date_retrait'], PDO::PARAM_STR);
        $query->bindValue(':total', $input['prix'], PDO::PARAM_STR);
        $query->execute();
        // Met ici la requete sql pour ajouter en base de donnée, $panier doit avoir json_encode dans la requete sql
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Le panier est vide.'], 400);
    }
} else {
    echo json_encode(['error' => 'Aucun panier envoyé.'], 400);
}
