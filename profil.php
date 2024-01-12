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

?>

<body>
    <h1>Profil de <?php echo $recupUser['nom'] . " " . $recupUser['prenom'] ?></h1>
    <img src="<?php if ($photo == NULL) {
                    echo "./assets/img/grandprofilfb.jpg";
                } else {
                    echo $photo;
                } ?>" />
    <p><?php echo $recupUser['pts_fidelite'] ?></p>
    <p><?php echo $recupUser['email']; ?></p>
</body>