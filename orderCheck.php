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
            <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
        </ul>
    </nav>
    <main>
        <div class="btn-retour">
            <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        
        <section class="commandeTable">
            <h1>Commande en direct</h1>
            <table class="table" id="table" data-toggle="table" data-show-columns="true" data-search="true" auto-refresh="true">
                <thead>
                    <tr>
                        <th scope="col" data-sortable="true" data-field="id">Numéro de commande</th>
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
                            <td><?php echo $histo['nom'] . " " . $histo['prenom'] ?></td>
                            <td><?php echo $histo['date_commande']; ?></td>
                            <td><?php echo $histo['date_retrait']; ?></td>
                            <td><?php echo $histo['statut']; ?></td>
                            <td><?php echo $histo['commentaire']; ?></td>
                            <td><?php echo $histo['total']; ?>€</td>

                            <td><a href="./addPtsFid.php?id_commande=<?php echo $histo['id_commande']; ?>">Valider</a></td>
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
<script>
    // Fonction pour mettre à jour les données du tableau
    function mettreAJourTableau() {
        // Effectuer une nouvelle requête AJAX pour récupérer les données actualisées
        $.ajax({
            url: 'actualiserCommandes.php', // Créez un script PHP pour récupérer les nouvelles données
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