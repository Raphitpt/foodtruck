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

// Afficher l'en-tête de la page
echo head('Modifier les informations de l\'entreprise');
?>

<body>
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p><?php echo $infos['nom_entreprise'] ?></p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <li><button onclick="location.href = ''" class="button_nav">Nous contacter</button></li>
        </ul>
        <ul class="nav_right">
            <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
        </ul>
    </nav>
    <main id="editEntr">
        <section>
            <a href="infosEntreprise.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
            <div class="form">
                <?php
                // Vérifier si la requête est de type GET
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                ?>
                    <h1>Modifier les informations de l'entreprise</h1>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="">
                            <div>
                                <h2>Identité de l'entreprise :</h2>
                                <div>
                                    <label for="nom_entreprise">Nom de l'entreprise</label>
                                    <input name="nom_entreprise" id="nom_entreprise" type="text" required value="<?php echo $infos['nom_entreprise'] ?>">
                                </div>
                                <div>
                                    <label for="adresse_entreprise">Adresse de l'entreprise</label>
                                    <input name="adresse_entreprise" id="adresse_entreprise" value="<?php echo $infos['adresse_entreprise'] ?>">
                                </div>
                                <div>
                                    <label for="fichier_logo">Logo</label>
                                    <input name="fichier_logo" id="fichier_logo" type="file">
                                </div>
                                <h2>Horaires d'ouverture/fermeture :</h2>
                                <div>
                                    <?php foreach ($horaires as $horaire) { ?>
                                        <div>
                                            <label for="<?php echo $horaire['Jour']; ?>"><?php echo $horaire['Jour']; ?></label>
                                            <input name="HeureOuverture_<?php echo $horaire['Jour']; ?>" id="HeureOuverture_<?php echo $horaire['Jour']; ?>" type="text" required value="<?php echo $horaire['HeureOuverture']; ?>">
                                            <input name="HeureFermeture_<?php echo $horaire['Jour']; ?>" id="HeureFermeture_<?php echo $horaire['Jour']; ?>" type="text" required value="<?php echo $horaire['HeureFermeture']; ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <button type="submit" class="actions">Appliquer</button>
                            <button type="button" class="actions"><a href="infosEntreprise.php">Annuler</a></button>
                        </div>

                    </form>

                <?php
                } else {
                    // En mode POST, enregistrer les données du formulaire dans la base de données

                    // Récupérer les données du formulaire
                    $nom_entreprise = isset($_POST['nom_entreprise']) ? htmlspecialchars($_POST['nom_entreprise']) : '';
                    $adresse_entreprise = isset($_POST['adresse_entreprise']) ? htmlspecialchars($_POST['adresse_entreprise']) : '';
                    $fichier_logo = isset($_FILES['fichier_logo']) ? $_FILES['fichier_logo'] : '';

                    // Gestion du fichier logo
                    $chemin_logo = $infos['url_logo']; // Utilisez l'ancien chemin par défaut

                    if (!empty($fichier_logo['name'])) {
                        // Un nouveau fichier logo a été téléchargé
                        // Spécifiez le dossier de destination pour le fichier téléchargé
                        $dossier_destination = './assets/img/';

                        // Générez un nom unique pour le fichier
                        $nom_fichier_logo = uniqid() . '_' . $fichier_logo['name'];

                        // Construisez le chemin complet du fichier sur le serveur
                        $chemin_logo = $dossier_destination . $nom_fichier_logo;

                        // Déplacez le fichier téléchargé vers le dossier de destination
                        move_uploaded_file($fichier_logo['tmp_name'], $chemin_logo);
                    }

                    // Mettre à jour les informations de l'entreprise avec le chemin du fichier logo
                    $updateInfosSql = 'UPDATE settings SET nom_entreprise = :nom_entreprise, adresse_entreprise = :adresse_entreprise, url_logo = :url_logo';
                    $updateInfosStmt = $dbh->prepare($updateInfosSql);
                    $updateInfosStmt->execute([
                        'nom_entreprise' => $nom_entreprise,
                        'adresse_entreprise' => $adresse_entreprise,
                        'url_logo' => $chemin_logo,
                    ]);

                    // Mettre à jour les horaires de l'entreprise
                    foreach ($horaires as $horaire) {
                        $jour = $horaire['Jour'];
                        $heureOuverture = htmlspecialchars($_POST["HeureOuverture_$jour"]);
                        $heureFermeture = htmlspecialchars($_POST["HeureFermeture_$jour"]);

                        $updateHoraireSql = 'UPDATE planning SET HeureOuverture = :heureOuverture, HeureFermeture = :heureFermeture WHERE Jour = :jour';
                        $updateHoraireStmt = $dbh->prepare($updateHoraireSql);
                        $updateHoraireStmt->execute([
                            'heureOuverture' => $heureOuverture,
                            'heureFermeture' => $heureFermeture,
                            'jour' => $jour,
                        ]);
                    }

                    // Rediriger vers la page indexBO.php après la modification
                    header('Location: infosEntreprise.php');
                }


                ?>
            </div>
        </section>

    </main>

</body>

</html>