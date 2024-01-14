<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

echo head('Mise à jour des points de fidélité');



if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données JSON du corps de la requête
    $json_data = file_get_contents("php://input");

    // Décoder les données JSON en tableau associatif
    $data = json_decode($json_data, true);

    // Vérifier si la clé 'pts_fidelite' existe dans les données
    if (isset($data['pts_fidelite'])) {
        // Récupérer la valeur de 'pts_fidelite'
        $pts_fidelite = $data['pts_fidelite'];

        // Faites quelque chose avec la valeur $pts_fidelite, par exemple, mettez à jour votre base de données
        $sql = "UPDATE users SET pts_fidelite = :pts_fidelite WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':pts_fidelite' => $pts_fidelite,
            ':email' => $email
        ]);

        // Répondre avec un tableau JSON indiquant le succès
        echo json_encode(['success' => true]);
    } else {
        // Répondre avec une erreur si la clé 'pts_fidelite' est manquante
        echo json_encode(['success' => false, 'error' => 'La clé pts_fidelite est manquante']);
    }
}

?>