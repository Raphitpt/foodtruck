<?php
require './bootstrap.php';
session_start();

$id = 4;
$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();
echo head('Page d\'accueil');

$recupUser = 'SELECT * FROM users WHERE id_user = :id';
$stmt = $dbh->prepare($recupUser);
$stmt->execute([
    'id' => $id,
]);
$recupUser = $stmt->fetch();
$photo = $recupUser['photoprofil'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fichier_photo_profil = isset($_FILES['fichier_photo_profil']) ? $_FILES['fichier_photo_profil'] : '';
    $chemin_photo_profil = '';

    if (!empty($fichier_photo_profil['name'])) {
        $dossier_destination = './assets/img/';

        $nom_fichier_photo_profil = uniqid() . '_' . $fichier_photo_profil['name'];

        $chemin_photo_profil = $dossier_destination . $nom_fichier_photo_profil;
        if (move_uploaded_file($fichier_photo_profil['tmp_name'], $chemin_photo_profil)) {
            $updatePhotoProfilSql = 'UPDATE users SET photoprofil = :photoprofil WHERE id_user = :id_user';
            $updatePhotoProfilStmt = $dbh->prepare($updatePhotoProfilSql);
            $updatePhotoProfilStmt->execute([
                'photoprofil' => $chemin_photo_profil,
                'id_user' => $id,
            ]);

            if ($updatePhotoProfilStmt->rowCount() > 0) {
                echo 'La photo de profil a été mise à jour avec succès.';
                header("Location: profil.php");
                unlink($photo);
            }
        } else {
            echo 'Erreur lors du téléchargement du fichier.';
        }
    } else {
        echo 'Aucun fichier photo de profil n\'a été téléchargé.';
    }
}
?>

<body>
    <!-- ... Votre code HTML existant ... -->

    <h1>Profil de <?php echo $recupUser['nom'] . " " . $recupUser['prenom'] ?></h1>
    <img src="<?php echo $photo == NULL ? "./assets/img/grandprofilfb.jpg" : $photo; ?>" />
    <form action="" method="post" enctype="multipart/form-data">
        <label for="fichier_photo_profil">Modifier la photo de profil :</label>
        <input type="file" name="fichier_photo_profil" id="fichier_photo_profil">
        <input type="submit" value="Enregistrer">
    </form>
    <p><?php echo $recupUser['pts_fidelite'] ?></p>
    <p><?php echo $recupUser['email']; ?></p>
</body>

</html>