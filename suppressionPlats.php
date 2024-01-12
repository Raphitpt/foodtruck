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
if (isset($_GET['id_plat'])) {
    $id_plat = (int)$_GET['id_plat'];
    
    // Vérifier si le paramètre de confirmation a été envoyé
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
        // Suppression des données
        $sql = 'DELETE FROM plats WHERE id_plat = :id_plat';
        // exécuter
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':id_plat' => $id_plat,
        ]);
        
        // redirection vers la page de listing
        header('Location: indexBO.php');
        exit();
    }
    
    // Afficher la pop-up de confirmation avec JavaScript
    echo "<script>
            var confirmation = confirm('Voulez-vous vraiment supprimer cet élément ?');
            if (confirmation) {
                window.location.href = 'delete.php?id_plat={$id_plat}';
            } else {
                window.location.href = 'indexBO.php';
            }
          </script>";
}
?>
