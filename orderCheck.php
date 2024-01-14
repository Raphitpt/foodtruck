<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

echo head('Commandes en cours');

$hist = "SELECT * FROM commandes inner join users on commandes.id_user = users.id_user WHERE statut = 'En cours'";
$hist = $dbh->query($hist);
$hist = $hist->fetchAll();

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
                <p>
                    <?= $infos['nom_entreprise'] ?>
                </p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>
                <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
            <?php endif; ?>
        </ul>
        <ul class="nav_right">
            <?php if (isset($_SESSION['email'])) { ?>
                <button onclick="location.href = 'profil.php'" class="image"><img
                        src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
            <?php } else { ?>
                <li><button onclick="location.href = './login.php'" class="button_nav connect">
                        <?= htmlspecialchars("Se connecter") ?>
                    </button></li>
            <?php } ?>
        </ul>
    </nav>
    <main>
        <div class="btn-retour">
            <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>

        <section class="commandeTable">
            <h1>Commande en direct</h1>
            <table class="table" id="table" data-toggle="table" data-pagination="true" data-show-columns="true" data-search="true"
                auto-refresh="true">
                <thead>
                    <tr>
                        <th scope="col" data-sortable="true" data-field="id">Numéro de commande</th>
                        <th scope="col">Détail de la commande</th>
                        <th scope="col">Nom/Prénom du client</th>
                        <th scope="col">Date de commande</th>
                        <th scope="col">Date de retrait</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Commentaire</th>
                        <th scope="col">Total</th>
                        <th scope="col">Valider</th>
                        <th scope="col">Supprimer</th>


                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hist as $histo) { ?>
                        <tr>
                            <td>
                                <?php echo $histo['id_commande']; ?>
                            </td>
                            <?php $details = json_decode($histo['detail_commande'], true);
                            echo "<td><ul>";
                            foreach ($details as $detail) {
                                echo "<li>{$detail['nom']} x {$detail['quantite']}</li>";
                                echo "<ul>";
                                foreach ($detail['supplements'] as $supplements) {
                                    echo "<li>{$supplements['name']}</li>";
                                }
                                echo "</ul>";
                            } ?>
                            <td>
                                <?php echo $histo['nom'] . " " . $histo['prenom'] ?>
                            </td>
                            <td>
                                <?php echo $histo['date_commande']; ?>
                            </td>
                            <td>
                                <?php echo $histo['date_retrait']; ?>
                            </td>
                            <td>
                                <?php echo $histo['statut']; ?>
                            </td>
                            <td>
                                <?php echo $histo['commentaire']; ?>
                            </td>
                            <td>
                                <?php echo $histo['total']; ?>€
                            </td>

                            <td><a href="./addPtsFid.php?id_commande=<?php echo $histo['id_commande']; ?>"
                                    class="actions">Valider</a></td>
                            <td><a href="./suppCommande.php?id_commande=<?php echo $histo['id_commande']; ?>"
                                    class="actionsSup">Supprimer</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </section>
    </main>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
<script>
    // Fonction pour mettre à jour les données du tableau
    function mettreAJourTableau() {
        // Effectuer une nouvelle requête AJAX pour récupérer les données actualisées
        $.ajax({
            url: 'actualiserCommandes.php', // Créez un script PHP pour récupérer les nouvelles données
            success: function (nouvellesDonnees) {
                // Mettez à jour le contenu du corps du tableau avec les nouvelles données
                $('#table tbody').html(nouvellesDonnees);
            },
            error: function () {
                console.log('Erreur lors de la mise à jour des données du tableau.');
            }
        });
    }

    // Mettre à jour le tableau toutes les 10sec (30000 millisecondes)
    setInterval(mettreAJourTableau, 10000);
</script>

</html>