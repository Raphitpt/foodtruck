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
                            <button type="button" class="btn btn-primary btn_commander">Commander</button>
                        </div>
                    </div>
                </div>
            </div>
            <h2>Réserver son repas</h2>

            <div class="quantite"></div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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

                <table>
                    <tr>
                        <th>Horaire</th>
                        <th>Sélection</th>
                    </tr>
                    <?php
                    for ($hour = 12; $hour < 15; $hour++) {
                        for ($minute = 0; $minute <= 55; $minute += 10) {
                            $time = str_pad($hour, 2, '0', STR_PAD_LEFT) . 'h' . str_pad($minute, 2, '0', STR_PAD_LEFT);
                            echo '<tr>';
                            echo '<td>' . $time . '</td>';
                            echo '<td><input type="radio" name="selectedTime" value="' . $time . '" class="td"> </td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
                <br>
                <input type="submit" name="submit" value="Réserver">
            </form>

            <?php
            if (isset($_POST['submit'])) {
                $selectedTime = $_POST['selectedTime'];
                $selectedDay = $_POST['choix_date'];
                echo 'Vous avez sélectionné l\'horaire suivant: ' . $selectedTime;
            }
            ?>


            <script>
                const panier = JSON.parse(sessionStorage.getItem('panier')) || [];
                console.log(panier);
                var form = document.getElementById('myform');

                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    var selectedOption = document.querySelector('input[name="selectedTime"]:checked');

                    if (selectedOption) {
                        document.getElementById('result').innerHTML = 'Vous avez sélectionné l\'horaire suivant: ' + selectedOption.value;
                    }
                });


            </script>
        </main>
        <script>
            const tbody = document.querySelector('tbody');
            let html = '';
            const totalCommande = document.getElementById('totalCommande');
            const commanderButton = document.querySelector('.btn_commander')

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
                if (element.suplement != null) {
                    html += `<td>${element.suplement.nom}</td>`;
                }
                html += '</tr>';
                total += articleTotal;
            });

            tbody.innerHTML = html;
            totalCommande.innerHTML = `Total de la commande : ${total} €`;

            if (panier.length === 0) {
                commanderButton.style.display = 'none';
            }

            commanderButton.addEventListener('click', function () {
                window.location.href = 'commande.php';
            });
        </script>
        <script src="./assets/js/functions.js"></script>
        <script src="https://kit.fontawesome.com/45762c6469.js" crossorigin="anonymous"></script>
</body>

</html>