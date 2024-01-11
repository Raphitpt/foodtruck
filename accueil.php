<?php
require './bootstrap.php';
session_start();
$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();
echo head('Page d\'accueil');
?>
<style>
    body{
        overflow-x:hidden;
    }
    h1 {
        text-align: center;
    }

    img {
        width: 40%;
    }

    .accueil {
        height:160vh;
        width: 100vw;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    .accueil button {
        width: 15vw;
        padding:15px;
    }

    .accueil div:nth-child(3) {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .accueil div:nth-child(3) p {
        height: 50%;
        width: 30%;
        text-align: center;
    }

    .accueil div:nth-child(4) {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .accueil div:nth-child(4) p {
        height: 50%;
        width: 40%;
        padding:15px;
        text-align: center;
      
    }
    .accueil div:nth-child(4) img{
        width:25vw;
    }
    footer{
        height:10vh;
        width:100vw;
        background-color:black;
    }
</style>

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
            <button onclick="location.href = './index.php'">Aller commander</button>
            <div>
                <img src="./assets/img/Fichier 2.png" />
                <p>
                    La fouée est une petite boule de pain, cuite au four et fourrée encore chaude de rillettes,
                    de grillon, de champignons, de mogettes ou de beurre, selon les régions. La fouée est issue du
                    terroir gastronomique de l'ouest de la France (Touraine, Saumurois, Poitou, Charentes).
                </p>
            </div>
            <div>
                <p>
                    Nous sommes un jeune couple charentais passionné par la création de fouée, tradition familiale
                    depuis 50, nous nous engageons a vous préparer des fouées d'une grande qualité et à régaler
                    vos papilles. On réalise des fouées sucrées et salés pour faire plaisir à tout le monde. Venez partager
                    un repas avec nous, vous ne le regretterez pas.
                </p>
                <img src="./assets/img/vue-du-couple-3d.jpg" />

            </div>

        </section>
    </main>
    <footer></footer>

</body>