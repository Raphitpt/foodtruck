<?php 
    require '/functions.php';
    echo head('Création de compte');
?>
    <form method="POST">
        <input type="text" name="nom" placeholder="Votre nom">
        <input type="text" name="prenom" placeholder="Votre prénom">
        <input type="mail" name="email" placeholder="Votre email">
        <input type="text" name="passwd" placeholder="Password">
        <input type="submit" value="Créer votre compte">
    </form>
<?php
    echo footer();
?>