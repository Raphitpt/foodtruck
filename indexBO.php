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
            <h1>Fouées salées</h1>
            </br>
            <div class="plats_card">
                <?php foreach ($plats_sale as $plat) : ?>
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $plat['nom'] ?></h5>
                            <input type="hidden" name="id_plats" class="id_plats" value="<?= $plat['id_plat'] ?>">
                            <p class="card-text"><?= $plat['composition'] ?></p>
                            <p class="card-price"><?= $plat['prix'] ?>€</p>
                            <div class="card-footer-plats">
                                <!-- <button type="button" class="btn btn-primary">Ajouter</button> -->
                                <!-- <input type="number" class="form-control" value="0" id="input-number">
                                <button type="button" class="btn btn-success" id="ajouter">+</button>
                                <button type="button" class="btn btn-danger" id="enlever">-</button> -->
                                <button type="button"><a href="editPlats.php?id_plat=<?= $plat['id_plat'] ?>">Modifier</a></button>
                                <button type="button"><a href="addPlats.php?id_plat=<?= $plat['id_plat'] ?>">Ajouter</a></button>
                                <button type="button"><a href="suppressionPlats.php?id_plat=<?= $plat['id_plat'] ?>">Effacer</a></button>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="plats sucre">
            <h1>Fouées sucrées</h1>
            </br>
            <div class="plats_card">
                <?php foreach ($plats_sucre as $plat) : ?>

                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $plat['nom'] ?></h5>
                            <input type="hidden" name="id_plats" class="id_plats" value="<?= $plat['id_plat'] ?>">
                            <p class="card-text"><?= $plat['composition'] ?></p>
                            <p class="card-price"><?= $plat['prix'] ?>€</p>
                            <div class="card-footer-plats">
                                <!-- <button type="button" class="btn btn-primary">Ajouter</button> -->
                                <!-- <input type="number" class="form-control" value="0" id="input-number">
                                <button type="button" class="btn btn-success" id="ajouter">+</button>
                                <button type="button" class="btn btn-danger" id="enlever">-</button> -->
                                <button type="button"><a href="suppressionPlats.php?id_plat=<?= $plat['id_plat'] ?>">Effacer</a></button>
                                <button type="button"><a href="editPlats.php?id_plat=<?= $plat['id_plat'] ?>">Modifier</a></button>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
        


        <!-- affichage des suppléments -->

        <div class="supplements" style="display:none;">
                <h1>Suppléments</h1>
                </br>
                <div class="cross_close">
                    <img src="./assets/img/cross_close.png" alt="croix fermer">
                </div>
            <div class="plats_card">
                <?php foreach ($supplements as $supplement) : ?>

                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $supplement['nom'] ?></h5>
                            <p class="card-price"><?= $supplement['prix'] ?>€</p>
                            <div class="card-footer-plats">
                                <!-- <button type="button" class="btn btn-primary">Ajouter</button> -->
                                <input type="number" class="form-control" value="0" id="input-number">
                                <button type="button" class="btn btn-success" id="ajouter">+</button>
                                <button type="button" class="btn btn-danger" id="enlever">-</button>
                                <button type="button"><a href="suppressionSuppl.php?id_suppl=<?= $supplement['id_suppl'] ?>">Effacer</a></button>
                                <button type="button"><a href="editSuppl.php?id_suppl=<?= $supplement['id_suppl'] ?>">Modifier</a></button>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
        <div class="fouee-recap">

        </div>
        <a href="./mail.php">Mail</a>
    </main>
    <script src="./assets/js/functions.js"></script>
</body>

</html>