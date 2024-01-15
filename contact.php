<?php
require './bootstrap.php';
session_start();
echo head('Page contact');
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $message = $_POST['message'];

    $messagesr = "INSERT INTO messages (nom, prenom, email, tel, message) values (:nom, :prenom, :email, :telephone, :message)";
    $stmt = $dbh->prepare($messagesr);
    $stmt->execute([
        'prenom' => $prenom,
        'nom' => $nom,
        'email' => $email,
        'telephone' => $telephone,
        'message' => $message

    ]);
    header('Location: accueil.php');
    exit();
}





?>
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
                    <li><button onclick="location.href = './login.php'" class="button_nav connect">
                            <?= htmlspecialchars("Se connecter") ?>
                        </button></li>
                    <li><button onclick="location.href = './signin.php'" class="button_nav connect">
                            <?= htmlspecialchars("S'inscrire") ?>
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
                    <li><a href="accueil.php">Accueil</a></li>
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
    <style>
        body {
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form {
            margin-top: 15px;
            width: 40vw;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        p {
            padding: 10px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: none;
        }

        main {
            display: flex;
        }

        .form img {
            width: 15%;
            margin-bottom: 1rem;
        }

        i {
            font-size: 30px;
            padding-right: 20px;
        }

        main section:nth-child(2) {
            align-items: start;
        }

        .form p {
            text-align: justify;
            display: flex;
            align-items: center;
            font-size: 18px;
        }

        .bar {
            width: 100%;
            height: 2px;
            background-color: black;
        }

        @media only screen and (max-width: 768px) {
            .form {
                width: 90vw;
            }

            .container {
                margin-bottom: 0px;
            }

            .form p {
                text-align: center;
            }
        }
    </style>
    <main class="respContact">
        <section class="form">
            <div class="container">
                <h2>Contactez-nous</h2>

                <form action="" method="post">
                    <img src="./assets/img/FOUEE2.png" alt="">
                    <label for="nom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" required>

                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>

                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>

                    <label for="number">Numéro de téléphone :</label>
                    <input type="number" id="numero" name="telephone" required>

                    <label for="message">Message :</label>
                    <textarea id="message" name="message" rows="4" required></textarea>

                    <button type="submit" class="actions">Envoyer</button>
                </form>
            </div>

        </section>
        <section class="form">
            <p>Pour toute information complémentaire, posez-nous vos questions, et nous serons ravis de vous répondre.
            </p>
            <div class="bar"></div>
            <p><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                <?php echo $infos['nom_entreprise'] ?>
            </p>
            <p>
                <i class="fa-solid fa-location-dot"></i>
                <?php echo $infos['adresse_entreprise'] ?>
            </p>
            <p>
                <i class="fa-solid fa-phone"></i>
                <?php echo $infos['tel'] ?>
            </p>
            <p>
                <i class="fa-solid fa-envelope"></i>
                <?php echo $infos['email'] ?>
            </p>

        </section>
    </main>

</body>
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

</html>
<?php
echo footer();
?>
</body>