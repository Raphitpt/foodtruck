<?php
require './bootstrap.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    exit();
}

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
        <ul>
            
            </li>
            <li><a href="./index.php"><i class="fa-solid fa-house"></i></a></li>
            <li><a href=""><i class="fa-solid fa-truck"></i></a></li>
            <li> <img src="./assets/img/FOUEE2.png" alt="logo fouee">
            <li><a href=""><i class="fa-solid fa-phone"></i></a></li>
            <li><a href="./login.php"><i class="fa-solid fa-user"></i></a></li>
        </ul>
    </nav>

    <main>

        <!-- affichage des plats -->

        <section class="plats">
            <div class="plats_title">
                <div class="plats_titles">
                    <div class="plats_title_salées" onclick="togglePlat('plats_salées')">
                        <i class="fa-solid fa-circle-dot" style="font-size:2rem; color:#FF5C28;" ></i>
                        <h1>Fouées salées</h1>
                    </div>
                    <div class="plats_title_sucrées" onclick="togglePlat('plats_sucrées')">
                        <i class="fa-solid fa-circle-dot" ></i>
                        <h1>Fouées sucrées</h1>
                    </div>
                    <div class="plats_title_suppléments" onclick="togglePlat('suppléments')">
                        <i class="fa-solid fa-circle-dot" ></i>
                        <h1>Suppléments</h1>
                    </div>
                </div>
                <div class="line">
                </div>
            </div>
            <div class="plats_sale">
                </br>
                <button type="button"><a href="addPlats.php?">Ajouter</a></button>
                <div class="plats_card">
                
                    <?php foreach ($plats_sale as $plat): ?>
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
                                <div class="card-footer-plats">
                                    <button type="button"><a href="editPlats.php?id_plat=<?= $plat['id_plat'] ?>">Modifier</a></button>
                                    <button type="button"><a href="suppressionPlats.php?id_plat=<?= $plat['id_plat'] ?>">Effacer</a></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="plats_sucre" style="display: none;">
                </br>
                <button type="button"><a href="addPlats.php?">Ajouter</a></button>

                <div class="plats_card">
                    <?php foreach ($plats_sucre as $plat): ?>

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
                                <div class="card-footer-plats">
                                    <button type="button"><a href="editPlats.php?id_plat=<?= $plat['id_plat'] ?>">Modifier</a></button>
                                    <button type="button"><a href="suppressionPlats.php?id_plat=<?= $plat['id_plat'] ?>">Effacer</a></button>
                                    
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
            <div class="plats_suppl" style="display: none;">
                </br>
                <button type="button"><a href="addSuppl.php">Ajouter</a></button>
                <div class="plats_card">
                
                    <?php foreach ($supplements as $supplement): ?>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $supplement['nom'] ?>
                                </h5>
                                <input type="hidden" name="id_suppl" class="id_suppl" value="<?= $supplement['id_suppl'] ?>">
                                <p class="card-price">
                                    <?= $supplement['prix'] ?>€
                                </p>
                                <div class="card-footer-plats">
                                    <button type="button"><a href="editSuppl.php?id_suppl=<?= $supplement['id_suppl'] ?>">Modifier</a></button>
                                    <button type="button"><a href="suppressionSuppl.php?id_suppl=<?= $supplement['id_suppl'] ?>">Effacer</a></button>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
    </main>
    <script src="./assets/js/functions.js"></script>
</body>

</html>