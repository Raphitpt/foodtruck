<?php
session_start();
// if (isset($_SESSION['email'])) {
   

    // intégration des librairies
    include 'bootstrap.php';
     // le paramètre id doit être passé dans l'URL
    if (isset($_GET['id_suppl'])) {
        $id_suppl = (int)$_GET['id_suppl'];

        $sql = 'DELETE FROM supplements WHERE id_suppl = :id_suppl';
        // exécuter
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':id_suppl' => $id_suppl,
        ]);
    }
    // redirection vers la page de listing
    header('Location: indexBO.php');

?>
<?php
// } else {
//   header('location: index.php');
// }
?>