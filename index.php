<?php
require './bootstrap.php';

$plats = "SELECT * FROM plats";
$plats = $dbh->query($plats);
$plats = $plats->fetchAll();

$supplements = "SELECT * FROM supplements";
$supplements = $dbh->query($supplements);
$supplements = $supplements->fetchAll();

echo head('Accueil');

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/css/style.css" rel="stylesheet">
    <title>Connexion</title>
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
            <?php foreach ($plats as $plat) : ?>
                <div class="plats_card">
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $plat['nom'] ?></h5>
                            <p class="card-text"><?= $plat['composition'] ?></p>
                            <p><?= $plat['prix'] ?>€</p>
                            <div class="card-footer-plats">
                                <button type="button" class="btn btn-primary">Ajouter</button>
                                <input type="number" class="form-control" value="0" id="input-number">
                                <button type="button" class="btn btn-success" id="ajouter">+</button>
                                <button type="button" class="btn btn-danger" id="enlever">-</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


        <!-- affichage des suppléments -->

        <div class="supplements" style="display:none;">
            <?php
            foreach ($supplements as $supplement) {
                echo "<div class='supplements'>";
                echo "<h2>" . $supplement['nom'] . "</h2>";
                echo "<p>" . $supplement['prix'] . "€</p>";
                echo "</div>";
            }
            ?>
        </div>
    </main>
    <script src="./assets/js/functions.js"></script>
</body>

</html>