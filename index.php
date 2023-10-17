<?php
require './bootstrap.php';
$plats= "SELECT * FROM plats";
$plats = $dbh->query($plats);
$plats = $plats->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/css/style.css" rel="stylesheet">
    <title>test</title>
</head>
<body>
    <h1>test de cvvo</h1>
    <button><a href="./login.php">Se connecter</a></button>
    <button><p>Panier</p></button>
    <button>S'isncrire</button>
    <div>
        <?php
        foreach($plats as $plat){
            echo "<div class='plat'>";
            echo "<h2>".$plat['nom']."</h2>";
            echo "<p>".$plat['composition']."</p>";
            echo "<p>".$plat['prix']."€</p>";
            echo "</div>";

        }
        ?>
    </div>
</body>
</html>