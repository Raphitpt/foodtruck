<?php
session_start();
if (isset($_SESSION['email'])) {
    require 'bootstrap.php';

    echo head('Modifier un plat');
    ?>

    <body>
        <div class="container">
            <?php
            if (isGetMethod()) {
                // identifiant du plat
                $id_plat = (int)$_GET['id_plat'];
                // récupérer les informations sur le plat
                $sql = 'SELECT *
                        FROM plats
                        WHERE plats.id_plat = :id_plat';
                $stmt = $dbh->prepare($sql);
                $stmt->execute([
                    'id_plat' => $id_plat,
                ]);
                $plats = $stmt->fetch();
                ?>
                <h1>Modifier un plat</h1>
                <form action="" method="post">
                    <input type="hidden" name="id_plat" value="<?php echo $plats['id_plat'] ?>">
                    <input type="hidden" name="id_categorie" value="<?php echo $plats['id_categorie'] ?>">
                    <div class="grid">
                        <div>
                            <div>
                                <label for="nom">Nom du plat:</label>
                                <input name="nom" id="nom" type="text" required value="<?php echo $plats['nom'] ?>">
                            </div>
                            <div>
                                <label for="composition">Compostion du plat</label>
                                <input name="composition" id="composition" type="number"required value="<?php echo $plats['composition'] ?>">
                            </div>
                            <div>
                                <label for="prix">Prix du plat </label>
                                <input name="prix" id="prix"  minlength="1" maxlength="2" type="number" required value="<?php echo $plats['prix'] ?>">
                            </div>
                        </div>
                    </div>
                    <button type="submit">Appliquer</button>
                    <a href="indexBO.php">Annuler</a>
                </form>
            <?php
            } else {
                // en mode POST, il faut enregistrer les données du formulaire dans la base

                // 1. récupérer les données du formulaire
                $id_plat =  trim($_POST['id_plat']);
                $id_categorie =  trim($_POST['id_categorie']);
                $nom = htmlspecialchars(trim($_POST['nom']));
                $composition = htmlspecialchars(trim($_POST['composition']));
                $prix = htmlspecialchars(trim($_POST['prix']));

                $errors = [];

                // // Valider le format de poids
                // if (filter_var($poids_rec, FILTER_VALIDATE_FLOAT) === false) {
                //   $errors[] = "Format de poids incorrect.";
                // }

                // // Valider le format de nombre de hausses
                // if (filter_var($nb_hausse_rec, FILTER_VALIDATE_INT) === false) {
                //   $errors[] = "Format de nombre de hausses incorrect.";
                // }

                // // Valider le format de poids (nombre entier ou décimal)
                // if (!preg_match('/^\d+(\.\d+)?$/', $poids_rec)) {
                //   $errors[] = "Veuillez entrer un poids de récolte valide (nombre entier ou décimal).";
                // }

                // // Valider le format de nombre de hausses (nombre entier)
                // if (!preg_match('/^\d+$/', $nb_hausse_rec)) {
                //   $errors[] = "Veuillez entrer un nombre de hausses valide (nombre entier).";
                // }

                if (count($errors) > 0) {
                  foreach ($errors as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                  }

                  // 2. construire le SQL de la requête préparée de modification
                  $sql = 'UPDATE plats SET nom = :nom, composition = :composition, prix = :prix WHERE id_plat = :id_plat';
                  // exécuter
                  $stmt = $dbh->prepare($sql);
                  $stmt->execute([
                      'nom' => $nom,
                      'composition' => $composition,
                      'prix' => $prix,
                  ]);
                  // 3. après la modification, retourner sur la page index_recolte.php des récoltes de la ruche sélectionnée
                  header('Location: indexBO.php');
                }
            }
            ?>

        </div>
    </body>

    </html>
<?php
} else {
    header('location: login.php');
}
?>
