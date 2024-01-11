<?php
require './bootstrap.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les données POST
    $panier = $_POST['panier'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    // Effectuez l'insertion dans la base de données (assurez-vous de traiter les données pour éviter les injections SQL)
    $stmt = $dbh->prepare("INSERT INTO commandes (panier, date, heure) VALUES (?, ?, ?)");
    $stmt->execute([$panier, $date, $heure]);

    // Réponse JSON pour le client
    $response = array('status' => 'success', 'message' => 'Commande enregistrée avec succès.');
    echo json_encode($response);
} else {
    // Réponse en cas de requête non autorisée
    $response = array('status' => 'error', 'message' => 'Requête non autorisée.');
    echo json_encode($response);
}
?>
