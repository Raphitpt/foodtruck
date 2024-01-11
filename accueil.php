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
    *{
        font-family: 'Montserrat', sans-serif;
    }

    h1 {
        text-align: justify;
        font-size: 4rem;
        font-weight: 700;

    }
    h2{
        text-align:center;
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
        border: 1px solid #E56D00;
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
    span{
        color:#E56D00;
        font-weight: bold;
    }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
<body>
    <header>
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
    </header>
    <main>
        <section class="accueil">
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

</body>