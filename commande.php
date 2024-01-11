<?php

$email = 'exemple@exemple.com';
$foues = 8;
$tempsCuisson = 10; // minutes
$maxCapacite = $foues * $tempsCuisson;

// Par exemple, les commandes précédentes (à partir de la base de données)
$commandesPrecedentes = [
    ['heure' => '12:30', 'produits' => ['salé', 'sucré']],
    ['heure' => '13:30', 'produits' => ['salé', 'sucré']],
];

function genererHoraire($heures)
{
    return sprintf('%02d:%02d', $heures[0], $heures[1]);
}

function verifierDisponibilite($commandes, $capacite)
{
    $utilisationCapacite = 0;
    foreach ($commandes as $commande) {
        $utilisationCapacite += count($commande['produits']);
    }
    return $utilisationCapacite + $capacite <= 480; // 480 = 8 fouées x 60 minutes
}

function obtenirProchaineDisponibilite($commandes, $capacite)
{
    $prochaineDisponibilite = strtotime('today');
    foreach ($commandes as $commande) {
        $prochaineDisponibilite = max($prochaineDisponibilite, strtotime($commande['heure']));
    }
    $disponibilite = strtotime('+' . ($capacite / 60) . ' hours', $prochaineDisponibilite);
    return date('H:i', $disponibilite);
}

// Traiter la commande
$capaciteActuelle = $maxCapacite;
foreach ($commandesPrecedentes as $commande) {
    $capaciteActuelle -= count($commande['produits']) * $tempsCuisson;
}

if (verifierDisponibilite($commandesPrecedentes, $capaciteActuelle)) {
    $prochaineDisponibilite = obtenirProchaineDisponibilite($commandesPrecedentes, $capaciteActuelle);
    $message = "L'horaire au plus tôt pour votre récupération de la commande sera à " . $prochaineDisponibilite . "h";
    mail($email, 'Récupération de commande', $message);
} else {
    $message = "Il n'y a pas de place pour votre commande en ce moment. Veuillez réessayer ultérieurement.";
    mail($email, 'Récupération de commande impossible', $message);
}

?>