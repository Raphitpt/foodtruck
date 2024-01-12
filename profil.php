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
/*
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
                header("Location: accueil.php");
                unlink($photo);
            }
        } else {
            echo 'Erreur lors du téléchargement du fichier.';
        }
    }
}
if (isset($_POST['modifier_mot_de_passe']) && !empty($_POST['nouveau_mot_de_passe']) && !empty($_POST['confirmation_mot_de_passe'])) {
    $nouveau_mot_de_passe = isset($_POST['nouveau_mot_de_passe']) ? $_POST['nouveau_mot_de_passe'] : '';
    $confirmation_mot_de_passe = isset($_POST['confirmation_mot_de_passe']) ? $_POST['confirmation_mot_de_passe'] : '';

    if ($nouveau_mot_de_passe !== $confirmation_mot_de_passe) {
        echo 'Les champs du mot de passe ne correspondent pas.';
    } else {
        $hashed_password = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
        var_dump($hashed_password);
        $updateMotDePasseSql = 'UPDATE users SET passwd = :passwd WHERE id_user = :id_user';
        $updateMotDePasseStmt = $dbh->prepare($updateMotDePasseSql);
        $updateMotDePasseStmt->execute([
            'passwd' => $hashed_password,
            'id_user' => $id,
        ]);

        if ($updateMotDePasseStmt->rowCount() > 0) {
            echo 'Le mot de passe a été modifié avec succès.';
            header("Location: profil.php");
        } else {
            echo 'Erreur lors de la modification du mot de passe.';
        }
    }
}
*/
?>
<style>
    h1 {
        text-align: center;
    }

    img {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: auto;
        margin-right: auto;

    }

    div {
        display: in
    }
</style>

<body>

    <h1>Profil de <?php echo $recupUser['nom'] . " " . $recupUser['prenom'] ?></h1>
    <div>
        <img src="<?php echo $photo == NULL ? "./assets/img/grandprofilfb.jpg" : $photo; ?>" />
    </div>
    <form action="" method="post" enctype="multipart/form-data"><label for="fichier_photo_profil">Modifier la photo de profil :</label><input type="file" name="fichier_photo_profil" id="fichier_photo_profil"><input type="submit" value="Enregistrer"></form>
    <form action="" method="post"><label for="nouveau_mot_de_passe">Nouveau mot de passe :</label><input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" value=""><input type="password" name="confirmation_mot_de_passe" id="confirmnew" value=""><input type="submit" name="modifier_mot_de_passe"></form>
    <p><?php echo $recupUser['pts_fidelite'] ?></p>
    <p><?php echo $recupUser['email']; ?></p>
    <main>
        <div class="btn-retour">
            <a href="index.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <section class="form">
            <h1>Profil de <?php echo $recupUser['nom'] . " " . $recupUser['prenom'] ?></h1>
            <img src="<?php echo $photo == NULL ? "./assets/img/grandprofilfb.jpg" : $photo; ?>" />
            <form action="" method="post" enctype="multipart/form-data">
                <label for="fichier_photo_profil">Modifier la photo de profil :</label>
                <input type="file" name="fichier_photo_profil" id="fichier_photo_profil">
                <input type="submit" value="Enregistrer">
            </form>
            <form action="" method="post">
                <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
                <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" value="">
                <input type="password" name="confirmation_mot_de_passe" id="confirmnew" value="">
                <input type="submit" name="modifier_mot_de_passe">
            </form>
            <p><?php echo $recupUser['pts_fidelite'] ?></p>
            <p><?php echo $recupUser['email']; ?></p>
        </section>
    </main>

</body>

</html>