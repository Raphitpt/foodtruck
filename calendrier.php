<?php


$startDate = new DateTime("$year-01-01");
$endDate = new DateTime("$year-12-31");

$dateInterval = new DateInterval('P1D'); // P1D signifie une période de 1 jour
$dateRange = new DatePeriod($startDate, $dateInterval, $endDate);




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
                selectedCells.push({ date: date, hour: hour });
            }
        }
    }

</script>
