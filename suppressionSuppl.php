<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    exit();
}
   

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
