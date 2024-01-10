<?php
require './bootstrap.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}



$horaires = "SELECT * FROM planning";
$horaires = $dbh->query($horaires);
$horaires = $horaires->fetchAll();

$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();



echo head('Accueil');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations entreprise</title>
</head>
<body>
    
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p>Fouée't Moi
            </li>
            <li><button onclick="location.href = './index.php'" class="button_nav">Accueil</button></li>
            <li><button onclick="location.href = ''" class="button_nav">Commander</button></li>
            <li><button onclick="location.href = ''" class="button_nav">Nous contacter</button></li>
        </ul>
        <ul class="nav_right">
            <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
        </ul>
    </nav>
    <main>
        
        <section>
            <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
            <div class="title_infos">
                <h1>Les informations de l'entreprise sont :</h1>
                <div class="infos">
                    <p>Nom de l'entreprise : <?= $infos['nom_entreprise'] ?></p>
                    <p>Adresse : <?= $infos['adresse_entreprise'] ?></p>
                    <p>Logo : <img src="<?= $infos['url_logo'] ?>" alt="logo fouee"></p>
                </div>
                <h1>Les horaires d'ouverture et de fermeture sont :</h1>
                <div class="infos">
                    <?php foreach ($horaires as $horaire) : ?>
                        <p><?= $horaire['Jour'] ?> : <?= $horaire['HeureOuverture']?> - <?= $horaire['HeureFermeture'] ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="buttons">
                <button type="button" class="actions"><a href="editEntreprise.php">Modifier</a></button>
            </div>
        </section>
    </main>
</body>
</html>