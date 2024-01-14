<?php
require './bootstrap.php';
session_start();

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if ($input && isset($input['date']) && isset($input['heure'])) {
    $dateRetrait = $input['date'] . ' ' . $input['heure'];
    
    // Votre logique de vérification ici
    $placesDisponibles = verifierPlacesDisponibles($dateRetrait);

    if ($placesDisponibles) {
        echo json_encode(['success' => true, 'provisional_date' => $dateRetrait]);
    } else {
        echo json_encode(['error' => 'La capacité maximale est atteinte à cette heure. Choisissez une autre heure.']);
    }
} else {
    echo json_encode(['error' => 'Paramètres manquants.'], 400);
}

function verifierPlacesDisponibles($requestedTime) {
    global $dbh;

    // Votre logique pour vérifier la capacité du four à cette heure
    // Effectuez la requête à la base de données et renvoyez true ou false en conséquence
    
    // Exemple imaginaire (vous devez ajuster ceci en fonction de votre schéma de base de données)
    $sql = "SELECT COUNT(*) as total_reservations FROM `four` WHERE `date` = :requested_date AND `heure` = :requested_hour";
    $query = $dbh->prepare($sql);
    $query->bindValue(':requested_date', date('Y-m-d', strtotime($requestedTime)), PDO::PARAM_STR);
    $query->bindValue(':requested_hour', date('H:i:s', strtotime($requestedTime)), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    $capaciteMax = 8; // Vous devrez ajuster ceci en fonction de votre capacité réelle

    return ($result['total_reservations'] < $capaciteMax);
}
?>
