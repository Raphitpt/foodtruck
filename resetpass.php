<?php
require './bootstrap.php';
session_start();

$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['password']) && !empty($_POST['password_confirm']) && !empty($_POST['email']) && !empty($_POST['reset_pass'])){
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $email = $_POST['email'];
    $reset_pass = $_POST['reset_pass'];

    if($password != $password_confirm){
        $_SESSION['erreur_motpasse'] = "Les mots de passe ne correspondent pas";
        header('Location: ./resetpass.php?email='.$email.'&reset_pass='.$reset_pass);
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql_update = 'UPDATE users SET passwd = :password, reset_pass = NULL WHERE email = :email AND reset_pass = :reset_pass';
        $update = $dbh->prepare($sql_update);
        $update->bindValue(':password', $password);
        $update->bindValue(':email', $email);
        $update->bindValue(':reset_pass', $reset_pass);
        $update->execute();
        header('Location: ./login.php');
        $_SESSION['erreur_motpasse'] = "Votre mot de passe a bien été réinitialisé";
    }
} else if (!empty($_GET['email']) && !empty($_GET['reset_pass'])) {
    $email = $_GET['email'];
    $reset_pass = $_GET['reset_pass'];

    $sql_verif = 'SELECT id_user FROM users WHERE email = :email AND reset_pass = :reset_pass';
    $verif = $dbh->prepare($sql_verif);
    $verif->bindValue(':email', $email);
    $verif->bindValue(':reset_pass', $reset_pass);
    $verif->execute();
    $verif = $verif->fetch();

    if (!empty($verif)) {
        $html = "";
        $html .= '<section class="form">';
        $html .= '<form action="./resetpass.php" method="post">';
        $html .= '<label>Mot de passe</label>';
        $html .= '<input type="password" name="password" class="form-control" required>';
        $html .= '<label>Confirmation du mot de passe</label>';
        $html .= '<input type="password" name="password_confirm" class="form-control" required>';
        $html .= '<input type="hidden" name="email" value="' . $email . '">';
        $html .= '<input type="hidden" name="reset_pass" value="' . $reset_pass . '">';
        $html .= '<button type="submit">Réinitialiser</button>';
        $html .= '</form>';
    } else {
        header('Location: ./index.php');
        $_SESSION['erreur_motpasse'] = "Une erreur est survenue lors de réinitilisation du mot de passe";
    }
} else {
    header('Location: ./index.php');
}

echo head('Réinitialiser le mot de passe');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

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
        <?php echo $html; ?>
    </main>

</body>

</html>