<?php
session_start();
require './bootstrap.php';

if (isset($_POST['submit'])) {
    // on récupère le mail
    $email = $_SESSION['email'];

    // on vérifie que le mail existe
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // si on a un utilisateur
    if ($user) {
        // on génère un nouveau token
        $token = bin2hex(random_bytes(32));

        // on met à jour le token
        $sql = "UPDATE users SET mailverif = :token WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['token' => $token, 'id_user' => $user['id_user']]);

        // on envoie le mail
        sendConfirmationMail($email, $token);

        $success = 'Un nouveau mail de confirmation vous a été envoyé';
    } else {
        $error = 'Le mail n\'existe pas';
    }
}

echo head('Accueil');
?>

<body>
    <h1>On t'a envoyé un mail mdr à <?= $_SESSION['email'] ?></h1>
    <p>Si tu veux confirmer ton inscription, clique sur le lien qu'on t'a envoyé</p>
    <p>Si tu veux pas, clique sur le lien qu'on t'a envoyé</p>
    <form method="post">
    <input type="submit" value="Renvoyer un mail">
    </form>

</body>
</html>