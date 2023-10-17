<?php
require './bootstrap.php';
$plats= "SELECT * FROM plats";
$plats = $dbh->query($plats);
$plats = $plats->fetchAll();

$supplements= "SELECT * FROM supplements";
$supplements = $dbh->query($supplements);
$supplements = $supplements->fetchAll();

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
    <nav>
        <img src="" alt="logo fouee">
        <ul>
            <li><button><a href="./index.php">Accueil</a></button></li>
            <li><button><a href="">Commander</a></button></li>
            <li><button><a href="">Panier</a></button></li>
            <li><button><a href="./login.php">Se connecter</a></button></li>
            <li><button><a href="signin.php">S'inscrire</a></button></li>
        </ul>
    </nav>

    <main>

<!-- affichage des plats -->

        <div class="plats">
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

    <!-- affichage des suppléments -->

        <div class="supplements" style="display:none;">
            <?php
            foreach($supplements as $supplement){
                echo "<div class='supplements'>";
                echo "<h2>".$supplement['nom']."</h2>";
                echo "<p>".$supplement['prix']."€</p>";
                echo "</div>";
            }
            ?>
        </div>
    </main>

</body>
</html>