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
                header('Location: index.php');
                exit();
            }
        } else {
            echo "Votre mot de passe ou pseudo est incorrect";
        }
    } else {
        echo "Veuillez compléter les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/css/style.css" rel="stylesheet">
    <title>Connexion</title>
</head>

<body>
    <h1>Connexion</h1>
    <form method="POST" action="">
        <label for="username">Mail :</label>
        <input type="text" name="email">
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="mdp">
        <br><br>
        <input type="submit" name="envoi" value="Envoyer">
    </form>
</body>

</html>