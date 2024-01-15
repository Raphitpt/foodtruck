<?php
require './bootstrap.php';
session_start();

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if ($input && isset($input['panier'], $input['date_retrait'])) {
    $panier = $input['panier'];
    $dateRetrait = $input['date_retrait'];
    $quantitesParFouee = array_reduce($panier, function ($result, $fouee) {
        $quantite = $fouee['quantite'];
        $result += $quantite;
        return $result;
    }, 0);
    // Répartir automatiquement les fouées dans le four
    $foueesReparties = distributeFouees($dateRetrait, $quantitesParFouee);

    // Vérifier la capacité du four avant d'ajouter la commande
    if ($foueesReparties !== false) {
        // Ajouter la nouvelle commande et les fouées dans les tables `commandes` et `four`
        $panier = json_encode($panier);

        // Utilisation de l'instruction ON DUPLICATE KEY UPDATE pour mettre à jour si l'heure existe déjà
        $sql = "INSERT INTO `commandes` (detail_commande, id_user, date_retrait, commentaire, total) 
                VALUES (:panier, :id_user, :date_retrait, :commentaire, :total)
                ON DUPLICATE KEY UPDATE total = :total";

        $query = $dbh->prepare($sql);
        $query->bindValue(':panier', $panier, PDO::PARAM_STR);
        $query->bindValue(':id_user', $input['id_user'], PDO::PARAM_INT);
        $query->bindValue(':commentaire', $input['commentaire'], PDO::PARAM_STR);
        $query->bindValue(':date_retrait', $dateRetrait, PDO::PARAM_STR);
        $query->bindValue(':total', $input['prix'], PDO::PARAM_STR);
        $query->execute();
        $id_commande = $dbh->lastInsertId();
        sendFacture($panier, $input['id_user'], $input['commentaire'], $dateRetrait, $input['prix'], $id_commande);
        echo json_encode(['success' => true, 'new_time' => 'Parfait, nous avons bien enregistré votre comande pour le ' . $dateRetrait]);
    } else {
        echo json_encode(['error' => 'La capacité du four est atteinte à cette heure. Choisissez une autre heure.'], 400);
    }
} else {
    echo json_encode(['error' => 'Paramètres manquants.'], 400);
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

function distributeFouees($requestedTime, $numberOfFouees) {
    global $dbh;

    // Calculer le nombre de fouées que l'on peut ajouter à ce tour
    $availableSlots = 8 - countScheduledOrders($requestedTime);
    $foueesToAdd = min($availableSlots, $numberOfFouees);

    // Vérifier si le four a la capacité nécessaire
    if ($foueesToAdd > 0) {
        // Utilisation de l'instruction ON DUPLICATE KEY UPDATE pour mettre à jour si l'heure existe déjà
        $sql = "INSERT INTO `four` (`date`, `heure`, `fouees`) VALUES (:requested_date, :requested_hour, :number_of_fouees)
                ON DUPLICATE KEY UPDATE fouees = fouees + :number_of_fouees";

        $query = $dbh->prepare($sql);
        $query->bindValue(':requested_date', date('Y-m-d', strtotime($requestedTime)), PDO::PARAM_STR);
        $query->bindValue(':requested_hour', date('H:i:s', strtotime($requestedTime)), PDO::PARAM_STR);
        $query->bindValue(':number_of_fouees', $foueesToAdd, PDO::PARAM_INT);
        $query->execute();

        // Mettre à jour le temps pour la prochaine répartition
        $requestedTime = date('Y-m-d H:i:s', strtotime($requestedTime) + 10 * 60);

        // Mettre à jour le nombre de fouées restantes
        $numberOfFouees -= $foueesToAdd;

        // S'il reste des fouées à répartir, appeler récursivement la fonction
        if ($numberOfFouees > 0) {
            return distributeFouees($requestedTime, $numberOfFouees);
        } else {
            return true; // Tout a été réparti avec succès
        }
    } else {
        return false; // Capacité du four atteinte
    }
}
?>
