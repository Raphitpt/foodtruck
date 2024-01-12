<?php
header('Content-Type: application/json');

// Récupérez les données JSON de la requête POST
$inputJSON = file_get_contents('php://input');
file_put_contents('log.txt', $inputJSON); // Enregistrez le contenu dans un fichier log.txt

// Décoder les données JSON
$data = json_decode($inputJSON, true);

// Vérifiez si les données du panier sont présentes
if ($data && isset($data['panier'])) {
    $panier = $data['panier'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Aucun panier envoyé.'], 400);
}

// Décommentez ces lignes si vous avez besoin d'utiliser $requestData au lieu de $data
// $requestData = json_decode(file_get_contents('php://input'), true);
// if (isset($requestData['panier'])) {
//     $panier = $requestData['panier'];
//     echo json_encode(['success' => true]);
// } else {
//     echo json_encode(['error' => 'Aucun panier envoyé.'], 400);
// }

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>