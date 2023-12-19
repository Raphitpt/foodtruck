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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
    <title>Connexion</title>
</head>

<body>
    <nav>
        <ul>
            
            </li>
            <li><button><a href="./index.php"><i class="fa-solid fa-house"></i></a></button></li>
            <li><button><a href=""><i class="fa-solid fa-truck"></i></a></button></li>
            <li> <img src="./assets/img/FOUEE2.png" alt="logo fouee">
            <li><button><a href=""><i class="fa-solid fa-phone"></i></a></button></li>
            <li><button><a href="./login.php"><i class="fa-solid fa-user"></i></a></button></li>
        </ul>
    </nav>
    <main>

        <!-- affichage des plats -->
        <section class="plats">
            <div class="plats_sale">
                <h1>Fouées salées</h1>
                </br>
                <div class="plats_card">
                    <?php foreach ($plats_sale as $plat): ?>
                        <div class="card" style="width: 18rem;">
                            <img src="..." class="card-img-top" alt="...">
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
                                <div class="card-footer-plats">
                                    <!-- <button type="button" class="btn btn-primary">Ajouter</button> -->
                                    <input type="number" class="form-control" value="0"
                                        id="input-number-<?= $plat['id_plat'] ?>">
                                    <button type="button" class="btn btn-success" id="ajouter">+</button>
                                    <button type="button" class="btn btn-danger" id="enlever">-</button>
                                    <div id="supplement-card-<?= $plat['id_plat'] ?>">

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="plats_sucre">
                <h1>Fouées sucrées</h1>
                </br>
                <div class="plats_card">
                    <?php foreach ($plats_sucre as $plat): ?>

                        <div class="card" style="width: 18rem;">
                            <img src="..." class="card-img-top" alt="...">
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
                                <div class="card-footer-plats">
                                    <!-- <button type="button" class="btn btn-primary">Ajouter</button> -->
                                    <input type="number" class="form-control" value="0"
                                        id="input-number-<?= $plat['id_plat'] ?>">
                                    <button type="button" class="btn btn-success" id="ajouter">+</button>
                                    <button type="button" class="btn btn-danger" id="enlever">-</button>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
            <div class="commande">
                <div class="panier"></div>
                <!-- <a href="./order.php" class="order-button">Commander</a> -->
            </div>



            <!-- affichage des suppléments -->

            <div class="supplements" style="display:none;">
                <h1>Suppléments</h1>
                </br>
                <div class="cross_close">
                    <img src="./assets/img/cross_close.png" alt="croix fermer">
                </div>
                <div class="plats_card">
                    <?php foreach ($supplements as $supplement): ?>

                        <div class="card" style="width: 18rem;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $supplement['nom'] ?>
                                </h5>
                                <p class="card-price">
                                    <?= $supplement['prix'] ?>€
                                </p>
                                <div class="card-footer-plats">
                                    <!-- <button type="button" class="btn btn-primary">Ajouter</button> -->
                                    <input type="number" class="form-control" value="0"
                                        id="input-number-<?= $plat['id_plat'] ?>">
                                    <button type="button" class="btn btn-success" id="ajouter">+</button>
                                    <button type="button" class="btn btn-danger" id="enlever">-</button>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
            <div class="fouee-recap">

            </div>
            <a href="./mail.php">Mail</a>
        </section>
        <section>
            <div>Je suis gay </div>
        </section>
    </main>

    <script src="./assets/js/functions.js"></script>
    <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>