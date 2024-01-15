<?php
require './bootstrap.php';
echo head('Mentions Légales');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales de Vente</title>
    <link rel="stylesheet" href="./assets/css/style.css" type="text/css" media="all">
    <script src="https://kit.fontawesome.com/9a21cee862.js" crossorigin="anonymous"></script>

</head>

<body>
    <main id="mentions">
        <section class="retours">
            <a href="./accueil.php">
                <i class="fa-solid fa-arrow-left"></i>
                Retours
            </a>
        </section>

        <section id="mentionsLegales">
            <h2>1. Commandes</h2>
            <p>Les commandes peuvent être passées sur place, directement dans notre établissement. Aucun paiement en
                ligne n'est requis lors de la commande.</p>

            <div>
                <h2>2. Paiement sur Place</h2>
                <p>Le paiement des commandes s'effectue sur place, au moment de la récupération des produits. Nous
                    acceptons
                    les paiements en espèces, par carte de crédit, ou tout autre moyen de paiement spécifié.</p>
            </div>

            <div>
                <h2>3. Annulation</h2>
                <p>Les annulations de commandes peuvent être effectuées sur place avant le paiement. Une fois le
                    paiement
                    effectué, les annulations ne sont généralement pas autorisées. Veuillez consulter notre équipe pour
                    plus
                    d'informations.</p>
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; 2024 Votre Entreprise | Conditions Générales de Vente</p>
    </footer>
</body>

</html>