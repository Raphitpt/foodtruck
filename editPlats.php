<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    exit();
}

require 'bootstrap.php';
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

echo head('Modifier un plat');
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
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Identifiant du plat
        $id_plat = (int) $_GET['id_plat'];

        // Récupérer les informations sur le plat
        $sql = 'SELECT * FROM plats WHERE id_plat = :id_plat';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['id_plat' => $id_plat]);
        $plats = $stmt->fetch();
        ?>
        <main>
            <div class="btn-retour">
                <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <section class="form">
                <h1>Modifier un plat</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_plat" value="<?php echo $plats['id_plat'] ?>">
                    <input type="hidden" name="id_categorie" value="<?php echo $plats['id_categorie'] ?>">
                    <div class="grid">
                        <div>
                            <div>
                                <label for="nom">Nom du plat:</label>
                                <input name="nom" id="nom" type="text" required value="<?php echo $plats['nom'] ?>">
                            </div>
                            <div>
                                <label for="Image du plat">Image du plat</label>
                                <input name="image" id="image" type="file">
                                <input type="hidden" name="image_path" value="<?php echo $plats['image_plat'] ?>">
                            </div>
                            <div>
                                <label for="composition">Composition du plat</label>
                                <input name="composition" id="composition" value="<?php echo $plats['composition'] ?>">
                            </div>
                            <div>
                                <label for="prix">Prix du plat </label>
                                <input name="prix" id="prix" type="number" required value="<?php echo $plats['prix'] ?>">
                            </div>
                            <div>
                                <label for="id_categorie">Sucré ou Salé:</label>
                                <select name="id_categorie">
                                    <option value="1" <?php if ($plats['id_categorie'] == 1)
                                        echo 'selected'; ?>>Sucrée</option>
                                    <option value="2" <?php if ($plats['id_categorie'] == 2)
                                        echo 'selected'; ?>>Salée</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="actions">Appliquer</button>
                    <a href="indexBO.php" class="actions">Annuler</a>
                </form>
            </section>
        </main>
        <?php
    } else {
        // En mode POST, il faut enregistrer les données du formulaire dans la base
    
        // Récupérer les données du formulaire
        $id_plat = (int) $_POST['id_plat'];
        $id_categorie = (int) $_POST['id_categorie'];
        $nom = htmlspecialchars($_POST['nom']);
        $composition = htmlspecialchars($_POST['composition']);
        $prix = (float) $_POST['prix'];

        // Vérifier si un nouveau fichier image est spécifié
        $image_path = $_POST['image_path'];
        if (!empty($_FILES['image']['name'])) {
            // Supprimer l'ancienne image si elle existe
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            // Télécharger la nouvelle image
            $dossier_destination = './assets/img/'; // Mettez le chemin vers le dossier où vous souhaitez stocker les images
            $nom_fichier_image = uniqid() . '_' . $_FILES['image']['name'];
            $image_path = $dossier_destination . $nom_fichier_image;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                // Image téléchargée avec succès
            } else {
                echo "Erreur lors du téléchargement du fichier.";
                exit();
            }
        }

        // Mettre à jour la base de données avec les nouvelles informations
        $sql = 'UPDATE plats SET nom = :nom, composition = :composition, prix = :prix, image_plat = :image_path, id_categorie = :id_categorie WHERE id_plat = :id_plat';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'composition' => $composition,
            'prix' => $prix,
            'image_path' => $image_path,
            'id_plat' => $id_plat,
            'id_categorie' => $id_categorie,
        ]);

        // Rediriger après la modification
        header('Location: indexBO.php');
    }
    ?>
</body>

</html>