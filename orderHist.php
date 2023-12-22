<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

echo head('Historique de commande');

$hist = "SELECT * FROM commandes";
$hist = $dbh->query($hist);
$hist = $hist->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./assets/css/style.css" rel="stylesheet">
    <title>Historique de commande</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Numéro de commande</th>
                <th>Numéro de client</th>
                <th>Date de commande</th>
                <th>Date de retrait</th>
                <th>Statut</th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hist as $histo) { ?>
                <tr>
                    <td><?php echo $histo['id_commande']; ?></td>
                    <td><?php echo $histo['id_user']; ?></td>
                    <td><?php echo $histo['date_commande']; ?></td>
                    <td><?php echo $histo['date_retrait']; ?></td>
                    <td><?php echo $histo['statut']; ?></td>
                    <td><?php echo $histo['commentaire']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="indexBO.php">Retour</a>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>