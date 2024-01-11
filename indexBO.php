<?php
require './bootstrap.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
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

$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

$horaires = "SELECT * FROM planning";
$horaires = $dbh->query($horaires);
$horaires = $horaires->fetchAll();



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
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p><?php echo $infos['nom_entreprise'] ?></p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <li><button onclick="location.href = ''" class="button_nav">Nous contacter</button></li>
        </ul>
        <ul class="nav_right">
            <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
        </ul>
    </nav>

    <main id="BO">
        <section class="col-left">
            <div>
                <h1>Bienvenue sur le Back Office</h1>
            </div>

            <div class="action">
                <a href="orderCheck.php">Commandes en cours</a>
                <a href="orderHist.php">Historique des commandes</a>
                <a href="index.php">Voir le site web</a></button>
                <a href="ptsFid.php">Points de fidélité</a>
                <a href="infosEntreprise.php">Données de votre entreprise</a>

            </div>
        </section>


        <!-- affichage des plats -->

        <main class="main_commande">
            <!-- affichage des plats -->

            <section class="plats">

                <div class="plats_title">
                    <div class="plats_titles">
                        <button type="button" class="button_fouee sel plats_title_salées" onclick="togglePlat('plats_salées')">fouées salées</button>
                        <button type="button" class="button_fouee plats_title_sucrées" onclick="togglePlat('plats_sucrées')">fouées sucrées</button>
                        <button type="button" class="button_fouee plats_title_suppléments" onclick="togglePlat('suppléments')">suppléments</button>


                    </div>
                    <div class="line"></div>
                </div>
                </div>
                <div class="plats_sale">
                    </br>
                    <button type="button" class="actions"><a href="addPlatsl.php">Ajouter<i class="fa-solid fa-plus"></i></a></button>
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
                                    <div class="buttons">
                                        <button type="button" class="actions"><a href="editPlats.php?id_plat=<?= $plat['id_plat'] ?>">Modifier</a></button>
                                        <button type="button" class="actions"><a href="suppressionPlats.php?id_plat=<?= $plat['id_plat'] ?>">Effacer</a></button>
                                    </div>


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
                    <button type="button" class="actions"><a href="addPlats.php">Ajouter<i class="fa-solid fa-plus"></i></a></button>
                    <div class="plats_card">


                        <?php foreach ($plats_sucre as $plat) : ?>

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
                                    <div class="buttons">
                                        <button type="button" class="actions"><a href="editPlats.php?id_plat=<?= $plat['id_plat'] ?>">Modifier</a></button>
                                        <button type="button" class="actions"><a href="suppressionPlats.php?id_plat=<?= $plat['id_plat'] ?>">Effacer</a></button>
                                    </div>

                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="plats_suppl" style="display: none;">
                    </br>
                    <button type="button" class="actions"><a href="addSuppl.php">Ajouter<i class="fa-solid fa-plus"></i></a></button>
                    <div class="plats_card">

                        <?php foreach ($supplements as $supplement) : ?>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= $supplement['nom'] ?>
                                    </h5>
                                    <img class="card_img" src="./assets/img/Fouées_angevines_avec_rillettes.JPG" alt="Photo d'un fouées à la rillette">

                                    <input type="hidden" name="id_suppl" class="id_suppl" value="<?= $supplement['id_suppl'] ?>">
                                    <p class="card-price">
                                        <?= $supplement['prix'] ?>€
                                    </p>
                                    <div class="buttons">
                                        <button type="button" class="actions"><a href="editSuppl.php?id_suppl=<?= $supplement['id_suppl'] ?>">Modifier</a></button>
                                        <button type="button" class="actions"><a href="suppressionSuppl.php?id_suppl=<?= $supplement['id_suppl'] ?>">Effacer</a></button>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            </section>


        </main>
        <script src="./assets/js/functions.js"></script>
</body>

</html>