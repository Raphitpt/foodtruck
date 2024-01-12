<?php
require './bootstrap.php';
header('Content-Type: application/json');
$inputJSON = file_get_contents('php://input');
file_put_contents('log.txt', $inputJSON); // Ajout de cette ligne pour enregistrer le contenu dans un fichier log.txt

$input = json_decode($inputJSON, true);
print_r($input);
if ($input && isset($input['panier'])) {
    $panier = $input['panier'];

    if (!empty($panier)) {
        
        // Met ici la requete sql pour ajouter en base de donnée, $panier doit avoir json_encode dans la requete sql
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Le panier est vide.'], 400);
    }
} else {
    echo json_encode(['error' => 'Aucun panier envoyé.'], 400);
}
