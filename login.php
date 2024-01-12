<?php
session_start();
require 'bootstrap.php';

if (isset($_POST['envoi'])) {
    if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
        $email = htmlspecialchars($_POST['email']);
        $mdp = $_POST['mdp'];
        $_SESSION['email'] = $email;

        $recupUser = 'SELECT * FROM users WHERE email = :email';
        $stmt = $dbh->prepare($recupUser);
        $stmt->execute([
            'email' => $email,
        ]);
        $recupUser = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($recupUser && password_verify($mdp, $recupUser['passwd'])) {
            if ($recupUser['email'] == 'admin@gmail.com') {
                header('Location: indexBO.php');
                exit();
            } else {
                header('Location: accueil.php');
                exit();
            }
        } else {
            echo "Votre mot de passe ou pseudo est incorrect";
        }
    } else {
        echo "Veuillez compléter les champs.";
    }
}
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();
echo head('Connexion');
?>



<body>
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
    <main>
        <div class="btn-retour">
            <a href="index.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <section class="form">
            <h1>Connectez-vous</h1>
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
                <input type="submit" name="envoi" value="Envoyer">
            </form>

        </section>
    </main>



</body>

</html>