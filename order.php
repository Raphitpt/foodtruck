<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    // Rediriger vers login.php si l'utilisateur n'est pas connecté
    header('Location: login.php');
    $_SESSION['order'] = "je viens de order";
    exit();
}

require 'bootstrap.php';

echo head('Panier');
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();

// Récupérer l'ID de l'utilisateur connecté
$email = $_SESSION['email'];
$photo = "SELECT * FROM users WHERE email = :email";
$stmt = $dbh->prepare($photo);
$stmt->execute([
    'email' => $email,
]);
$photo = $stmt->fetch();
$userId = $photo['id_user'];

$currentDateTime = new DateTime();
$maxDate = date('Y-m-d', strtotime('+1 week'));
// Récupérer les informations de l'utilisateur par son ID
echo '<input type="hidden" id="userId" value="' . $userId . '">';
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

        .btnHeure.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .selectedTime.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

</head>

<body>
    <header>
        <nav>
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= $infos['url_logo'] ?>" alt="logo fouee">
                    <p>
                        <?= $infos['nom_entreprise'] ?>
                    </p>
                </li>
                <li><button onclick="location.href = './accueil.php'" class="button_nav">Accueil</button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav">Commander</button></li>
                <li><button onclick="location.href = './contact.php'" class="button_nav">Nous contacter</button></li>
            </ul>
            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) { ?>
                    <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                <?php } else { ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect">
                            <?= htmlspecialchars("Se connecter") ?>
                        </button></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="menu-container">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p>
                        <?= htmlspecialchars("Fouées du Terroir") ?>
                </li>
            </ul>
            <div class="menu-btn" id="menu-btn">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <nav class="menu">
                <ul>
                    <li><a href="./accueil.php">Accueil</a></li>
                    <li><a href="index.php">Commander</a></li>
                    <li><a href="contact.php">Nous contacter</a></li>
                    <?php if (isset($_SESSION['email'])) { ?>
                        <li><a href="profil.php">Mon compte</a></li>
                    <?php } else { ?>
                        <li><a href="login.php">Connexion/Inscription</a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="recap_commande">
        <section class="recap">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>Mon panier</h1>
                        <table class="table">
                            <img src="./assets/img/FOUEE2.png" alt="">
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
                                <!-- <input type="text" placeholder="Précisez quels couverts, serviettes, pailles et
                                    condiments vous souhaitez inclure dans votre commande, ainsi que toute instruction
                                    spécifique à communiquer au restaurant" size="100" id="commentaire"> -->
                                <textarea name="texte" id="commentaire" cols="20" rows="10" placeholder="Précisez quels couverts, serviettes et condiments vous souhaitez inclure dans votre commande."></textarea>
                            </div>
                            <div class="totalRet">
                                <h2>Total de la commande <span id="totalCommande"></span> €</h2>
                                <div>
                                    <h2>FouéePoints: <span id="totalPts">
                                            <select id="ptsFideliteSelect" onchange="updateSelection(document.getElementById('totalCommande').textContent)">
                                                <?php
                                                for ($i = 0; $i <= $photo['pts_fidelite']; $i++) {
                                                    echo "<option value=\"$i\">$i</option>";
                                                }
                                                ?>
                                            </select></span>
                                    </h2>
                                </div>
                                <p>Un fouéePoints = 1€ de réduction !</p>
                                <p>Vous gagnez 1 FouéePoints tous les 10€ d'achats</p>
                            </div>

                            <h2>Date de retrait : <span id="totalHeure"></span> le <span id="totalDate"></span></h2>
                        </div>
                    </div>
                    <div class="btn">
                        <button onclick="location.href = './index.php'" class="btn btn_success btn_continuer">Continuer
                            mes achats</button>
                        <button class="btn btn_dark btn_commander">Commander</button>
                    </div>
                    <div class="monElement"></div>
                </div>
            </div>
            </div>
            <div class="selHeureBtn">
                <h2>Réserver son repas</h2>

                <div class="quantite"></div>
                <input type="date" id="dateReservation" min="<?= date('Y-m-d') ?>" max="<?= $maxDate ?>">
                </select>
                <div class="radio-inputs" id="heureContainer">
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
                        <p>Votre commande a bien été prise en compte. <br>On vous a envoyé un mail de confirmation
                            accompagné de votre facture. <br>Vous pouvez la retrouver dans votre historique de
                            commande.</p>
                        <button onclick="location.href = './index.php'" class="btn btn-secondary button_accueil btn_commander">Accueil</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        const panier = JSON.parse(sessionStorage.getItem('panier')) || [];
        console.log(panier);

        function updateSelection(totalcommande) {
            // Récupérer l'élément <select> par son ID
            var selectElement = document.getElementById("ptsFideliteSelect");

            // Définir des valeurs minimales et maximales
            var minValue = 0;
            var maxValue = totalcommande;

            // Récupérer la valeur sélectionnée
            var selectedValue = parseInt(selectElement.value);

            // Vérifier si la valeur est en dehors des limites
            if (selectedValue < minValue) {
                alert("La valeur sélectionnée est inférieure à la valeur minimale autorisée.");
                // Réinitialiser la sélection à la valeur minimale
                selectElement.value = minValue;
            } else if (selectedValue > maxValue) {
                alert("Vous ne pouvez pas utiliser plus de FouéePoints que le montant de votre commande.");
                // Réinitialiser la sélection à la valeur maximale
                selectElement.value = maxValue;
            } else {
                // Envoyer la valeur sélectionnée à un script PHP côté serveur
                $.ajax({
                    type: "POST",
                    url: ".php", // Remplacez script.php par le nom de votre script PHP
                    data: {
                        selectedValue: selectedValue
                    },
                    success: function(response) {
                        console.log("Valeur envoyée avec succès à PHP !");
                    },
                    error: function(error) {
                        console.error("Erreur lors de l'envoi de la valeur à PHP :", error);
                    }
                });
            }
        }


        // Récupérer l'élément <select> par son ID
        let selectElement = document.getElementById("ptsFideliteSelect");
        let selectedValue = selectElement.value;
        // Ajouter un écouteur d'événements pour le changement de sélection
        selectElement.addEventListener("change", function() {
            // Récupérer la valeur sélectionnée
            selectedValue = selectElement.value;

            // Faire quelque chose avec la valeur sélectionnée
            listPanier("", "", selectedValue)
            console.log("Valeur sélectionnée :", selectedValue);
        });

        function afficherCommandeConfirm() {
            // Masquer la section recap
            document.querySelector('.recap').style.display = 'none';
            // Afficher la section commandeConfirm
            document.querySelector('.commandeConfirm').style.display = 'flex';
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
        const idUsers = document.getElementById('userId');

        let total = 0;
        let nombreArticlesDansPanier = 0;

        function formatDate(selectedDate) {
            const options = {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric'
            };
            const dateObject = new Date(selectedDate);
            return dateObject.toLocaleDateString('fr-FR', options);
        }

        function listPanier(heure, date, selectedValue) {
            html = '';
            let prix = 0;
            let quantite = 0;
            total = 0;
            heure = heure || "12h00";
            date = date || dateReservationInput.value;
            selectedValue = selectedValue || 0;

            nombreArticlesDansPanier = panier.reduce((total, item) => total + parseInt(item.quantite), 0);

            panier.forEach(element => {
                html += '<tr>';
                html += `<td scope="row"><span class="titlePlatsRecap">${element.nom}</span>`;

                if (element.supplements !== null && element.supplements.length > 0) {
                    html += `<ul class="listRecapOrder">`;
                    html += "<p>Supplément(s) : </p>";
                    element.supplements.forEach(suppl => {
                        html += `<li>${suppl.name} (+${suppl.price} €)</li>`;
                    });
                    html += `</ul>`;
                }

                html += `</td>`;
                html += `<td class="monElement" id="${element.id}">${element.prix}</td>`;
                html += `<td class="monElement" id="${element.id}">${element.quantite}</td>`;

                const prix = parseFloat(element.prix);
                const quantite = parseFloat(element.quantite);

                // Ajouter le coût des suppléments
                const supplementCost = calculateSupplementCost(element.supplements);
                const articleTotal = (prix + supplementCost) * quantite;

                html += `<td class="monElement" id="${element.id}">${articleTotal} €</td>`;
                html += `<td class="icon-cell"><i class="fa-solid fa-xmark icon-xmark" data-id="${element.id}"></i></td>`;

                if (element.suplement != null) {
                    html += `<td>${element.suplement.nom}</td>`;
                }

                html += '</tr>';
                total += articleTotal;
            });

            function calculateSupplementCost(supplements) {
                let supplementCost = 0;
                supplements.forEach(function(supplement) {
                    const supplementPrice = parseFloat(supplement.price);
                    supplementCost += supplementPrice;
                });
                return supplementCost;
            }

            // if (nombreArticlesDansPanier > 8) {
            //     btnHeure.forEach((elem) => {
            //         const selectedHour = elem.querySelector('.selectedTime').textContent;
            //         const hourArticlesCount = panier.filter(item => item.heure === selectedHour).reduce((total, item) => total + parseInt(item.quantite), 0);

            //         if (hourArticlesCount >= 8) {
            //             elem.classList.add('disabled');
            //         } else {
            //             elem.classList.remove('disabled');
            //         }
            //     });
            // } else {
            //     btnHeure.forEach((elem) => {
            //         elem.classList.remove('disabled');
            //     });
            // }
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
                icon.addEventListener('click', function(event) {
                    const id = event.target.dataset.id;
                    if (id) {
                        // Supprimer l'élément du panier en utilisant son ID
                        const itemIndex = panier.findIndex(item => item.id === id);
                        if (itemIndex !== -1) {
                            panier.splice(itemIndex, 1);
                            // Mettre à jour l'affichage du panier après la suppression
                            listPanier(heure, date, selectedValue);
                        }
                    }
                });
            });
            total = total - selectedValue;
            totalCommande.innerHTML = `${total}`;
            sessionStorage.setItem("panier", JSON.stringify(panier));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialise la date du jour lors du chargement de la page
            const today = new Date();
            const formattedToday = today.toISOString().split('T')[0]; // Format YYYY-MM-DD
            dateReservationInput.value = formattedToday;

            // Initialise la liste de panier avec la date actuelle
            listPanier("12h00", formattedToday, selectedValue);

            // Associe l'événement de changement de date
            dateReservationInput.addEventListener('change', function() {
                const selectedDate = dateReservationInput.value;
                // Vérifie si une heure est déjà sélectionnée, puis met à jour le panier
                const selectedHeure = document.querySelector('.heureSelected');
                const heure = selectedHeure ? selectedHeure.textContent : "12h00";
                listPanier(heure, selectedDate, selectedValue);
            });

            if (btnHeure) {
                btnHeure.forEach((elem) => {
                    elem.addEventListener("click", function(event) {
                        const selectedHour = elem.querySelector('.selectedTime').textContent;
                        const hourArticlesCount = panier.filter(item => item.heure === selectedHour).reduce((total, item) => total + parseInt(item.quantite), 0);

                        if (hourArticlesCount >= 8) {
                            alert("Vous devez choisir une autre heure, celle-ci est indisponible.");
                        } else {
                            listPanier(selectedHour, dateReservationInput.value, selectedValue);
                            btnHeure.forEach((elem) => {
                                elem.classList.remove('heureSelected');
                            });
                            elem.classList.add('heureSelected');
                        }
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

        commanderButton.addEventListener('click', function() {
            const panierNettoye = nettoyerObjet(panier);
            let dateRetrait = totalDate.textContent;
            let [jour, mois, annee] = dateRetrait.split('/');
            let dateObj = new Date(Date.UTC(annee, mois - 1, jour));
            let formattedDate = dateObj.toISOString().split('T')[0];
            let heureFormated = totalHeure.textContent.split('h');
            let heure = heureFormated[0] + ":" + heureFormated[1];
            let commentaires = commentaire.value;
            let date = formattedDate + " " + heure;
            let idUser = idUsers.value;

            fetch('getProvisionalDate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        date_retrait: date, // La date que vous souhaitez
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Réponse du serveur :', data);
                    if (data.success) {
                        const provisionalDate = data.provisional_date;

                        // Afficher la date provisoire et demander la confirmation
                        const confirmDate = confirm('Veuillez confirmer la date provisoire : ' + provisionalDate);
                        if (confirmDate) {
                            // Si l'utilisateur confirme, exécuter la deuxième requête pour enregistrer la commande
                            fetch('commandefinal.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        panier: panierNettoye,
                                        date_retrait: provisionalDate,
                                        prix: totalCommande.textContent,
                                        commentaire: commentaires,
                                        id_user: idUser,
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Réponse du serveur :', data);
                                    if (data.success) {
                                        fetch('editPtsFid.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                },
                                                body: JSON.stringify({
                                                    pts_fidelite: selectedValue,
                                                }),
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                console.log('Réponse du serveur :', data);
                                                if (data.success) {
                                                    console.log("FouéePoints mis à jour avec succès !");
                                                } else {
                                                    alert(data.error);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Erreur lors de la requête :', error);
                                            });

                                        alert(data.new_time);
                                        afficherCommandeConfirm();
                                    } else {
                                        alert(data.error);
                                    }
                                })
                                .catch(error => {
                                    console.error('Erreur lors de la requête :', error);
                                });
                        } else {
                            alert('Confirmation annulée.');
                        }
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la requête :', error);
                });
        });
        document.getElementById("menu-btn").addEventListener("click", function() {
            this.classList.toggle("open");
            var mainContent = document.querySelector("main");
            if (this.classList.contains("open")) {
                mainContent.style.display = "none";
            } else {
                mainContent.style.display = "block";
            }
        });
        const accueilCommandConfirmButton = document.querySelector('.button_accueil');

        // Fonction pour vider le panier
        function viderPanier() {
            // Réinitialise le panier dans la session
            sessionStorage.removeItem('panier');

            // Redirige l'utilisateur vers la page d'accueil
            window.location.href = './index.php';
        }
        if (accueilCommandConfirmButton) {
            accueilCommandConfirmButton.addEventListener('click', function() {
                viderPanier();
            });
        }

        function updateDisabledHours() {
            let selectedDate = document.getElementById('dateReservation').value;
            let currentDateTime = new Date();
            let heureContainer = document.getElementById('heureContainer');

            // Supprimer toutes les heures actuelles
            while (heureContainer.firstChild) {
                heureContainer.removeChild(heureContainer.firstChild);
            }

            // Réafficher toutes les heures
            for (let hour = 12; hour <= 15; hour++) {
                for (let minute = (hour == 12 ? 10 : 0); minute <= (hour == 15 ? 0 : 55); minute += 10) {
                    let time = ('0' + hour).slice(-2) + ':' + ('0' + minute).slice(-2);
                    let div = document.createElement('div');
                    div.className = 'btnHeure';
                    div.innerHTML = '<p class="selectedTime">' + time + '</p>';
                    heureContainer.appendChild(div);

                    let minuteString = ('0' + minute).slice(-2);

                    let dateTime = new Date(selectedDate + 'T' + hour + ':' + minuteString + ':00');

                    if (dateTime < currentDateTime) {
                        div.classList.add('disabled');
                    }
                }
            }

        }

        // Appeler la fonction au chargement de la page
        window.addEventListener('load', updateDisabledHours);

        // Ajouter un écouteur d'événements pour mettre à jour lors du changement de date
        document.getElementById('dateReservation').addEventListener('input', updateDisabledHours);
    </script>
    <script src="./assets/js/functions.js"></script>
    <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>