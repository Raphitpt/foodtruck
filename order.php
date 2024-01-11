<?php
require './bootstrap.php';

session_start();


echo head('Panier');
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/css/style.css" rel="stylesheet">
    <title>Connexion</title>
    <style>
        table {
            width: 15vw;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>

</head>

<body>
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p>Fouée't Moi
            </li>
            <li><button onclick="location.href = './index.php'" class="button_nav">Accueil</button></li>
            <li><button onclick="location.href = ''" class="button_nav">Commander</button></li>
            <li><button onclick="location.href = ''" class="button_nav">Nous contacter</button></li>
        </ul>
        <ul class="nav_right">
            <li><button onclick="location.href = './login.php'" class="button_nav connect">Se connecter</button></li>
        </ul>
    </nav>
    <main>
        <main>
            <section class="recap">
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
                            <div class="resultDiv"></div>
                            <div class="total">
                                <h2 id="totalCommande"></h2>
                            </div>
                            <div class="btn">
                                <button type="button" class="btn btn-primary btn_commander">Commander</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <h2>Réserver son repas</h2>

            <div class="quantite"></div>
            <select name="choix_date" id="choix_date" required>
                <?php
                $currentDate = new DateTime(); // La date actuelle

                // Cloner la date actuelle pour avoir la date de fin
                $endDate = clone $currentDate;
                $endDate->add(new DateInterval('P2W'));

                $dateInterval = new DateInterval('P1D');
                $dateRange = new DatePeriod($currentDate, $dateInterval, $endDate);
                foreach ($dateRange as $date) {
                    $currentDate = $date->format('Y-m-d');
                    echo '<option class="calendar-cell data-date="' . $currentDate . '" onclick="selectCell(this)">';
                    echo $currentDate;  // Format date et heure
                    echo '</option>';
                }
                ?>
            </select>
            <div class="radio-inputs">
                <?php
                for ($hour = 12; $hour <= 15; $hour++) {
                    for ($minute = 0; $minute <= ($hour == 15 ? 0 : 55); $minute += 10) {
                        $time = str_pad($hour, 2, '0', STR_PAD_LEFT) . 'h' . str_pad($minute, 2, '0', STR_PAD_LEFT);
                        echo '<div class="btnHeure">';
                        echo '<p class="selectedTime">' . $time . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <script>
                const panier = JSON.parse(sessionStorage.getItem('panier')) || [];
                console.log(panier);
            </script>
        </main>
        <script>
            const tbody = document.querySelector('tbody');
            let html = '';
            const totalCommande = document.getElementById('totalCommande');
            const commanderButton = document.querySelector('.btn_commander')
            const heureReservation = document.querySelectorAll('.selectedTime');
            const btnHeure = document.querySelectorAll('.btnHeure');
            const resultDiv = document.querySelector('.resultDiv');

            let heure = "";

            let total = 0;

            function listPanier(x) {
                html = '';
                let prix = 0;
                let quantite = 0;
                total = 0;
                x = x || "12h00";
                panier.forEach(element => {
                    html += '<tr>';
                    html += `<th scope="row">${element.nom}</th>`;
                    html += `<td>${element.prix}</td>`;
                    html += `<td>${element.quantite}</td>`;
                    prix = parseFloat(element.prix);
                    quantite = parseFloat(element.quantite);
                    articleTotal = prix * quantite;
                    html += `<td>${articleTotal} €</td>`;
                    html += `<td><button><i class="fa-solid fa-trash"></button></i></td>`;
                    if (element.suplement != null) {
                        html += `<td>${element.suplement.nom}</td>`;
                    }
                    html += '</tr>';
                    total += articleTotal;
                });
                resultDiv.innerHTML = `${x}`;
                tbody.innerHTML = html;
                totalCommande.innerHTML = `Total de la commande : ${total} €`;
            }
            listPanier();
            if (btnHeure) {
                btnHeure.forEach((elem) => {
                    elem.addEventListener("click", function(event) {
                        listPanier(event.target.textContent);
                        btnHeure.forEach((elem) => {
                            elem.classList.remove('heureSelected');
                        });
                        elem.classList.add('heureSelected');
                    });
                });
            }

            if (panier.length === 0) {
                commanderButton.style.display = 'none';
            }

            /*commanderButton.addEventListener('click', function() {
                window.location.href = 'index.php';
            });
            */
            commanderButton.addEventListener('click', function() {
                if (!heureSelectionnee) {
                    alert("Veuillez sélectionner une heure avant de commander.");
                    return;
                }
             
                console.log('idpanier');
                const commande = {
                    id_commande:idpanier,
                    date: document.getElementById('choix_date').value,
                    heure: document.querySelector('.heureSelected .selectedTime').textContent
                };

                // Utilisez Fetch API pour envoyer les données au serveur
                fetch('enregistrer_commande.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(commande),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert("Commande enregistrée avec succès!");
                            // Vous pouvez rediriger l'utilisateur ou effectuer d'autres actions après l'enregistrement
                        } else {
                            alert("Erreur lors de l'enregistrement de la commande.");
                        }
                    })
                    .catch((error) => {
                        console.error('Erreur lors de l\'enregistrement de la commande:', error);
                    });
            });
        </script>
        <script src="./assets/js/functions.js"></script>
        <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>