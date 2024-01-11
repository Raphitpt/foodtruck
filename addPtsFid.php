<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

echo head('Points de fidélité');
$infos = "SELECT * FROM commandes inner join users on commandes.id_user = users.id_user WHERE statut = 'En cours'";
$infos = $dbh->query($infos);
$infos = $infos->fetch();


$users = "SELECT * FROM users";
$users = $dbh->query($users);
$users = $users->fetchAll();


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
        <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        <section class="commandeTable">
            <h1>Points de fidélité des clients</h1>
            <table class="table" id="table" data-toggle="table" data-show-columns="true" data-search="true" auto-refresh="true">
                <thead>
                    <tr>
                        <th scope="col">Nom/Prénom du client</th>
                        <th scope="col" data-sortable="true">Points de fidélité</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['nom'] . " " . $user['prenom'] ?></td>
                            <td><?php echo $user['pts_fidelite']; ?></td>
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