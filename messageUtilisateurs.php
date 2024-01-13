<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

echo head('Messages des utilisateurs');

$messages = "SELECT * FROM messages";
$messages = $dbh->query($messages);
$messages = $messages->fetchAll();

$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

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
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p>Fouée't Moi</p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
            <?php endif; ?>
        </ul>
        <ul class="nav_right">
            <?php if (isset($_SESSION['email'])) : ?>
                <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
            <?php else : ?>
                <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
            <?php endif; ?>
        </ul>
    </nav>
    <main>
        <div class="btn-retour">
            <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <section class="commandeTable">
            <h1>Messages des utilisateurs</h1>
            <table class="table" id="table" data-toggle="table" data-show-columns="true" data-search="true" auto-refresh="true">
                <thead>
                    <tr>
                        <th scope="col" data-sortable="true" data-field="id">Message n°</th>
                        <th scope="col">Nom/Prénom de l'utilisateur</th>
                        <th scope="col">Email de l'utilisateur</th>
                        <th scope="col">Message</th>
                        <th scope="col">Date</th>
                        <th scope="col">Supprimer</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message) : ?>
                        <tr>
                            <td><?= $message['id_message'] ?></td>
                            <td><?= $message['nom'] . " " . $message['prenom'] ?></td>
                            <td><?= $message['email'] ?></td>
                            <td><?= $message['message'] ?></td>
                            <td><?= $message['date_message'] ?></td>
                            <td><a href="suppressionMessages.php?id_message=<?= $message['id_message'] ?>" class="btn btn-danger">Supprimer</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>


        </section>
    </main>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
<script>
    // Fonction pour mettre à jour les données du tableau
    function mettreAJourTableau() {
        // Effectuer une nouvelle requête AJAX pour récupérer les données actualisées
        $.ajax({
            url: 'messageUtilisateurs.php', // Créez un script PHP pour récupérer les nouvelles données
            success: function(nouvellesDonnees) {
                // Mettez à jour le contenu du corps du tableau avec les nouvelles données
                $('#table tbody').html(nouvellesDonnees);
            },
            error: function() {
                console.log('Erreur lors de la mise à jour des données du tableau.');
            }
        });
    }

    // Mettre à jour le tableau toutes les 10sec (30000 millisecondes)
    setInterval(mettreAJourTableau, 10000);
</script>
</html>