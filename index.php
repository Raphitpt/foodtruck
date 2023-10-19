<?php
require './bootstrap.php';

$plats_sale = "SELECT * FROM plats WHERE id_categorie = 2";
$plats_sale = $dbh->query($plats_sale);
$plats_sale = $plats_sale->fetchAll();

$plats_sucre = "SELECT * FROM plats WHERE id_categorie = 1";
$plats_sucre = $dbh->query($plats_sucre);
$plats_sucre = $plats_sucre->fetchAll();


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

        <div class="plats sale">
            <h1>Fouées salés</h1>
            </br>
            <div class="plats_card">
                <?php foreach ($plats_sale as $plat) : ?>
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
                <?php endforeach; ?>
            </div>
        </div>
        <div class="plats sucre">
            <h1>Fouées sucrés</h1>
            </br>
            <div class="plats_card">
                <?php foreach ($plats_sucre as $plat) : ?>

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

                <?php endforeach; ?>
            </div>
        </div>
        <div class="panier">
            
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
        <a href="./mail.php">Mail</a>
    </main>
    <script src="./assets/js/functions.js"></script>
</body>

</html>