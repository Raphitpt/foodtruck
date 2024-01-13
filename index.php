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

$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

$day = date('l');
$horaires = "SELECT HeureOuverture, HeureFermeture FROM planning WHERE Jour = '$day'";
$horaires = $dbh->query($horaires);
$horaires = $horaires->fetch();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $photo = "SELECT * FROM users where email = :email";
    $stmt = $dbh->prepare($photo);
    $stmt->execute([
        'email' => $email,
    ]);
    $photo = $stmt->fetch();
}


echo head('Commander');

?>

<body>
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p>
                    <?= $infos['nom_entreprise'] ?>
                </p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <li><button onclick="location.href = './index.php'" class="button_nav">Commander</button></li>
            <li><button onclick="location.href = './contact.php'" class="button_nav">Nous contacter</button></li>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>
                <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
            <?php endif; ?>
        </ul>
        <ul class="nav_right">
            <?php if (isset($_SESSION['email'])) { ?>
                <button onclick="location.href = 'profil.php'" class="image"><img
                        src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
            <?php } else { ?>
                <li><button onclick="location.href = './login.php'" class="button_nav connect">
                        <?= htmlspecialchars("Se connecter") ?>
                    </button></li>
                <li><button onclick="location.href = './signin.php'" class="button_nav connect">
                        <?= htmlspecialchars("S'inscrire") ?>
                    </button></li>
            <?php } ?>
        </ul>
    </nav>




    <div class="infos_generale">
        <div>
            <h1>Commander</h1>
            <div class="line"></div>
        </div>
        <div>
            <p>Les horaires d'ouverture aujourd'hui sont :</p>
        <div>
        </div>     
            <p><?php echo $horaires['HeureOuverture'] ?>h -
                <?php echo $horaires['HeureFermeture'] ?>h
            </p>
            
        </div>
    </div>

    <main class="main_commande">
        <!-- affichage des plats -->

        <section class="plats list_plat">

            <div class="plats_title">
                <div class="plats_titles">
                    <button type="button" class="button_fouee sel plats_title_salées"
                        onclick="togglePlat('plats_salées')">Fouées salées</button>
                    <button type="button" class="button_fouee plats_title_sucrées"
                        onclick="togglePlat('plats_sucrées')">Fouées sucrées</button>

                </div>
                <div class="line"></div>
            </div>
            </div>
            <div class="plats_sale">
                </br>
                <div class="plats_card">
                    <?php foreach ($plats_sale as $plat): ?>
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $plat['nom'] ?>
                                </h5>
                                <img class="card_img" src="<?= $plat['image_plat'] ?>" alt="">
                                <input type="hidden" name="id_plats" class="id_plats" value="<?= $plat['id_plat'] ?>">
                                <p class="card-text">
                                    <?= $plat['composition'] ?>
                                </p>
                                <p class="card-price">
                                    <?= $plat['prix'] ?>€
                                </p>
                                <button type="button" class="button_add"><i class="fa-solid fa-plus"></i>Ajouter</button>
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
                    <?php foreach ($plats_sucre as $plat): ?>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $plat['nom'] ?>
                                </h5>
                                <img class="card_img" src="<?= $plat['image_plat'] ?>">

                                <input type="hidden" name="id_plats" class="id_plats" value="<?= $plat['id_plat'] ?>">
                                <p class="card-text">
                                    <?= $plat['composition'] ?>
                                </p>
                                <p class="card-price">
                                    <?= $plat['prix'] ?>€
                                </p>
                                <button type="button" class="button_add">Ajouter</button>

                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>

        </section>
        <section class="plats supplements" style="display: none;">
            <div>
                <h2>Voulez vous ajouter des suppléments ?</h2>
                <div class="line"></div>
            </div>
            <div class="suppl_sale">
                <h3>Suppléments salées</h3>
                <ul>
                    <?php foreach ($supplements as $supplement):
                        if ($supplement['type'] == "sale") { ?>

                            <div class="list_suppl">
                                <div>
                                    <li>
                                        <?= $supplement['nom'] ?>
                                    </li>
                                    <p class="idSuppl" style="display:none;">
                                        <?= $supplement['id_suppl'] ?>
                                    </p>
                                    <p>+
                                        <?= $supplement['prix'] ?> €
                                    </p>
                                </div>
                                <label>
                                    <input type="checkbox" class="input checkSuppl" data-id="<?= $supplement['id_suppl'] ?>"
                                        data-name="<?= $supplement['nom'] ?>" data-price="<?= $supplement['prix'] ?>">
                                    <span class="custom-checkbox"></span>
                                </label>
                            </div>
                        <?php }
                    endforeach; ?>
                </ul>
            </div>
            <div class="suppl_sucre">
                <h3>Suppléments sucrées</h3>
                <ul>
                    <?php foreach ($supplements as $supplement):
                        if ($supplement['type'] == "sucre") { ?>

                            <div class="list_suppl">
                                <div>
                                    <li>
                                        <?= $supplement['nom'] ?>
                                    </li>
                                    <p class="idSuppl" style="display:none;">
                                        <?= $supplement['id_suppl'] ?>
                                    </p>
                                    <p>+
                                        <?= $supplement['prix'] ?>€
                                    </p>
                                </div>
                                <label>
                                    <input type="checkbox" class="input checkSuppl" data-id="<?= $supplement['id_suppl'] ?>"
                                        data-name="<?= $supplement['nom'] ?>" data-price="<?= $supplement['prix'] ?>">
                                    <span class="custom-checkbox"></span>
                                </label>
                            </div>
                        <?php }
                    endforeach; ?>
                </ul>
            </div>
            <button class="noThanks">Non merci</button>
            <button class="addSupplYes">Ajouter</button>
        </section>
        <section class="panierBlock">
            <div class="h2Pannier">
                <h2>Votre commande</h2>
                <div class="line"></div>
            </div>
            <div class="commande rectangle">
                <div class="panier">

                    <p>votre panier est vide</P>
                </div>
                <!-- <a href="./order.php" class="order-button">Commander</a> -->
            </div>
        </section>
    </main>

    <script src="./assets/js/functions.js"></script>
    <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const numberAddElements = document.querySelectorAll('.number_add');

            numberAddElements.forEach(function (element) {
                const inputElement = element.querySelector('input');
                const addButton = element.querySelector('.add');
                const subButton = element.querySelector('.sub');

                addButton.addEventListener('click', function () {
                    inputElement.stepUp();
                });

                subButton.addEventListener('click', function () {
                    inputElement.stepDown();
                });

            });
        });
    </script>
</body>

</html>