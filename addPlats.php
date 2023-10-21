<?php
session_start();
// if (isset($_SESSION['mail'])) {
require 'bootstrap.php';
echo head('Ajouter un plat');
$id_plat = isset($_GET['id_plat']) ? htmlspecialchars($_GET['id_plat']) : '';

?>

<body>
  <div class="container">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') { ?>
      <h1>Ajouter un plat</h1>
      <form action="" method="post">
        <div class="grid">
          <div>
            <div>
              <label for="nom">Nom du plat:</label>
              <input name="nom" id="nom" type="text" required>
            </div>
            <div>
              <label for="composition">Composition du plat</label>
              <input name="composition" id="composition" required>
            </div>
            <div>
              <label for="prix">Prix du plat</label>
              <input name="prix" id="prix" type="number" required>
            </div>
          </div>
          <button type="submit">Ajouter</button>
          <a href="indexBO.php">Annuler</a>
      </form>
    <?php
    } else {
      // En mode POST, il faut enregistrer les données du formulaire dans la base

      // 1. Récupérer les données du formulaire
      $nom = htmlspecialchars(trim($_POST['nom']));
      $composition = htmlspecialchars(trim($_POST['composition']));
      $prix = htmlspecialchars(trim($_POST['prix']));

      $errors = [];

      // You need to validate data and add errors if necessary.
      // For example, you can check if the fields are not empty and if the price is a valid number.

      if (count($errors) > 0) {
        foreach ($errors as $error) { ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php }
      } else {
        // 2. Construire le SQL de la requête préparée d'insertion
        $sql = 'INSERT INTO plats (`nom`, `composition`, `prix`)
                VALUES (:nom, :composition, :prix)';
        // Exécuter
        $sth = $dbh->prepare($sql);
        $sth->execute([
          'nom' => $nom,
          'composition' => $composition,
          'prix' => $prix,
        ]);

        // 3. Après l'insertion, retourner sur la page d'accueil avec un message
        header('Location: index_BO.php?success=1');
        exit;
      }
    } ?>
  </div>
</body>
</html>
<?php
// } else {
//   header('Location: index.php');
//   exit();
// }
?>
