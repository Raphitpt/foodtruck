<?php
// Assurez-vous que vous avez démarré la session si nécessaire
session_start();

// Récupérez les données du panier
$rawData = file_get_contents("php://input");
$requestData = json_decode($rawData, true);


// Vérifiez si les données du panier sont présentes
if (isset($requestData['panier'])) {
    $panier = $requestData['panier'];

    // Faites ce que vous voulez avec le panier ici
    // Par exemple, vous pourriez stocker les données dans la session PHP
    $_SESSION['panier'] = $panier;

    // Répondez avec un message de succès ou autre chose si nécessaire
    $response = ['message' => 'Le panier a été reçu avec succès !'];
    echo json_encode($response);
} else {
    // Si les données du panier ne sont pas présentes, répondez avec une erreur
    http_response_code(400); // Code HTTP 400 Bad Request
    $response = ['error' => 'Erreur : Aucun panier envoyé.'];
    echo json_encode($response);
}
?>