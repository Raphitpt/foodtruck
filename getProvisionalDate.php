<?php
require './bootstrap.php';
session_start();

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if ($input && isset($input['date_retrait']) && isset($input['quantite'])) {
    if ($input['quantite']> 8){
        echo json_encode(['error' => 'La quantité maximale pour un créneau est de 8 fouées.']);
        exit();
    }
    $dateRetrait = calculatePickupTime($input['date_retrait'], $input['quantite']);
    echo json_encode(['success' => true, 'provisional_date' => $dateRetrait]);
} else {
    echo json_encode(['error' => 'Paramètres manquants.'], 400);
}

function calculatePickupTime($requestedTime, $numberOfFouees) {
    global $dbh;

    $pickupTime = strtotime($requestedTime);

    // Boucler jusqu'à trouver un créneau disponible
    while (true) {
        $totalAvailableFouees = countAvailableFouees($pickupTime);

        if ($totalAvailableFouees >= $numberOfFouees) {
            // Créneau trouvé, retourner le temps demandé
            return date("Y-m-d H:i:s", $pickupTime);
        }

        $pickupTime += 10 * 60; // Ajouter 10 minutes
    }
}

function countAvailableFouees($pickupTime) {
    global $dbh;

    // Récupérer le nombre total de fouées disponibles à l'heure demandée
    $sql = "SELECT SUM(fouees) as total_fouees FROM `four` WHERE `date` = :requested_date AND `heure` = :requested_hour";
    $query = $dbh->prepare($sql);
    $query->bindValue(':requested_date', date('Y-m-d', $pickupTime), PDO::PARAM_STR);
    $query->bindValue(':requested_hour', date('H:i:s', $pickupTime), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return 8 - $result['total_fouees'];
}

function findFirstAvailableTime($startTime, $requestedQuantity) {
    global $dbh;

    // Tant qu'il reste des fouées à répartir
    while ($requestedQuantity > 0) {
        // Calculer le nombre de fouées que l'on peut ajouter à ce tour
        $availableSlots = 8 - countScheduledOrders(date('Y-m-d H:i:s', (int)$startTime));
        $foueesToAdd = min($availableSlots, $requestedQuantity);

        // Mettre à jour le temps pour la prochaine répartition
        $startTime += $foueesToAdd * 10 * 60;

        $requestedQuantity -= $foueesToAdd;
    }

    return $startTime;
}

function countScheduledOrders($requestedTime) {
    global $dbh;

    // Récupérer le nombre de fouées prévues à l'heure demandée depuis la base de données
    $sql = "SELECT SUM(fouees) as total_fouees FROM `four` WHERE `date` = :requested_date AND `heure` = :requested_hour";
    $query = $dbh->prepare($sql);
    $query->bindValue(':requested_date', date('Y-m-d', (int)$requestedTime), PDO::PARAM_STR);
    $query->bindValue(':requested_hour', date('H:i:s', (int)$requestedTime), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['total_fouees'];
}
