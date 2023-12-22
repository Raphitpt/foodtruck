<?php

require 'bootstrap.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé.
    echo "Vous n'êtes pas le bienvenu ici";
    echo "<a href='index.php'>Retour</a>";
    exit();
}

echo head('Historique de commande');

$hist = "SELECT * FROM commandes inner join users on commandes.id_user = users.id_user";
$hist = $dbh->query($hist);
$hist = $hist->fetchAll();


?>
<body>
    <nav>
        <ul>
            
            </li>
            <li><a href="./index.php"><i class="fa-solid fa-house"></i></a></li>
            <li><a href=""><i class="fa-solid fa-truck"></i></a></li>
            <li> <img src="./assets/img/FOUEE2.png" alt="logo fouee">
            <li><a href=""><i class="fa-solid fa-phone"></i></a></li>
            <li><a href="./login.php"><i class="fa-solid fa-user"></i></a></li>
        </ul>
    </nav>
    <main>
        <a href="indexBO.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
        <section class="commandeTable">
            <h1>Historique de commande</h1>
                <table class="table" id="table" data-toggle="table" data-show-columns="true" data-search="true" auto-refresh="true">
                    <thead>
                        <tr>
                            <th scope="col" data-sortable="true" data-field="id">Numéro de commande</th>
                            <th scope="col">Nom/Prénom du client</th>
                            <th scope="col">Date de commande</th>
                            <th scope="col">Date de retrait</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hist as $histo) { ?>
                            <tr>
                                <td><?php echo $histo['id_commande']; ?></td>
                                <td><?php echo $histo['nom']." ".$histo['prenom'] ?></td>
                                <td><?php echo $histo['date_commande']; ?></td>
                                <td><?php echo $histo['date_retrait']; ?></td>
                                <td><?php echo $histo['statut']; ?></td>
                                <td><?php echo $histo['commentaire']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
        </section>
    </main>    
    
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
</html>