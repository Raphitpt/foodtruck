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
?>

<body>
    <header>
        <nav class="navfr">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p><?= htmlspecialchars("Fouée't Moi") ?></p>
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
</body>