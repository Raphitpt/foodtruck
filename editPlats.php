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

<body>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Change 'isGetMethod()' to '$_SERVER['REQUEST_METHOD'] === 'GET''
            // Identifiant du plat
            $id_plat = (int)$_GET['id_plat'];
            // Récupérer les informations sur le plat
            $sql = 'SELECT *
                        FROM plats
                        WHERE id_plat = :id_plat';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'id_plat' => $id_plat,
            ]);
            $plats = $stmt->fetch();
        ?>
            <main>
                <div class="btn-retour">
                    <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <section class="form">
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
                                    <label for="composition">Composition du plat</label>
                                    <input name="composition" id="composition" value="<?php echo $plats['composition'] ?>">
                                </div>
                                <div>
                                    <label for="prix">Prix du plat </label>
                                    <input name="prix" id="prix" type="number" required value="<?php echo $plats['prix'] ?>">
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
            $id_plat = (int)$_POST['id_plat']; // You can keep this as an integer
            $id_categorie = (int)$_POST['id_categorie']; // You can keep this as an integer
            $nom = htmlspecialchars($_POST['nom']);
            $composition = htmlspecialchars($_POST['composition']);
            $prix = (float)$_POST['prix']; // Cast to float for decimal values

            // 2. Construire le SQL de la requête préparée de modification
            $sql = 'UPDATE plats SET nom = :nom, composition = :composition, prix = :prix WHERE id_plat = :id_plat';
            // Exécuter
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'nom' => $nom,
                'composition' => $composition,
                'prix' => $prix,
                'id_plat' => $id_plat,
            ]);
            // 3. Après la modification, retourner sur la page indexBO.php
            header('Location: indexBO.php');
        }
        ?>
</body>

</html>