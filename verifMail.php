<!-- <?php
session_start();
require './bootstrap.php';

$email = $_GET['email']; // Use POST instead of GET

// on vérifie que le mail existe
$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $dbh->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if ($user['active'] == 1) {
    if ($_SESSION['order'] == "je viens de order") {
        $_SESSION['email'] = $email;
        header('Location: order.php');
        exit();
    } else {
        $_SESSION['email'] = $email;
        header('Location: accueil.php');
        exit();
    }
}

if (isset ($_POST['submit'])) {
    // on récupère le mail
    $email = $_POST['email']; // Use POST instead of GET

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
            <?php if (isset ($success)): ?>
                <h1>
                    <?= $success ?>
                </h1>
            <?php else: ?>
                <?php if (isset ($error)): ?>
                    <h1>
                        <?= $error ?>
                    </h1>
                <?php endif; ?>
                <p>Pour vérifier votre mail, cliquer sur le lien dans le mail que vous avez reçu.</p>
                <p>En cas de problème, contactez-nous !</p>
                <form method="post">
                    <input type="hidden" name="email" value="<?= $_GET['email'] ?>">
                    <input type="submit" name="submit" value="Renvoyer un mail">
                </form>
                <p>Une fois votre compte actif, revenez sur cette page pour continuer votre commande ! </p>
                <button class="actions" style="background:#e56d00; color:#fff;" onclick="window.location.reload()">Recharger la page</button>
            <?php endif; ?>
        </section>
    </main>
</body>

</html> -->

<?php
session_start();
require './bootstrap.php';

$email = $_GET['email']; // Utilisez POST au lieu de GET

// Vérifiez que l'e-mail existe
$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $dbh->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

// Vérifiez si l'utilisateur est déjà activé
if ($user['active'] == 1) {
    // Redirigez l'utilisateur selon vos besoins
    if ($_SESSION['order'] == "je viens de order") {
        header('Location: order.php');
        exit();
    } else {
        header('Location: accueil.php');
        exit();
    }
}

// Traitement de la demande de renvoi du mail de confirmation
if (isset ($_POST['submit'])) {
    // Récupérez l'e-mail à partir du formulaire
    $email = $_POST['email']; // Utilisez POST au lieu de GET

    // Vérifiez que l'e-mail existe
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    $infos = "SELECT * FROM settings";
    $infos = $dbh->query($infos);
    $infos = $infos->fetch();

    // Si l'utilisateur existe
    if ($user) {
        // Générez un nouveau token
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

        // Mettez à jour le token dans la base de données
        $sql = "UPDATE users SET mailverif = :token WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['token' => $token, 'id_user' => $user['id_user']]);

        // Envoyez le nouveau mail de confirmation
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
            <?php if (isset ($success)): ?>
                <h1>
                    <?= $success ?>
                </h1>
            <?php else: ?>
                <?php if (isset ($error)): ?>
                    <h1>
                        <?= $error ?>
                    </h1>
                <?php endif; ?>
                <p>Pour vérifier votre mail, cliquez sur le lien dans le mail que vous avez reçu.</p>
                <p>En cas de problème, contactez-nous !</p>
                <form method="post">
                    <input type="hidden" name="email" value="<?= $_GET['email'] ?>">
                    <input type="submit" name="submit" value="Renvoyer un mail">
                </form>
                <p>Une fois votre compte actif, revenez sur cette page pour continuer votre commande ! </p>
                <button class="actions" style="background:#e56d00; color:#fff;" onclick="window.location.reload()">Recharger
                    la page</button>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>