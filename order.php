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

        .icon-cell {
            border: 0;
            color: red;
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
    <main class="recap_commande">
        <section class="recap">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="btn-retour">
                            <a href="index.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
                        </div>
                        <h1>Mon panier</h1>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Prix</th>
                                    <th scope="col">Quantité</th>
                                    <th scope="col">Total</th>
                                    <!-- <th scope="col">Supprimer</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="total">
                            <h2 id="totalCommande"></h2>
                            <h2 id=totalDate></h2>
                        </div>
                        <div class="btn">
                            <button onclick="location.href = './commandefinal.php'"
                                class="btn btn-primary btn_commander">Commander</button>
                            <button onclick="location.href = './index.php'"
                                class="btn btn-secondary btn_commander">Retour</button>
                        </div>
                        <div class="monElement"></div>
                    </div>
                </div>
            </div>
            <div>
                <h2>Réserver son repas</h2>

                <div class="quantite"></div>
                <input type="date" id="dateReservation">
                </select>
                <div class="radio-inputs">
                    <?php
                    for ($hour = 12; $hour <= 15; $hour++) {
                        for ($minute = ($hour == 12 ? 10 : 0); $minute <= ($hour == 15 ? 0 : 55); $minute += 10) {
                            $time = str_pad($hour, 2, '0', STR_PAD_LEFT) . 'h' . str_pad($minute, 2, '0', STR_PAD_LEFT);
                            echo '<div class="btnHeure">';
                            echo '<p class="selectedTime">' . $time . '</p>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

        <script>
            const panier = JSON.parse(sessionStorage.getItem('panier')) || [];
            console.log(panier);
        </script>
    </main>
    <script>
        const tbody = document.querySelector('tbody');
        let html = '';
        const totalCommande = document.getElementById('totalCommande');
        const totalDate = document.getElementById('totalDate');
        const commanderButton = document.querySelector('.btn_commander');
        const heureReservation = document.querySelectorAll('.selectedTime');
        const btnHeure = document.querySelectorAll('.btnHeure');
        const dateReservationInput = document.getElementById('dateReservation');

        let total = 0;

        function formatDate(selectedDate) {
            const options = {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric'
            };
            const dateObject = new Date(selectedDate);
            return dateObject.toLocaleDateString('fr-FR', options);
        }

        function listPanier(heure, date) {
            html = '';
            let prix = 0;
            let quantite = 0;
            total = 0;
            heure = heure || "12h00";

            panier.forEach(element => {
                html += '<tr>';
                html += `<th scope="row">${element.nom}</th>`;
                html += `<td class="monElement" id="${element.id}">${element.prix}</td>`;
                html += `<td class="monElement" id="${element.id}">${element.quantite}</td>`;
                prix = parseFloat(element.prix);
                quantite = parseFloat(element.quantite);
                const articleTotal = prix * quantite;
                html += `<td class="monElement" id="${element.id}">${articleTotal} €</td>`;
                html += `<td class="icon-cell"><i class="fa-solid fa-xmark icon-xmark" data-id="${element.id}"></i></td>`;
                if (element.suplement != null) {
                    html += `<td>${element.suplement.nom}</td>`;
                }
                html += '</tr>';
                total += articleTotal;

            });
            const iconCell = document.querySelector('.icon-cell');

            // Vous pouvez ajouter des styles spécifiques à cette cellule
            if (iconCell) {
                iconCell.style.border = 'none'; // Ajoutez d'autres styles selon vos besoins
            }

            tbody.innerHTML = html;
            const formattedDate = formatDate(date);
            totalCommande.innerHTML = `Total de la commande : ${total} €`;
            totalDate.innerHTML = `Date de la commande : ${heure} le ${formattedDate}`;

            const monElements = document.querySelectorAll(".monElement");
            const ids = Array.from(monElements).map(element => element.id);
            const iconCells = document.querySelectorAll('.icon-cell');


            // Associer un événement de clic à chaque icône de suppression
            iconCells.forEach(icon => {
                icon.addEventListener('click', function (event) {
                    const id = event.target.dataset.id;
                    if (id) {
                        // Supprimer l'élément du panier en utilisant son ID
                        const itemIndex = panier.findIndex(item => item.id === id);
                        if (itemIndex !== -1) {
                            panier.splice(itemIndex, 1);
                            // Mettre à jour l'affichage du panier après la suppression
                            listPanier(heure, date);
                        }
                    }
                });
            });
            sessionStorage.setItem("panier", JSON.stringify(panier));
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialise la date du jour lors du chargement de la page
            const today = new Date();
            const formattedToday = today.toISOString().split('T')[0]; // Format YYYY-MM-DD
            dateReservationInput.value = formattedToday;

            // Initialise la liste de panier avec la date actuelle
            listPanier("12h00", formattedToday);

            // Associe l'événement de changement de date
            dateReservationInput.addEventListener('change', function () {
                const selectedDate = dateReservationInput.value;
                // Vérifie si une heure est déjà sélectionnée, puis met à jour le panier
                const selectedHeure = document.querySelector('.heureSelected');
                const heure = selectedHeure ? selectedHeure.textContent : "12h00";
                listPanier(heure, selectedDate);
            });

            // Associe l'événement de clic sur une heure
            if (btnHeure) {
                btnHeure.forEach((elem) => {
                    elem.addEventListener("click", function (event) {
                        listPanier(event.target.textContent, dateReservationInput.value);
                        btnHeure.forEach((elem) => {
                            elem.classList.remove('heureSelected');
                        });
                        elem.classList.add('heureSelected');
                    });
                });
            }
        });

        if (panier.length === 0) {
            commanderButton.style.display = 'none';
        }
        console.log('Contenu du panier avant la requête fetch :', panier);
        // function sendPanierToServer() {
        //     // Assurez-vous que le panier est correctement défini avant d'exécuter la requête fetch
        //     fetch('commandefinal.php', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //         },
        //         body: JSON.stringify({ panier: panier }),
        //     })
        //         .then(response => {
        //             if (!response.ok) {
        //                 throw new Error(`Erreur HTTP ! Statut : ${response.status}`);
        //             }
        //             return response.json();
        //         })
        //         .then(data => {
        //             console.log('Réponse du serveur :', data);
        //         })
        //         .catch(error => {
        //             console.error('Erreur lors de la requête :', error);
        //         });
        // }
        fetch('commandefinal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ panier: panier }),
        })
            .then(response => response.json())
            .then(data => {
                console.log('Réponse du serveur :', data);
            })
            .catch(error => {
                console.error('Erreur lors de la requête :', error);
            });
        // /*commanderButton.addEventListener('click', function() {
        //     window.location.href = 'commande.php';
        // });*/
        // commanderButton.addEventListener('click', function () {
        //     /*if (!heureSelectionnee) {
        //         alert("Veuillez sélectionner une heure avant de commander.");
        //         return;
        //     }
        //     */
        //     console.log(document.querySelector('.heureSelected .selectedTime').textContent);
        //     console.log(selectedDate);

        //     console.log(date_retrait);
        //     const commande = {
        //         panier: panier,
        //         date: document.getElementById('choix_date').value,
        //         heure: document.querySelector('.heureSelected .selectedTime').textContent
        //     };

        //     // Utilisez Fetch API pour envoyer les données au serveur
        // fetch('commandefinal.php', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //     },
        //     body: JSON.stringify(panier),
        // })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.status === 'success') {
        //             alert("Commande enregistrée avec succès!");
        //             // Vous pouvez rediriger l'utilisateur ou effectuer d'autres actions après l'enregistrement
        //         } else {
        //             alert("Erreur lors de l'enregistrement de la commande.");
        //         }
        //     })
        //     .catch((error) => {
        //         console.error('Erreur lors de l\'enregistrement de la commande:', error);
        //     });

    </script>
    <script src="./assets/js/functions.js"></script>
    <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>