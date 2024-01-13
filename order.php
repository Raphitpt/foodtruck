<?php
require './bootstrap.php';

session_start();


echo head('Panier');
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $photo = "SELECT * FROM users where email = :email";
    $stmt = $dbh->prepare($photo);
    $stmt->execute([
        'email' => $email,
    ]);
    $photo = $stmt->fetch();
}

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

        .commandeConfirm {
            display: none;
        }

        .text-input {
            display: none;
        }
    </style>

</head>

<body>
    <nav>
        <ul class="nav_left">
            <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                <p><?= $infos['nom_entreprise'] ?></p>
            </li>
            <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
            <li><button onclick="location.href = './index.php'" class="button_nav">Commander</button></li>
            <li><button onclick="location.href = './contact.php'" class="button_nav">Nous contacter</button></li>
        </ul>
        <ul class="nav_right">
            <?php if (isset($_SESSION['email'])) { ?>
                <button onclick="location.href = 'profil.php'" class="image"><img
                        src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
            <?php } else { ?>
                <li><button onclick="location.href = './login.php'" class="button_nav connect">
                        <?= htmlspecialchars("Se connecter") ?>
                    </button></li>
            <?php } ?>
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
                            <button class="text" onclick="ouvrirTextInput()">Ajouter un commentaire à la
                                commande</button>

                            <div class="text-input">
                                <label for="monTexte"></label>
                                <input type="text" placeholder="Précisez quels couverts, serviettes, pailles et
                                    condiments vous souhaitez inclure dans votre commande, ainsi que toute instruction
                                    spécifique à communiquer au restaurant" size="100" id="commentaire">
                            </div>
                            <h2>Total de la commande <span id="totalCommande"></span> €</h2>
                            <h2>Date de retrait : <span id="totalHeure"></span> le <span id="totalDate"></span></h2>
                        </div>
                        <div class="btn">
                            <button class="btn btn-primary btn_commander">Commander</button>
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
        <section class="commandeConfirm">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>Commande confirmée !</h1>
                        <p>Votre commande a bien été prise en compte. Vous pouvez la retrouver dans votre historique de
                            commande.</p>
                        <button onclick="location.href = './index.php'"
                            class="btn btn-secondary btn_commander">Retour</button>
                    </div>
                </div>
            </div>
        </section>

        <script>
            const panier = JSON.parse(sessionStorage.getItem('panier')) || [];
            console.log(panier);
        </script>
    </main>
    <script>
        function afficherCommandeConfirm() {
            // Masquer la section recap
            document.querySelector('.recap').style.display = 'none';
            // Afficher la section commandeConfirm
            document.querySelector('.commandeConfirm').style.display = 'block';
        }

        function ouvrirTextInput() {
            // Afficher la zone de texte
            var textInput = document.querySelector('.text-input');
            textInput.style.display = 'block';
        }
        const tbody = document.querySelector('tbody');
        let html = '';
        const totalCommande = document.getElementById('totalCommande');
        const totalDate = document.getElementById('totalDate');
        const totalHeure = document.getElementById('totalHeure');
        const commanderButton = document.querySelector('.btn_commander');
        const heureReservation = document.querySelectorAll('.selectedTime');
        const btnHeure = document.querySelectorAll('.btnHeure');
        const dateReservationInput = document.getElementById('dateReservation');
        const commentaire = document.getElementById('commentaire');

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
            const selectedHour = heure.split('h').join(''); // Convertir l'heure en format numérique
            const articlesAtSelectedHour = panier.filter(element => {
                const articleHour = element.heure.split('h').join(''); // Convertir l'heure de l'article en format numérique
                return articleHour === selectedHour;
            });

            if (articlesAtSelectedHour.length >= 8) {
                alert('Cette heure est déjà pleine. Veuillez sélectionner une autre heure.');
                return; // Ne pas mettre à jour l'affichage du panier si l'heure est pleine
            }

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
            totalCommande.innerHTML = `${total}`;
            totalDate.innerHTML = `${formattedDate}`;
            totalHeure.innerHTML = `${heure}`;
            commentaire.innerHTML = `${commentaire.value}`;

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

            totalCommande.innerHTML = `${total}`;
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

        function nettoyerObjet(objet) {
            for (const [cle, valeur] of Object.entries(objet)) {
                if (typeof valeur === 'string') {
                    // Supprimez les espaces et les retours à la ligne dans les chaînes de caractères
                    objet[cle] = valeur.trim();
                } else if (typeof valeur === 'object') {
                    // Si la valeur est un objet, récursion pour nettoyer les valeurs à l'intérieur
                    objet[cle] = nettoyerObjet(valeur);
                }
            }
            return objet;
        }

        commanderButton.addEventListener('click', function () {
            const panierNettoye = nettoyerObjet(panier);
            let dateRetrait = totalDate.textContent;
            let [jour, mois, annee] = dateRetrait.split('/');
            let dateObj = new Date(Date.UTC(annee, mois - 1, jour));
            let formattedDate = dateObj.toISOString().split('T')[0];
            let heureFormated = totalHeure.textContent.split('h');
            let heure = heureFormated[0] + ":" + heureFormated[1];
            let commentaires = commentaire.value;
            let date = formattedDate + " " + heure;
            fetch('commandefinal.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    panier: panierNettoye,
                    date_retrait: date,
                    prix: totalCommande.textContent,
                    commentaire: commentaires,
                }, null),
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Réponse du serveur :', data);
                    if (data.success) {
                        // Rediriger vers la page de confirmation
                        afficherCommandeConfirm();
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la requête :', error);
                });
        });
    </script>
    <script src="./assets/js/functions.js"></script>
    <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>