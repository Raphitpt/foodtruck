<?php
$year = 2023;

$startDate = new DateTime("$year-01-01");
$endDate = new DateTime("$year-12-31");

$dateInterval = new DateInterval('P1D'); // P1D signifie une période de 1 jour
$dateRange = new DatePeriod($startDate, $dateInterval, $endDate);


/*

// Afficher le calendrier
echo '<table border="1">';
echo '<tr><th>Date</th>';

// Entêtes des colonnes
for ($hour = 8; $hour <= 18; $hour++) {
    echo '<th>' . $hour . ':00</th>';
}

echo '</tr>';



foreach ($dateRange as $date) {
    $currentDate = $date->format('Y-m-d');
    echo '<tr>';
    echo '<td>' . $currentDate . '</td>';

    // Génération des cellules pour chaque heure
    for ($hour = 8; $hour <= 18; $hour++) {
        $cellClass = isset($organizedReservations[$currentDate][$hour]) ? 'booked' : '';
        echo '<td class="calendar-cell ' . $cellClass . '" data-date="' . $currentDate . '" data-hour="' . $hour . '" onclick="selectCell(this)"></td>';
    }

    echo '</tr>';
}

echo '</table>';
?>

<button onclick="submitReservations()">Valider les réservations</button>
*/

session_start();
require 'bootstrap.php';
echo head('Modifier un plat');
?>

<body>
    <form action="" method="post">
        <select name="choix_heure" id="choix_heure" required>
            <?php
            foreach ($dateRange as $date) {
                $currentDate = $date->format('Y-m-d');

                for ($hour = 8; $hour <= 18; $hour++) {
                    $cellClass = isset($organizedReservations[$currentDate][$hour]) ? 'booked' : '';
                    echo '<option class="calendar-cell ' . $cellClass . '" data-date="' . $currentDate . '" data-hour="' . $hour . '" onclick="selectCell(this)">';
                    echo $currentDate . ' ' . sprintf("%02d", $hour) . ':00';  // Format date et heure
                    echo '</option>';
                }
            }



            ?>

    </form>
</body>
<script>
    var selectedCells = [];

    function selectCell(cell) {
        if (!cell.classList.contains('booked')) {
            cell.classList.toggle('selected');
            var date = cell.getAttribute('data-date');
            var hour = cell.getAttribute('data-hour');

            var index = selectedCells.findIndex(function (item) {
                return item.date === date && item.hour == hour;
            });

            if (index !== -1) {
                selectedCells.splice(index, 1);
            } else {
                selectedCells.push({
                    date: date,
                    hour: hour
                });
            }
        }
    }
</script>

<!DOCTYPE html>
<html>

<head>
    <title>Réserver son repas</title>
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

    <h2>Réserver son repas</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                    echo '<td><input type="radio" name="selectedTime" value="' . $time . '"></td>';
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
        echo 'Vous avez sélectionné l\'horaire suivant: ' . $selectedTime;
    }
    ?>

</body>

</html>