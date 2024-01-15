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
    
    $infos = "SELECT * FROM settings";
    $infos = $dbh->query($infos);
    $infos = $infos->fetch();

    // si on a un utilisateur
    if ($user) {
        // on génère un nouveau token
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
        $token = getRandomStringRandomInt();

        // on met à jour le token
        $sql = "UPDATE users SET mailverif = :token WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['token' => $token, 'id_user' => $user['id_user']]);
        // on envoie le mail
        send_activation_email($email, $token);

        $success = 'Un nouveau mail de confirmation vous a été envoyé';
    } else {
        $error = 'Le mail n\'existe pas';
    }
}

echo head('Accueil');
?>

<body>
    <main>
        <section class="form">
            <h1>Un mail vient d'être envoyé à <?= $_SESSION['email'] ?></h1>
            <p>Pour vérifier votre mail, cliquer sur le lien dans le mail que vous avez reçu.</p>
            <p>En cas de problème, contactez-nous !</p>
            <form method="post">
                <input type="submit" value="Renvoyer un mail">
            </form>
        </section>

    </main>


</body>

</html>