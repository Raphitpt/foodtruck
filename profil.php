<?php

require './bootstrap.php';
session_start();

$email = $_SESSION['email'];
$recupId = 'SELECT id_user FROM users where email = :email';
$user = $dbh->prepare($recupId);
$user->execute([
    'email' => $email,
]);
$recupId = $user->fetch();
$id = $recupId['id_user'];

$infosQuery = "SELECT * FROM settings";
$infosResult = $dbh->query($infosQuery);
$infos = $infosResult->fetch();
echo head('Profil');

$recupUser = 'SELECT * FROM users WHERE id_user = :id';
$stmt = $dbh->prepare($recupUser);
$stmt->execute([
    'id' => $id,
]);
$recupUser = $stmt->fetch();
$photo = $recupUser['photoprofil'];

$errors = [];
$successMessage = '';

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
                $successMessage = 'La photo de profil a été mise à jour avec succès.';
                header("Location: profil.php");
                unlink($photo);
            }
        } else {
            $errors[] = 'Erreur lors du téléchargement du fichier.';
        }
    }
}

if (isset($_POST['modifier_mot_de_passe']) && !empty($_POST['nouveau_mot_de_passe']) && !empty($_POST['confirmation_mot_de_passe'])) {
    $nouveau_mot_de_passe = isset($_POST['nouveau_mot_de_passe']) ? $_POST['nouveau_mot_de_passe'] : '';
    $confirmation_mot_de_passe = isset($_POST['confirmation_mot_de_passe']) ? $_POST['confirmation_mot_de_passe'] : '';

    if ($nouveau_mot_de_passe !== $confirmation_mot_de_passe) {
        $errors[] = 'Les champs du mot de passe ne correspondent pas.';
    } else {
        $hashed_password = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
        $updateMotDePasseSql = 'UPDATE users SET passwd = :passwd WHERE id_user = :id_user';
        $updateMotDePasseStmt = $dbh->prepare($updateMotDePasseSql);
        $updateMotDePasseStmt->execute([
            'passwd' => $hashed_password,
            'id_user' => $id,
        ]);

        if ($updateMotDePasseStmt->rowCount() > 0) {
            $successMessage = 'Le mot de passe a été modifié avec succès.';
            header("Location: profil.php");
        } else {
            $errors[] = 'Erreur lors de la modification du mot de passe.';
        }
    }
}

?>
<style>
    img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: auto;
        margin-right: auto;

    }

    .form {
        margin-top: 5px;
        border-radius: 0;
        background-color: #eeeeee00;
        ;
    }

    .connect {
        text-align: center;

    }

    .connect a {
        text-decoration: none;
        color: black;
    }

    ul {
        text-decoration: none;
    }

    .menu-container img {
        height: auto;
        border-radius: 0;
        object-fit: contain;
        margin-left: auto;
        margin-right: auto
    }
</style>

<body>
    <header>
        <nav>
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                    <p><?= $infos['nom_entreprise'] ?></p>
                </li>
                <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav">Commander</button></li>
                <li><button onclick="location.href = './contact.php'" class="button_nav">Nous contacter</button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav">Back Office</button></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="menu-container">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p><?= htmlspecialchars("Fouée't Moi") ?>
                </li>
            </ul>
            <div class="menu-btn" id="menu-btn">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <nav class="menu">
                <ul>
                    <li><a href="./accueil.php">Accueil</a></li>
                    <li><a href="index.php">Commander</a></li>
                    <li><a href="contact.php">Nous contacter</a></li>
                    <?php if (isset($_SESSION['email'])) { ?>
                        <li><a href="profil.php">Mon compte</a></li>
                    <?php } else { ?>
                        <li><a href="login.php">Connexion/Inscription</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                        <li><button onclick="location.href = 'indexBO.php'" class="button_nav"><?= htmlspecialchars("Back Office") ?></button></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="btn-retour">
            <a href="accueil.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        </div>


        <section class="form">
            <h1>Profil de <?php echo $recupUser['nom'] . " " . $recupUser['prenom'] ?></h1>
            <img src="<?php echo $photo == NULL ? "./assets/img/grandprofilfb.jpg" : $photo; ?>" />
            <button onclick="location.href = './historique.php'" class="button_nav connect"><?= htmlspecialchars("Historique") ?></button></li>
            <form action="" method="post" enctype="multipart/form-data">
                <label for="fichier_photo_profil">Modifier la photo de profil :</label>
                <input type="file" name="fichier_photo_profil" id="fichier_photo_profil">
                <input type="submit" value="Enregistrer">
                <br>
                <?php if (!empty($errors)) : ?>
                    <span class="error-message" style="color:red;"><?= implode('<br>', $errors) ?></span>
                <?php endif; ?>
            </form>
            <p>Votre email : <br><strong><?php echo $recupUser['email']; ?></strong></p>
            <form action="" method="post">
                <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
                <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" value="">
                <br>
                <label for="confirmnew">Confirmer le nouveau mot de passe :</label>
                <input type="password" name="confirmation_mot_de_passe" id="confirmnew" value=""><br>
                <input type="submit" name="modifier_mot_de_passe">
            </form>
            <p>Vos points de fidélité : <br> <strong><?php echo $recupUser['pts_fidelite'] ?></strong> FouéePoints</p>

            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) : ?>
                    <button onclick="location.href = './logout.php'" class="button_nav connect"><?= htmlspecialchars("Se déconnecter") ?></button>
                <?php endif; ?>
            </ul>
        </section>

    </main>


</body>
<script>
    document.getElementById("menu-btn").addEventListener("click", function() {
        this.classList.toggle("open");
        var mainContent = document.querySelector("main");
        if (this.classList.contains("open")) {
            mainContent.style.display = "none";
        } else {
            mainContent.style.display = "block";
        }
    });
</script>

</html>