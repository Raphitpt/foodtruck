<?php
require './bootstrap.php';

session_start();



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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
    <title>Connexion</title>
</head>

<body>
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="./assets/img/Frame 3.png" alt="logo fouee">
                <p>Fouée't Moi
            </li>
            <li><button onclick="location.href = './index.php'" class="button_nav">accueil</button></li>
            <li><button onclick="location.href = ''" class="button_nav">commander</button></li>
            <li><button onclick="location.href = ''" class="button_nav">nous contacter</button></li>
        </ul>
        <ul class="nav_right">
            <li><button onclick="location.href = './login.php'" class="button_nav connect">se connecter</button></li>
        </ul>
    </nav>
    <main class="main_commande">
        <!-- affichage des plats -->
        <section class="plats">
            <div class="plats_title">
                <div class="plats_titles">
                    <button type="button" class="button_fouee sel plats_title_salées" onclick="togglePlat('plats_salées')">fouées salées</button>
                    <button type="button" class="button_fouee plats_title_sucrées" onclick="togglePlat('plats_sucrées')">fouées sucrées</button>

                </div>
                <div class="line"></div>
            </div>
            </div>
            <div class="plats_sale">
                </br>
                <div class="plats_card">
                    <?php foreach ($plats_sale as $plat) : ?>
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $plat['nom'] ?>
                                </h5>
                                <img class="card_img" src="./assets/img/Fouées_angevines_avec_rillettes.JPG" alt="Photo d'un fouées à la rillette">
                                <input type="hidden" name="id_plats" class="id_plats" value="<?= $plat['id_plat'] ?>">
                                <p class="card-text">
                                    <?= $plat['composition'] ?>
                                </p>
                                <p class="card-price">
                                    <?= $plat['prix'] ?>€
                                </p>
                                <button type="button" class="button_add"><i class="fa-solid fa-plus"></i>ajouter</button>
                                <!-- <input type="number" class="form-control" value="0"
                                        id="input-number-<?= $plat['id_plat'] ?>">
                                    <button type="button" class="btn btn-success" id="ajouter">+</button>
                                    <button type="button" class="btn btn-danger" id="enlever">-</button>
                                    <div id="supplement-card-<?= $plat['id_plat'] ?>"> -->

                                <!-- </div> -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="plats_sucre" style="display: none;">
                </br>
                <div class="plats_card">
                    <?php foreach ($plats_sucre as $plat) : ?>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $plat['nom'] ?>
                                </h5>
                                <input type="hidden" name="id_plats" class="id_plats" value="<?= $plat['id_plat'] ?>">
                                <p class="card-text">
                                    <?= $plat['composition'] ?>
                                </p>
                                <p class="card-price">
                                    <?= $plat['prix'] ?>€
                                </p>
                                <button type="button" class="button_add">Ajouter</button>
                                <!-- <div class="card-footer-plats">
                                    <button type="button" class="btn btn-primary">Ajouter</button>
                                    <input type="number" class="form-control" value="0" id="input-number-<?= $plat['id_plat'] ?>">
                                    <button type="button" class="btn btn-success" id="ajouter">+</button>
                                    <button type="button" class="btn btn-danger" id="enlever">-</button>
                                </div> -->
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>

        </section>

        <section>        
            <div class="commande rectangle">
            <div class="panier"><i class="fa-solid fa-cart-shopping"></i></div>
            <!-- <a href="./order.php" class="order-button">Commander</a> -->
        </div>
        </section>
    </main>

    <script src="./assets/js/functions.js"></script>
    <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>