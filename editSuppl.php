<?php
session_start();
// if (isset($_SESSION['email'])) {
    require 'bootstrap.php';

    echo head('Modifier un supplément');
    ?>

    <body>
        <div class="container">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Change 'isGetMethod()' to '$_SERVER['REQUEST_METHOD'] === 'GET''
                // Identifiant du plat
                $id_suppl = (int)$_GET['id_suppl'];
                // Récupérer les informations sur le supplément
                $sql = 'SELECT *
                        FROM supplements
                        WHERE id_suppl = :id_suppl';
                $stmt = $dbh->prepare($sql);
                $stmt->execute([
                    'id_suppl' => $id_suppl,
                ]);
                $supplements = $stmt->fetch();
                ?>
                <h1>Modifier un plat</h1>
                <form action="" method="post">
                    <input type="hidden" name="id_suppl" value="<?php echo $supplements['id_suppl'] ?>">
                    <div class="grid">
                        <div>
                            <div>
                                <label for="nom">Nom du supplément:</label>
                                <input name="nom" id="nom" type="text" required value="<?php echo $supplements['nom'] ?>">
                            </div>
                            <div>
                                <label for="prix">Prix du supplément </label>
                                <input name="prix" id="prix" type="number" required value="<?php echo $supplements['prix'] ?>">
                            </div>
                        </div>
                    </div>
                    <button type="submit">Appliquer</button>
                    <a href="indexBO.php">Annuler</a>
                </form>
            <?php
            } else {
                // En mode POST, il faut enregistrer les données du formulaire dans la base

                // Récupérer les données du formulaire
                $id_suppl = (int)$_POST['id_suppl']; // You can keep this as an integer
                $nom = htmlspecialchars($_POST['nom']);
                $prix = (float)$_POST['prix']; // Cast to float for decimal values

                // 2. Construire le SQL de la requête préparée de modification
                $sql = 'UPDATE supplements SET nom = :nom, prix = :prix WHERE id_suppl = :id_suppl';
                // Exécuter
                $stmt = $dbh->prepare($sql);
                $stmt->execute([
                    'nom' => $nom,
                    'prix' => $prix,
                    'id_suppl' => $id_suppl,
                ]);
                // 3. Après la modification, retourner sur la page indexBO.php
                header('Location: indexBO.php');
            }
            ?>

        </div>
    </body>

    </html>
<?php
// } else {
//     header('location: login.php');
// }
?>
