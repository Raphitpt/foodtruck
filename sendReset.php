<?php
require './bootstrap.php';
session_start();
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email'])) {
    function getRandomStringRandomInt($length = 16)
    {
        $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($stringSpace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $stringSpace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
    $email = $_POST['email'];
    $reset_pass = getRandomStringRandomInt();

    $sql_verif = 'SELECT id_user FROM users WHERE email = :email';
    $verif = $dbh->prepare($sql_verif);
    $verif->bindValue(':email', $email);
    $verif->execute();
    $verif = $verif->fetch();

    if (!empty($verif)) {
        $sql_insert = "UPDATE users SET reset_pass = :reset_pass WHERE email = :email";
        $insert = $dbh->prepare($sql_insert);
        $insert->bindValue(':reset_pass', $reset_pass);
        $insert->bindValue(':email', $email);
        $insert->execute();


        $subject = "Votre mot passe a été réinitialisé";
        $message = "https://rtiphonet.fr/foodtruck/resetpass.php?email=" . $email . "&reset_pass=" . $reset_pass . "";



        // declare variable
        $headers = "Reply-To: Fouée Et Moi <rtiphonet@gmail.com>\r\n";

        $headers .= "Return-Path: Fouée Et Moi <rtiphonet@gmail.com>\r\n";
        $headers .= "From: Fouée Et Moi <rtiphonet@gmail.com>\r\n";
        $headers .= "Organization: Fouée Et Moi\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";



        mail($email, $subject, $message, $headers);
    }
    echo head('Mot de passe oublié');
?>

    <body>
        <nav>
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                    <p>Fouée't Moi</p>
                </li>
                <li><button onclick="location.href = './index.php'" class="button_nav">Accueil</button></li>
                <li><button onclick="location.href = ''" class="button_nav">Commander</button></li>
                <li><button onclick="location.href = ''" class="button_nav">Nous contacter</button></li>
            </ul>
            <ul class="nav_right">
                <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
            </ul>
        </nav>
        <main>
            <section class="form">
                <h1>Lien envoyé</h1>
            </section>
        </main>
    </body>

    </html>
<?php
} else {
    echo head('Mot de passe oublié');
?>

    <body>
        <nav>
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                    <p>Fouée't Moi</p>
                </li>
                <li><button onclick="location.href = './index.php'" class="button_nav">Accueil</button></li>
                <li><button onclick="location.href = ''" class="button_nav">Commander</button></li>
                <li><button onclick="location.href = ''" class="button_nav">Nous contacter</button></li>
            </ul>
            <ul class="nav_right">
                <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
            </ul>
        </nav>
        <main>
            <section class="form">
                <h1>Mot de passe oublié</h1>
                <form method="POST" action="">
                    <label for="username">Mail :</label>
                    <input type="text" name="email">
                    <br>
                    <br>
                    <input type="submit" name="envoi" value="Envoyer">
                </form>

            </section>
        </main>
    </body>

    </html>
<?php
}
?>