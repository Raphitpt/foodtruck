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
$accueilQuery = "SELECT * FROM elements_accueil";
$accueilResult = $dbh->query($accueilQuery);
$accueil = $accueilResult->fetch();

$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();

// Afficher l'en-tête de la page
echo head('Modifier les informations de la page d\'accueil');
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
                <li><button onclick="location.href = './logout.php'" class="button_nav connect">Se déconnecter</button></li>
            <?php else : ?>
                <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
            <?php endif; ?>
        </ul>
    </nav>
    <main id="editEntr">
        <section>
            <div class="btn-retour">
                <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="form">
                <?php
                // Vérifier si la requête est de type GET
                if ($_SERVER['REQUEST_METHOD'] === 'GET') :
                ?>
                    <h1>Modifier les informations de la page d'accueil</h1>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div>
                            <h2>Contenus multilingues</h2>
                            <div>
                                <label for="nom_entreprise">Nom de l'entreprise</label>
                                <input name="nom_entreprise" id="nom_entreprise" type="text" required value="<?= htmlspecialchars($accueil['nom_entreprise']); ?>">
                            </div>
                            <div>
                                <label for="url_img1">Première image</label>
                                <input name="url_img1" id="url_img1" type="file">
                            </div>
                            <div>
                                <label for="url_img2">Seconde image</label>
                                <input name="url_img2" id="url_img2" type="file">
                            </div>
                            <h2>Contenu en français</h2>
                            <div>
                                <label for="title1">Premier titre</label>
                                <input name="title1" id="title1" value="<?= htmlspecialchars($accueil['title1']); ?>">
                            </div>
                            <div>
                                <label for="texte1">Premier texte</label>
                                <input name="texte1" id="texte1" value="<?= htmlspecialchars($accueil['texte1']); ?>">
                            </div>
                            <div>
                                <label for="title2">Deuxième titre</label>
                                <input name="title2" id="title2" value="<?= htmlspecialchars($accueil['title2']); ?>">
                            </div>
                            <div>
                                <label for="texte2">Deuxième texte</label>
                                <input name="texte2" id="texte2" value="<?= htmlspecialchars($accueil['texte2']); ?>">
                            </div>
                            <h2>Contenu en anglais</h2>
                            <div>
                                <label for="title1EN">Premier titre</label>
                                <input name="title1EN" id="title1EN" value="<?= htmlspecialchars($accueil['title1EN']); ?>">
                            </div>
                            <div>
                                <label for="texte1EN">Premier texte</label>
                                <input name="texte1EN" id="texte1EN" value="<?= htmlspecialchars($accueil['texte1EN']); ?>">
                            </div>
                            <div>
                                <label for="title2EN">Deuxième titre</label>
                                <input name="title2EN" id="title2EN" value="<?= htmlspecialchars($accueil['title2EN']); ?>">
                            </div>
                            <div>
                                <label for="texte2EN">Deuxième texte</label>
                                <input name="texte2EN" id="texte2EN" value="<?= htmlspecialchars($accueil['texte2EN']); ?>">
                            </div>
                            <div>
                                <button type="submit" class="actions">Appliquer</button>
                                <button type="button" class="actions"><a href="indexBO.php">Annuler</a></button>
                            </div>
                        </div>
                    </form>
                <?php
                else :
                    // En mode POST, enregistrez les données du formulaire dans la base de données

                    // Récupérez les données du formulaire
                    $nom_entreprise = isset($_POST['nom_entreprise']) ? htmlspecialchars($_POST['nom_entreprise']) : '';
                    $img1 = isset($_FILES['url_img1']) ? $_FILES['url_img1'] : '';
                    $img2 = isset($_FILES['url_img2']) ? $_FILES['url_img2'] : '';
                    $title1 = isset($_POST['title1']) ? htmlspecialchars($_POST['title1']) : '';
                    $texte1 = isset($_POST['texte1']) ? htmlspecialchars($_POST['texte1']) : '';
                    $title2 = isset($_POST['title2']) ? htmlspecialchars($_POST['title2']) : '';
                    $texte2 = isset($_POST['texte2']) ? htmlspecialchars($_POST['texte2']) : '';
                    $title1EN = isset($_POST['title1EN']) ? htmlspecialchars($_POST['title1EN']) : '';
                    $texte1EN = isset($_POST['texte1EN']) ? htmlspecialchars($_POST['texte1EN']) : '';
                    $title2EN = isset($_POST['title2EN']) ? htmlspecialchars($_POST['title2EN']) : '';
                    $texte2EN = isset($_POST['texte2EN']) ? htmlspecialchars($_POST['texte2EN']) : '';

                    // Gestion du changement des images
                    if ($img1['size'] > 0) {
                        // Vérifier si le fichier est une image
                        if (exif_imagetype($img1['tmp_name'])) {
                            // Définir le chemin de destination
                            $destination = './assets/img/' . $img1['name'];
                            // Déplacer le fichier
                            move_uploaded_file($img1['tmp_name'], $destination);
                            // Enregistrer le chemin dans la base de données
                            $url_img1 = $destination;
                        } else {
                            echo 'Le fichier n\'est pas une image';
                            exit(); // Ajout de cette ligne pour éviter l'exécution du code suivant en cas d'erreur
                        }
                    } else {
                        $url_img1 = $accueil['url_img1'];
                    }

                    if ($img2['size'] > 0) {
                        // Vérifier si le fichier est une image
                        if (exif_imagetype($img2['tmp_name'])) {
                            // Définir le chemin de destination
                            $destination = './assets/img/' . $img2['name'];
                            // Déplacer le fichier
                            move_uploaded_file($img2['tmp_name'], $destination);
                            // Enregistrer le chemin dans la base de données
                            $url_img2 = $destination;
                        } else {
                            echo 'Le fichier n\'est pas une image';
                            exit(); // Ajout de cette ligne pour éviter l'exécution du code suivant en cas d'erreur
                        }
                    } else {
                        $url_img2 = $accueil['url_img2'];
                    }

                    // Construire le SQL de la requête préparée de modification
                    $sql = 'UPDATE elements_accueil SET nom_entreprise = :nom_entreprise, url_img1 = :url_img1, url_img2 = :url_img2, title1 = :title1, texte1 = :texte1, title2 = :title2, texte2 = :texte2, title1EN = :title1EN, texte1EN = :texte1EN, title2EN = :title2EN, texte2EN = :texte2EN WHERE id = :id';

                    // Exécuter
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute([
                        'nom_entreprise' => $nom_entreprise,
                        'url_img1' => $url_img1,
                        'url_img2' => $url_img2,
                        'title1' => $title1,
                        'texte1' => $texte1,
                        'title2' => $title2,
                        'texte2' => $texte2,
                        'title1EN' => $title1EN,
                        'texte1EN' => $texte1EN,
                        'title2EN' => $title2EN,
                        'texte2EN' => $texte2EN,
                        'id' => $accueil['id'],
                    ]);

                    // Rediriger vers la page indexBO.php après la modification
                    header('Location: indexBO.php');
                    exit(); // Ajout de cette ligne pour éviter l'exécution du code suivant après la redirection
                endif;
                ?>
            </div>
        </section>
    </main>
</body>

</html>
