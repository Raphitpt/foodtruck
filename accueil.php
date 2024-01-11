<?php
require './bootstrap.php';
session_start();
$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();
echo head('Page d\'accueil');
?>
<style>
    h1 {
        text-align: center;
    }

    img {
        width: 40%;
    }
    .accueil{
        height:calc(100vh - 4.5rem);
        width:100vw;
        display:flex;
        flex-direction:column;
        justify-content: space-around;
        align-items: center;    

    }
    .accueil button{
        width:20vw;
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
            <img src="./assets/img/Fichier 2.png"/>
            <button onclick="location.href = './index.php'">Aller commander</button>
        </section>
    </main>

</body>