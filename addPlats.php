<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
  // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
  echo "Vous n'êtes pas le bienvenu ici";
  exit();
}
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

echo head('Ajouter un plat');
$id_plat = isset($_GET['id_plat']) ? htmlspecialchars($_GET['id_plat']) : '';

?>

<body>
  <main>
    <div class="btn-retour">
      <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <section class="form">
      <?php
      if ($_SERVER['REQUEST_METHOD'] === 'GET') { ?>
        <h1>Ajouter un plat</h1>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="grid">
            <div>
              <div>
                <label for="nom">Nom du plat:</label>
                <input name="nom" id="nom" type="text" required>
              </div>
              <div>
                <label for="Image du plat">Image du plat</label>
                <input name="image" id="image" type="file">
              </div>
              <div>
                <label for="composition">Composition du plat</label>
                <input name="composition" id="composition">
              </div>
              <div>
                <label for="prix">Prix du plat</label>
                <input name="prix" id="prix" type="number" required>
              </div>
              <div>
                <label>
                  <input type="radio" name="choix" value="1">Sucrée
                </label>
                <br>
                <label>
                  <input type="radio" name="choix" value="2">Salée
                </label>
              </div>
            </div>
            <button type="submit" class="actions">Ajouter</button>
            <a href="indexBO.php" class="actions">Annuler</a>
        </form>
    </main>

    <?php
      } else {
        // En mode POST, il faut enregistrer les données du formulaire dans la base
      
        // 1. Récupérer les données du formulaire
        $nom = htmlspecialchars(trim($_POST['nom']));
        $composition = htmlspecialchars(trim($_POST['composition']));
        $prix = htmlspecialchars(trim($_POST['prix']));
        $id_categorie = $_POST['choix'];
        $image_path = $_POST['image_path'];


        $errors = [];

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
        if (count($errors) > 0) {
          foreach ($errors as $error) { ?>
        <p>
          <?php echo htmlspecialchars($error); ?>
        </p>
      <?php }
        } else {
          // 2. Construire le SQL de la requête préparée d'insertion
          $sql = 'INSERT INTO plats(`nom`, `composition`, `prix`,`id_categorie`,`image_plat`)
                VALUES (:nom, :composition, :prix, :id_categorie, :image_plat)';
          // Exécuter
          $sth = $dbh->prepare($sql);
          $sth->execute([
            'nom' => $nom,
            'composition' => $composition,
            'prix' => $prix,
            'id_categorie' => $id_categorie,
            'image_plat' => $image_path,
          ]);

          // 3. Après l'insertion, retourner sur la page d'accueil avec un message
          header('Location: indexBO.php');
          exit;
        }
      } ?>
  </section>




</body>

</html>