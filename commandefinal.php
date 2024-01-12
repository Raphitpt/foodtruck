<?php
header('Content-Type: application/json');
$data = ["STATUS" => "OK"];
$data = json_decode(file_get_contents('php://input'), true);
var_dump($data);
if ($data && isset($data['panier'])) {
    $panier = $data['panier'];


// Vérifiez si les données du panier sont présentes
if (isset($requestData['panier'])) {
    $panier = $requestData['panier'];

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Aucun panier envoyé.'], 400);
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$inputJSON = file_get_contents('php://input');
file_put_contents('log.txt', $inputJSON); // Ajout de cette ligne pour enregistrer le contenu dans un fichier log.txt
$input = json_decode($inputJSON, true);
print_r($input);
}
?>