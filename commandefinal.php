<?php
require './bootstrap.php';

$inputJSON = file_get_contents('php://input');
dd($inputJSON);
file_put_contents('log.txt', $inputJSON); // Ajout de cette ligne pour enregistrer le contenu dans un fichier log.txt

$input = json_decode($inputJSON, true);
print_r($input);
if ($input && isset($input['panier'])) {
    $panier = $input['panier'];

    // Vérifiez si les données du panier sont présentes
    if (!empty($panier)) {
        // Faites quelque chose avec le panier, par exemple, enregistrez-le en base de données
        // ...

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Le panier est vide.'], 400);
    }
} else {
    echo json_encode(['error' => 'Aucun panier envoyé.'], 400);
}
