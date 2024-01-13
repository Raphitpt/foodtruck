<?php
require './bootstrap.php';
session_start();
echo head('Page d\'accueil');
$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();

$contenuQuery = "SELECT * FROM elements_accueil";
$contenuResult = $dbh->query($contenuQuery);
$contenu = $contenuResult->fetch();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $photo = "SELECT * FROM users where email = :email";
    $stmt = $dbh->prepare($photo);
    $stmt->execute([
        'email' => $email,
    ]);
    $photo = $stmt->fetch();
}



?>
<style>
    body {
        overflow-x: hidden;
    }

    * {
        font-family: 'Montserrat', sans-serif;
    }

    h1 {
        text-align: justify;
        font-size: 4rem;
        font-weight: 700;
    }

    h2 {
        text-align: center;
        font-weight: 700;
    }

    img {
        width: 40%;
    }

    .accueil {
        height: 160vh;
        width: 100vw;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    .accueil button {
        width: 15vw;
        padding: 1rem;
        border: none;
        background-color: #f5F5F5;
        font-weight: 700;
        border-radius: 11px;
        color: black;
        border: 2px solid #e56D00;
    }

    .accueil div:nth-child(3) {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .accueil div:nth-child(3) div {
        height: 50%;
        width: 30%;
        text-align: justify;
    }

    .accueil div:nth-child(4) {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .accueil div:nth-child(4) div {
        height: 50%;
        width: 40%;
        padding-right: 3rem;
        text-align: justify;
    }

    .accueil div:nth-child(4) img {
        width: 25vw;
    }

    footer {
        height: 10vh;
        width: 100vw;
        background-color: black;
    }

    span {
        color: #e56D00;
        font-weight: bold;
    }

    button:hover {
        color: white;
        background-color: #e56D00;
    }

    .choixlangue div {
        position: absolute;
        top: 0;
        right: 1.5rem;
        cursor: pointer;
        padding: 1rem;
        text-align: end;
        width: 4vw;
    }

    .choixlangue img {
        width: 4vw;
    }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

<body>
    <header>
        <nav class="navfr">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p><?= $infos['nom_entreprise'] ?></p>
                </li>
                <li><button onclick="location.href = './accueil.php'" class="button_nav"><?= htmlspecialchars("Accueil") ?></button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav"><?= htmlspecialchars("Commander") ?></button></li>
                <li><button onclick="location.href = './contact.php'" class="button_nav"><?= htmlspecialchars("Nous contacter") ?></button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav"><?= htmlspecialchars("Back Office") ?></button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) { ?>
                    <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                <?php } else { ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect"><?= htmlspecialchars("Se connecter") ?></button></li>
                    <li><button onclick="location.href = './signin.php'" class="button_nav connect"><?= htmlspecialchars("S'inscrire") ?></button></li>
                <?php } ?>

            </ul>
        </nav>
        <nav style="display:none;" class="navang">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p><?= htmlspecialchars("Fouée't Moi") ?>
                </li>
                <li><button onclick="location.href = '#'" class="button_nav"><?= htmlspecialchars("Home") ?></button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav"><?= htmlspecialchars("Order") ?></button></li>
                <li><button onclick="location.href = ''" class="button_nav"><?= htmlspecialchars("Contact us") ?></button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav"><?= htmlspecialchars("Back Office") ?></button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) { ?>
                    <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                <?php } else { ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect"><?= htmlspecialchars("Se connecter") ?></button></li>
                    <li><button onclick="location.href = './signin.php'" class="button_nav connect"><?= htmlspecialchars("S'inscrire") ?></button></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="choixlangue">
            <div class="français" style="display:none;"><img src="./assets/img/FR_MARIN.png"></div>
            <div class="anglais"><img src="./assets/img/Flag_of_Great_Britain_(1707–1800).svg.png"></div>
        </div>

        <section class="accueil ang" style="display:none">
            <h1><?= htmlspecialchars("Welcome on") ?> <?php echo htmlspecialchars($contenu['nom_entreprise'], ENT_QUOTES) ?></h1>
            <button onclick="location.href = './index.php'"><?= htmlspecialchars("Order") ?></button>
            <div>
                <img src="<?php echo htmlspecialchars($contenu['url_img1']) ?>" />
                <div>
                    <h2><?php echo htmlspecialchars($contenu['title1EN'], ENT_QUOTES) ?></h2>
                    <p>
                        <?php echo htmlspecialchars($contenu['texte1EN'], ENT_QUOTES) ?>
                    </p>
                </div>
            </div>
            <div>
                <div>
                    <h2><?php echo htmlspecialchars($contenu['title2EN'], ENT_QUOTES) ?></h2>
                    <p>
                        <?php echo htmlspecialchars($contenu['texte2EN'], ENT_QUOTES) ?>
                    </p>
                </div>
                <img src="<?php echo htmlspecialchars($contenu['url_img2']) ?>" />

            </div>

        </section>
        <section class="accueil fra">
            <h1><?= htmlspecialchars("Bienvenue sur") ?> <?php echo htmlspecialchars($contenu['nom_entreprise'], ENT_QUOTES) ?></h1>
            <button onclick="location.href = './index.php'"><?= htmlspecialchars("Commander") ?></button>
            <div>
                <img src="<?php echo htmlspecialchars($contenu['url_img1']) ?>" />
                <div>
                    <h2><?php echo htmlspecialchars($contenu['title1'], ENT_QUOTES) ?></h2>
                    <p>
                        <?php echo htmlspecialchars($contenu['texte1'], ENT_QUOTES) ?>
                    </p>
                </div>
            </div>
            <div>
                <div>
                    <h2><?php echo htmlspecialchars($contenu['title2'], ENT_QUOTES) ?></h2>
                    <p>
                        <?php echo htmlspecialchars($contenu['texte2'], ENT_QUOTES) ?>
                    </p>
                </div>
                <img src="<?php echo htmlspecialchars($contenu['url_img2']) ?>" />

            </div>

        </section>
    </main>
    <footer></footer>
    <script src="./assets/js/functions.js"></script>
</body>

</html>