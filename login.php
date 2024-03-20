<?php
// session_start();
// require 'bootstrap.php';

// // Initialiser les messages d'erreur
// $errorMsg = '';

// if (isset ($_POST['envoi'])) {
//     if (!empty ($_POST['email']) && !empty ($_POST['mdp'])) {
//         $email = htmlspecialchars($_POST['email']);
//         $mdp = $_POST['mdp'];


//         $recupUser = 'SELECT * FROM users WHERE email = :email';
//         $stmt = $dbh->prepare($recupUser);
//         $stmt->execute([
//             'email' => $email,
//         ]);
//         $recupUser = $stmt->fetch(PDO::FETCH_ASSOC);



//         if ($recupUser && password_verify($mdp, $recupUser['passwd'])) {
//             if ($recupUser['active'] != 0) {
//                 $_SESSION['email'] = $email;
//                 if ($recupUser['email'] == 'admin@gmail.com') {
//                     header('Location: indexBO.php');
//                     exit();
//                 } else if ($_SESSION['order'] == "je viens de order") {
//                     header('Location: order.php');
//                     exit();
//                 } else {
//                     header('Location: accueil.php');
//                     exit();
//                 }
//             } else {
//                 // $errorMsg = "Veuillez activer votre compte";
//                 // header('Location: verifMail.php?email=' . $email);
//             }

//         } else {
//             // Mettre à jour le message d'erreur
//             $errorMsg = "Votre mot de passe ou pseudo est incorrect";
//         }
//     } else {
//         // Mettre à jour le message d'erreur
//         $errorMsg = "Veuillez compléter les champs.";
//     }
// }

// $infos = "SELECT * FROM settings";
// $infos = $dbh->query($infos);
// $infos = $infos->fetch();
// echo head('Connexion');

?>

<?php
session_start();
require 'bootstrap.php';

// Initialiser les messages d'erreur
$errorMsg = '';

if (isset ($_POST['envoi'])) {
    if (!empty ($_POST['email']) && !empty ($_POST['mdp'])) {
        $email = htmlspecialchars($_POST['email']);
        $mdp = $_POST['mdp'];

        $recupUser = 'SELECT * FROM users WHERE email = :email';
        $stmt = $dbh->prepare($recupUser);
        $stmt->execute(['email' => $email]);
        $recupUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($recupUser && password_verify($mdp, $recupUser['passwd'])) {
            $_SESSION['email'] = $email;
            if ($recupUser['email'] == 'admin@gmail.com') {
                header('Location: indexBO.php');
                exit();
            } else if (isset ($_SESSION['order']) && $_SESSION['order'] == "je viens de order") {
                header('Location: order.php');
                exit();
            } else {
                header('Location: accueil.php');
                exit();
            }
        } else {
            // Mettre à jour le message d'erreur
            $errorMsg = "Votre mot de passe ou pseudo est incorrect";
        }
    } else {
        // Mettre à jour le message d'erreur
        $errorMsg = "Veuillez compléter les champs.";
    }
}

$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();
echo head('Connexion');
?>

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
                <?php if (isset ($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav">
                            <?= htmlspecialchars("Back Office") ?>
                        </button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset ($_SESSION['email'])) { ?>
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
                        <?= htmlspecialchars("Fouées du Terroir") ?>
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
                <?php if (isset ($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav">
                            <?= htmlspecialchars("Back Office") ?>
                        </button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset ($_SESSION['email'])) { ?>
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
                    <?php if (isset ($_SESSION['email'])) { ?>
                        <button onclick="location.href = 'profil.php'" class="image"><img
                                src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                    <?php } else { ?>
                        <li><a href="login.php">Connexion/Inscription</a></li>
                    <?php } ?>
                    <?php if (isset ($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>
                        <li><button onclick="location.href = 'indexBO.php'" class="button_nav">
                                <?= htmlspecialchars("Back Office") ?>
                            </button></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="btn-retour">
            <a href="accueil.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <section class="form">
            <h1>Connectez-vous</h1>
            <!-- Afficher le message d'erreur ici -->
            <?php if (!empty ($errorMsg)): ?>
                <p style="color: red;">
                    <?= $errorMsg ?>
                </p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="username">Mail :</label>
                <input type="text" name="email">
                <br>
                <label for="password">Mot de passe :</label>
                <input type="password" name="mdp">
                <br>
                <a href="./sendReset.php">Mot de passe oublié ?</a>
                <br>
                <br>
                <div>
                    <input type="submit" name="envoi" value="Envoyer">
                    <button class="actions"><a href="signin.php">S'inscire</a></button>
                </div>

            </form>

        </section>
    </main>
</body>

</html>