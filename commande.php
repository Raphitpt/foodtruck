<?php
$year = 2023;

$startDate = new DateTime("$year-01-01");
$endDate = new DateTime("$year-12-31");

$dateInterval = new DateInterval('P10M'); // P1D signifie une période de 1 jour
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
                
                for ($hour = 12; $hour < 15; $hour++) {
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

            var index = selectedCells.findIndex(function(item) {
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
