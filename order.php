<?php
require './bootstrap.php';

session_start();


echo head('Panier');

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/css/style.css" rel="stylesheet">
    <title>Connexion</title>
</head>

<body>
    <nav>
        <img src="" alt="logo fouee">
        <ul>
            <li><button><a href="./index.php">Accueil</a></button></li>
            <li><button><a href="">Commander</a></button></li>
            <li><button><a href="">Panier</a></button></li>
            <li><button><a href="./login.php">Se connecter</a></button></li>
            <li><button><a href="signin.php">S'inscrire</a></button></li>
        </ul>
    </nav>
    <main>
        <main>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>Mon panier</h1>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Prix</th>
                                    <th scope="col">Quantité</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Supprimer</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="total">
                            <h2 id="totalCommande"></h2>
                        </div>
                        <div class="btn">
                            <button type="button" class="btn btn-primary">Commander</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </main>
    <script>
        const panier = JSON.parse(sessionStorage.getItem('panier')) || [];
        const tbody = document.querySelector('tbody');
        let html = '';
        const totalCommande = document.getElementById('totalCommande');
        let total = 0;
        
        panier.forEach(element => {
            html += '<tr>';
            html += `<th scope="row">${element.nom}</th>`;
            html += `<td>${element.prix}</td>`;
            html += `<td>${element.quantite}</td>`;
            let prix = parseFloat(element.prix);
            let quantite = parseFloat(element.quantite);
            let articleTotal = prix * quantite;
            html += `<td>${articleTotal} €</td>`;
            html += `<td><button><i class="fa-solid fa-trash"></button></i></td>`;
            html += '</tr>';
            total += articleTotal;
        });

        tbody.innerHTML = html;
        totalCommande.innerHTML = `Total de la commande : ${total} €`;

        if (panier.length === 0) {
            commanderButton.style.display = 'none';
        }

        commanderButton.addEventListener('click', function() {
            window.location.href = './order.php';
        });
    </script>
    <script src="./assets/js/functions.js"></script>
    <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>