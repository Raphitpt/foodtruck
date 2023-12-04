<?php 
    require '/functions.php';
    echo head('Création de compte');
?>
    <form method="POST">
        <input type="text" name="nom" placeholder="Votre nom">
        <input type="text" name="prenom" placeholder="Votre prénom">
        <input type="text" name="passwd" placeholder="Password">
        <input type="submit" value="Connexion">
    </form>
<?php
    echo footer();
?>