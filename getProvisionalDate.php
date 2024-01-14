<?php
require './bootstrap.php';
session_start();

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if ($input && isset($input['date_retrait'])) {
    $dateRetrait = calculatePickupTime($input['date_retrait']);
    echo json_encode(['success' => true, 'provisional_date' => $dateRetrait]);
} else {
    echo json_encode(['error' => 'Paramètres manquants.'], 400);
}

function calculatePickupTime($requestedTime) {
    global $dbh;
    $pickupTime = strtotime($requestedTime);

    // Boucler jusqu'à trouver un créneau disponible
    while (countScheduledOrders($requestedTime) >= 8) {
        $pickupTime += 10 * 60; // Ajouter 10 minutes
        $requestedTime = date("Y-m-d H:i:s", $pickupTime);
    }

    return $requestedTime;
}

function countScheduledOrders($requestedTime) {
    global $dbh;

    // Récupérer le nombre de fouées prévues à l'heure demandée depuis la base de données
    $sql = "SELECT SUM(fouees) as total_fouees FROM `four` WHERE `date` = :requested_date AND `heure` = :requested_hour";
    $query = $dbh->prepare($sql);
    $query->bindValue(':requested_date', date('Y-m-d', strtotime($requestedTime)), PDO::PARAM_STR);
    $query->bindValue(':requested_hour', date('H:i:s', strtotime($requestedTime)), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['total_fouees'];
}
?>
