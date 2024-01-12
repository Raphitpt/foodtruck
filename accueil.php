<?php
require './bootstrap.php';
session_start();
$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();
echo head('Page d\'accueil');
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
        cursor: pointer;
        padding: 1rem;
        text-align: end;
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
                <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                    <p>Fouée't Moi</p>
                </li>
                <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav">Commander</button></li>
                <li><button onclick="location.href = ''" class="button_nav">Nous contacter</button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) : ?>
                    <li><button onclick="location.href = './logout.php'" class="button_nav connect">Se déconnecter</button></li>
                <?php else : ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
                <?php endif; ?>
            </ul>
        </nav>
        <nav style="display:none;" class="navang">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                    <p>Fouée't Moi
                </li>
                <li><button onclick="location.href = '#'" class="button_nav">Home</button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav">Order</button></li>
                <li><button onclick="location.href = ''" class="button_nav">Contact us</button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
            <?php if (isset($_SESSION['email'])) : ?>
                    <li><button onclick="location.href = './logout.php'" class="button_nav connect">Deconnexion</button></li>
                <?php else : ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect">Connexion</button></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="choixlangue">
            <div class="français" style="display:none;"><img src="./assets/img/FR_MARIN.png"></div>
            <div class="anglais"><img src="./assets/img/Flag_of_Great_Britain_(1707–1800).svg.png"></div>
        </div>

        <section class="accueil ang" style="display:none">
            <h1>Welcome on Fouée't Moi </h1>
            <button onclick="location.href = './index.php'">Order</button>
            <div>
                <img src="./assets/img/Fichier 2.png" />
                <div>
                    <h2>What is a fouée ?</h2>
                    <p>
                        The fouée is a small <span>ball of bread</span>, baked and still warm filled with rillettes,
                        of cricket, mushrooms, mogettes or butter, depending on the region. The fouée comes from the
                        <span>gastronomy</span> of western France (Touraine, Saumurois, Poitou, Charentes).
                </div>
            </div>
            <div>
                <div>
                    <h2>Who are we ?</h2>
                    <p>
                        We are a recent couple <span>Charentais passionate</span> about the creation of fouée, <span>family tradition </span>
                        since 50, we are committed to prepare you <span>high quality</span> fouées and to delight
                        your taste buds. <span>Sweet and savory </span> fouées are made to please everyone. <span>Come</span> share
                        A meal with us, you will not regret it.
                    </p>
                </div>
                <img src="./assets/img/vue-du-couple-3d.jpg" />

            </div>

        </section>
        <section class="accueil fra">
            <h1>Bienvenue sur Fouée't Moi </h1>
            <button onclick="location.href = './index.php'">Commander</button>
            <div>
                <img src="./assets/img/Fichier 2.png" />
                <div>
                    <h2>Qu'est ce qu'un fouée ?</h2>
                    <p>
                        La fouée est une petite <span>boule de pain</span>, cuite au four et fourrée encore chaude de rillettes,
                        de grillon, de champignons, de mogettes ou de beurre, selon les régions. La fouée est issue du
                        <span>terroir gastronomique</span> de l'ouest de la France (Touraine, Saumurois, Poitou, Charentes).
                    </p>
                </div>
            </div>
            <div>
                <div>
                    <h2>Qui sommes-nous ?</h2>
                    <p>
                        Nous sommes un jeune couple <span>charentais passionné</span> par la création de fouée, <span>tradition familiale</span>
                        depuis 50, nous nous engageons a vous préparer des fouées d'une <span>grande qualité</span> et à régaler
                        vos papilles. On réalise des fouées <span>sucrées et salés</span> pour faire plaisir à tout le monde. <span>Venez</span> partager
                        un repas avec nous, vous ne le regretterez pas.
                    </p>
                </div>
                <img src="./assets/img/vue-du-couple-3d.jpg" />

            </div>

        </section>
    </main>
    <footer></footer>
    <script src="./assets/js/functions.js"></script>

</body>