<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    exit();
}

require 'bootstrap.php';

// Récupérer les informations de l'entreprise
$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();

// Récupérer les horaires de l'entreprise
$horairesQuery = "SELECT * FROM planning";
$horairesResult = $dbh->query($horairesQuery);
$horaires = $horairesResult->fetchAll();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $photo = "SELECT * FROM users where email = :email";
    $stmt = $dbh->prepare($photo);
    $stmt->execute([
        'email' => $email,
    ]);
    $photo = $stmt->fetch();
}


// Afficher l'en-tête de la page
echo head('Statistiques de vente par jour');
?>
<script>
    window.onload = function () {
        // Check screen width
        var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        // Define the minimum width for access
        var minWidth = 800;

        // Redirect if the screen width is smaller than the minimum width
        if (screenWidth < minWidth) {
            alert("Cette page est accessible uniquement sur des écrans larges avec une largeur supérieure à 800px. Veuillez utiliser un écran plus grand.");
            window.location.href = 'index.php'; // Redirect to the appropriate page
        }
    };
</script>
<body>
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p><?= $infos['nom_entreprise'] ?></p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
            <?php endif; ?>
        </ul>
        <ul class="nav_right">
            <?php if (isset($_SESSION['email'])) { ?>
                <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
            <?php } else { ?>
                <li><button onclick="location.href = './login.php'" class="button_nav connect"><?= htmlspecialchars("Se connecter") ?></button></li>
            <?php } ?>
        </ul>
    </nav>
    <main>
        <div class="btn-retour">
            <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <section class="commandeTable">
            <h1>Statistiques de vente par jour</h1>
            <table class="table" id="table" data-toggle="table" data-show-columns="true" data-search="true" auto-refresh="true">
                <thead>
                    <tr>
                        <th scope="col" data-sortable="true" data-field="id">Jour</th>
                        <th scope="col">Nombre de commandes</th>
                        <th scope="col">Total</th>
                        <th scope="col">Moyenne</th>
                        <th scope="col">Vente la plus élevée</th>
                        <th scope="col">Vente la plus faible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT date_commande AS jour, COUNT(*) AS nb_commandes, SUM(total) AS total, AVG(total) AS moyenne, MAX(total) AS max, MIN(total) AS min FROM commandes GROUP BY DAYNAME(date_commande)";
                    $stmt = $dbh->query($sql);
                    $stats = $stmt->fetchAll();
                    foreach ($stats as $stat) {
                    ?>
                        <tr>
                            <td><?= $stat['jour'] ?></td>
                            <td><?= $stat['nb_commandes'] ?></td>
                            <td><?= $stat['total'] ?>€</td>
                            <td><?= $stat['moyenne'] ?>€</td>
                            <td><?= $stat['max'] ?>€</td>
                            <td><?= $stat['min'] ?>€</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>