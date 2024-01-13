<?php

require 'bootstrap.php';
session_start();

$email = $_SESSION['email'];
$recupId = 'SELECT id_user FROM users where email = :email';
$user = $dbh->prepare($recupId);
$user->execute([
    'email' => $email,
]);
$recupId = $user->fetch();
$id = $recupId['id_user'];

echo head('Historique de commande');

$hist = "SELECT * FROM commandes inner join users on commandes.id_user = users.id_user where users.id_user = :id";
$user = $dbh->prepare($hist);
$user->execute([
    'id' => $id,
]);
$hist = $user->fetchAll();


$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();


if (isset($_SESSION['email'])) {
    $photo = "SELECT * FROM users where email = :email";
    $stmt = $dbh->prepare($photo);
    $stmt->execute([
        'email' => $email,
    ]);
    $photo = $stmt->fetch();
}


?>

<body>

    <main>
        <header>
            <nav class="navfr">
                <ul class="nav_left">
                    <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                        <p><?= $infos['nom_entreprise'] ?></p>
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
        <div class="btn-retour">
            <a href="index.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <section class="commandeTable">
            <h1>Historique de commande</h1>
            <table class="table" id="table" data-toggle="table" data-show-columns="true" data-search="true" auto-refresh="true">
                <thead>
                    <tr>
                        <th scope="col" data-sortable="true" data-field="id">Commande n°</th>
                        <th scope="col">Détail de la commande</th>
                        <th scope="col">Nom/Prénom du client</th>
                        <th scope="col">Date de commande</th>
                        <th scope="col">Date de retrait</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Commentaire</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hist as $histo) { ?>
                        <tr>
                            <td><?php echo $histo['id_commande']; ?></td>
                            <?php $details = json_decode($histo['detail_commande'], true);
                            echo "<td><ul>";
                            foreach ($details as $detail) {
                                echo "<li>{$detail['nom']} x {$detail['quantite']}</li>";
                            } ?>
                            <td><?php echo $histo['nom'] . " " . $histo['prenom'] ?></td>
                            <td><?php echo $histo['date_commande']; ?></td>
                            <td><?php echo $histo['date_retrait']; ?></td>
                            <td><?php echo $histo['statut']; ?></td>
                            <td><?php echo $histo['commentaire']; ?></td>
                            <td><?php echo $histo['total']; ?>€</td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </section>
    </main>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>

</html>