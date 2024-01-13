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

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $photo = "SELECT * FROM users where email = :email";
    $stmt = $dbh->prepare($photo);
    $stmt->execute([
        'email' => $email,
    ]);
    $photo = $stmt->fetch();
}




echo head('Informations de votre entreprise');

?>
<script>
    window.onload = function () {
        // Check screen width
        var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        // Define the minimum width for access
        var minWidth = 800;

        // Redirect if the screen width is smaller than the minimum width
        if (screenWidth < minWidth) {
            alert("Cette page est accessible uniquement sur des écrans larges avec une largeur supérieure à 800px. Veuillez utiliser un écran plus grand.");
            window.location.href = 'index.php'; // Redirect to the appropriate page
        }
    };
</script>

<body>

    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p><?= $infos['nom_entreprise'] ?></p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
            <?php endif; ?>
        </ul>
        <ul class="nav_right">
            <?php if (isset($_SESSION['email'])) : ?>
                <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
            <?php else : ?>
                <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
            <?php endif; ?>
        </ul>
    </nav>
    <main>
        <div class="btn-retour">
            <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <section class="infosEntr">

            <div class="title_infos">
                <h1>Les informations de l'entreprise sont :</h1>
                <div class="infos">
                    <p>Nom de l'entreprise : <strong><?= $infos['nom_entreprise'] ?></strong></p>
                    <p>Adresse : <strong><?= $infos['adresse_entreprise'] ?></strong></p>
                    <p>N° de téléphone <strong><?= $infos['tel'] ?></p></strong>
                    <p>Adresse mail : <strong><?= $infos['email'] ?></strong></p>
                    <p>Logo : <img src="<?= $infos['url_logo'] ?>" alt="logo fouee"></p>
                </div>
                <h1>Les horaires d'ouverture et de fermeture sont :</h1>
                <div class="infos">
                    <?php foreach ($horaires as $horaire) : ?>
                        <p><?= $horaire['Jour'] ?> : <strong><?= $horaire['HeureOuverture'] ?> - <?= $horaire['HeureFermeture'] ?></strong></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <button type="button" class="actions"><a href="editEntreprise.php">Modifier</a></button>
            </div>
        </section>
    </main>
</body>

</html>