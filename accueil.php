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
    * {
        font-family: 'Montserrat', sans-serif;
    }

    body {
        overflow-x: hidden;
    }

    h1 {
        text-align: center;
        font-size: 4rem;
        font-weight: 700;
    }

    h2 {
        text-align: center;
        font-weight: 700;
    }

    img {
        width: 30vw;
        height: auto;
    }

    .accueil {
        height: 120vh;
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

    .accueil div:nth-child(4) {
        width: 60vw;
        display: grid;
        grid-template-columns: 1fr 1fr;
        text-align: justify;
        align-self: center;
        justify-content: center;
        align-items: center;
    }

    .accueil div:nth-child(4) p {
        padding: 1rem;
    }

    .accueil div:nth-child(4) div {
        height: 50%;
        width: 30%;


    }

    .accueil div:nth-child(4) img {
        grid-row: span 2;
        margin-left: auto;
        margin-right: auto;
        width: 20vw;

    }


    .accueil div:nth-child(5) {
        width: 60vw;
        display: grid;
        grid-template-columns: 1fr 1fr;
        text-align: justify;
        align-self: center;
        justify-content: center;
        align-items: center;
    }

    .accueil div:nth-child(5) p {
        padding: 1rem;
    }

    .accueil div:nth-child(5) div {
        height: 50%;
        width: 30%;


    }

    .accueil div:nth-child(5) img {
        grid-row: span 2;
        width: 20vw;
        margin-left: auto;
        margin-right: auto;


    }



    span {
        color: #e56D00;
        font-weight: bold;
    }

    .accueil button:hover {
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
        width: 2.5vw;
    }

    h5 {
        padding-top: 15px;
    }

    @media screen and (max-width:768px) {
        .accueil h1 {
            font-size: 1.7rem;
            margin-top: 1.5rem;
        }

        .accueil button {
            width: 40vw;
        }

        .accueil {
            height: auto;
            margin: 30px auto;
        }

        .accueil h1 {
            padding: 20px;
        }

        .accueil div:nth-child(4) {
            display: flex;
            flex-direction: column;
            margin-bottom: 3rem;
            margin-top: 3rem;
        }

        .accueil div:nth-child(4) h2 {
            order: 1;
            padding: 20px;
            width: 70vw;
        }

        .accueil div:nth-child(4) img {
            order: 2;
            width: 40vw;
        }

        .accueil div:nth-child(4) p {
            order: 3;
            width: 80vw;
        }

        .accueil div:nth-child(5) {
            display: flex;
            flex-direction: column;
            margin-bottom: 3rem;

        }

        .accueil div:nth-child(5) h2 {
            order: 1;
            padding-bottom: 20px;
            width: 70vw;
        }

        .accueil div:nth-child(5) img {
            order: 2;
            width: 40vw;
        }

        .accueil div:nth-child(5) p {
            order: 3;
            width: 80vw;
        }

        .choixlangue div {
            right: 0.5rem;
            width: auto;
            top: -1.85rem;

        }

        .choixlangue img {
            width: 10vw;
        }



        @media screen and (max-width:425px) {
            .choixlangue div {
                right: 0.5rem;
                width: auto;

            }

            .choixlangue img {
                width: 10vw;
            }

            .accueil {
                height: 110vh;
            }

            .accueil h1 {
                padding-top: 4rem;
            }

            .accueil h5 {
                text-align: center;
            }

            .accueil button {
                width: 40vw;
            }

            .accueil p {
                font-size: 10px;
            }

            .accueil div:nth-child(4) img {
                height: 15vh;
                width: auto;
            }

            .accueil div:nth-child(5) img {
                height: 15vh;
                width: auto;
            }

            footer {
                height: 5vh;
            }
        }

        @media screen and (max-width:425px) and (max-height:667px) {
            main {
                margin-top: 7rem;
            }
        }



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
                    <p>
                        <?= $infos['nom_entreprise'] ?>
                    </p>
                </li>
                <li><button onclick="location.href = './accueil.php'" class="button_nav">
                        <?= htmlspecialchars("Accueil") ?>
                    </button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav">
                        <?= htmlspecialchars("Commander") ?>
                    </button></li>
                <li><button onclick="location.href = './contact.php'" class="button_nav">
                        <?= htmlspecialchars("Nous contacter") ?>
                    </button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav">
                            <?= htmlspecialchars("Back Office") ?>
                        </button></li>
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
        <nav style="display:none;" class="navang">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p>
                        <?= htmlspecialchars("Fouée't Moi") ?>
                </li>
                <li><button onclick="location.href = '#'" class="button_nav">
                        <?= htmlspecialchars("Home") ?>
                    </button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav">
                        <?= htmlspecialchars("Order") ?>
                    </button></li>
                <li><button onclick="location.href = ''" class="button_nav">
                        <?= htmlspecialchars("Contact us") ?>
                    </button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav">
                            <?= htmlspecialchars("Back Office") ?>
                        </button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) { ?>
                    <button onclick="location.href = 'profil.php'" class="image"><img
                            src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                <?php } else { ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect">
                            <?= htmlspecialchars("Connexion") ?>
                        </button></li>
                    <li><button onclick="location.href = './signin.php'" class="button_nav connect">
                            <?= htmlspecialchars("Inscription") ?>
                        </button></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="menu-container">

            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p>
                        <?= htmlspecialchars("Fouées du Terroir") ?>
                </li>
            </ul>
            <div class="menu-btn" id="menu-btn">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>


            <nav class="menu">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="index.php">Commander</a></li>
                    <li><a href="contact.php">Nous contacter</a></li>
                    <?php if (isset($_SESSION['email'])) { ?>
                        <li><a href="profil.php">Mon compte</a></li>
                    <?php } else { ?>
                        <li><a href="login.php">Connexion/Inscription</a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>


        <div class="choixlangue">
            <div class="français" style="display:none;"><img src="./assets/img/fr.webp"></div>
            <div class="anglais"><img src="./assets/img/Flag_of_Great_Britain__1707–1800_.svg.webp"></div>
        </div>

        <section class="accueil ang" style="display:none">
            <h1>
                <?= htmlspecialchars("Welcome on") ?>
                <?php echo htmlspecialchars($contenu['nom_entreprise'], ENT_QUOTES) ?>
            </h1>
            <button onclick="location.href = './index.php'">
                <?= htmlspecialchars("Order") ?>
            </button>
            <h5><?php echo $infos['adresse_entreprise'] ?></h5>
            <div>
                <img src="<?php echo htmlspecialchars($contenu['url_img1']) ?>" />

                <h2>
                    <?php echo htmlspecialchars($contenu['title1EN'], ENT_QUOTES) ?>
                </h2>
                <p>
                    <?php echo htmlspecialchars($contenu['texte1EN'], ENT_QUOTES) ?>
                </p>
            </div>
            <div>
                <h2>
                    <?php echo htmlspecialchars($contenu['title2EN'], ENT_QUOTES) ?>
                </h2>
                <img src="<?php echo htmlspecialchars($contenu['url_img2']) ?>" />
                <p>
                    <?php echo htmlspecialchars($contenu['texte2EN'], ENT_QUOTES) ?>
                </p>


            </div>

        </section>
        <section class="accueil fra">
            <h1>
                <?= htmlspecialchars("Bienvenue sur") ?>
                <?php echo htmlspecialchars($contenu['nom_entreprise'], ENT_QUOTES) ?>
            </h1>
            <button onclick="location.href = './index.php'">
                <?= htmlspecialchars("Commander") ?>
            </button>
            <h5>
                <i class="fa-solid fa-location-dot"></i>
                <?php echo $infos['adresse_entreprise'] ?>
            </h5>

            <div>
                <img src="<?php echo htmlspecialchars($contenu['url_img1']) ?>" />

                <h2>
                    <?php echo htmlspecialchars($contenu['title1'], ENT_QUOTES) ?>
                </h2>
                <p>
                    <?php echo htmlspecialchars($contenu['texte1'], ENT_QUOTES) ?>
                </p>

            </div>
            <div>

                <h2>
                    <?php echo htmlspecialchars($contenu['title2'], ENT_QUOTES) ?>
                </h2>
                <img src="<?php echo htmlspecialchars($contenu['url_img2']) ?>" />
                <p>
                    <?php echo htmlspecialchars($contenu['texte2'], ENT_QUOTES) ?>
                </p>



            </div>

        </section>
    </main>
    <script src="./assets/js/functions.js">
    </script>
    <script>
        document.getElementById("menu-btn").addEventListener("click", function () {
            this.classList.toggle("open");
            var mainContent = document.querySelector("main");
            if (this.classList.contains("open")) {
                mainContent.style.display = "none";
            } else {
                mainContent.style.display = "block";
            }
        });
    </script>

</body>
<?php
echo footer();
?>

</html>