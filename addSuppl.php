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

echo head('Ajouter un supplément');
$id_suppl = isset($_GET['id_suppl']) ? htmlspecialchars($_GET['id_suppl']) : '';

?>

<body>
  <main>
    <div class="btn-retour">
      <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <section class="form">
      <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET') { ?>
          <h1>Ajouter un supplément</h1>
          <form action="" method="post">
            <div class="grid">
              <div>
                <div>
                  <label for="nom">Nom du supplément:</label>
                  <input name="nom" id="nom" type="text" required>
                </div>
                <div>
                  <label for="prix">Prix du supplément</label>
                  <input name="prix" id="prix" type="number" required>
                </div>
              </div>
              <div>
                <button type="submit" class="actions">Ajouter</button>
                <a href="indexBO.php" class="actions">Annuler</a>
              </div>
              
          </form>
    </section>
  </main>

  <?php
        } else {
          // En mode POST, il faut enregistrer les données du formulaire dans la base

          // 1. Récupérer les données du formulaire
          $nom = htmlspecialchars(trim($_POST['nom']));
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
            $sql = 'INSERT INTO supplements(`nom`,`prix`)
                VALUES (:nom, :prix)';
            // Exécuter
            $sth = $dbh->prepare($sql);
            $sth->execute([
              'nom' => $nom,
              'prix' => $prix,
            ]);

            // 3. Après l'insertion, retourner sur la page d'accueil avec un message
            header('Location: indexBO.php');
            exit;
          }
        } ?>
</div>

</body>

</html>