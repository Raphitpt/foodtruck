<?php
session_start();
if (isset($_SESSION['email'])) {
   

    // intégration des librairies
    include 'bootstrap.php';
     // le paramètre id doit être passé dans l'URL
    if (isset($_GET['id_plat'])) {
        $id_plat = (int)$_GET['id_plat'];

        $sql = 'DELETE FROM plats WHERE id_plat = :id_plat';
        // exécuter
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':id_plat' => $id_plat,
        ]);
    }
    // redirection vers la page de listing
    header('Location: indexBO.php');

?>
<?php
} else {
  header('location: index.php');
}
?>