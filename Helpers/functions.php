<?php
/*
    Fichier : /Helpers/functions.php
 */

/**
 * Retourne le contenu HTML du bloc d'en tête d'une page.
 * Deux CSS sont automatiquement intégré :
 *   - pico.css
 *   - custom.css
 *
 * @param string title le titre de la page.
 * @return string
 */
function head(string $title = ''): string
{
    return  <<<HTML_HEAD
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="icon" type="image/png" href="./assets/img/659fabf161f3c_FOUEE2.png" />
  <title>$title</title>
</head>

HTML_HEAD;
}


/**
 * Retourne vrai si la méthode d'appel est GET.
 */
function isGetMethod(): bool
{
    return  ($_SERVER['REQUEST_METHOD'] === 'GET') ;
}
/**
 * Envoyer un mail de confirmation.
 */
function sendConfirmationMail(string $email, string $token): void
{
    $to = $email;
    $subject = 'Confirmation de votre inscription';
    $message = <<<HTML
    <h1>Confirmation de votre inscription</h1>
    <p>Merci de cliquer sur le lien suivant pour confirmer votre inscription :</p>
    <a href="http://localhost:8000/confirmMail.php?token=$token&email=$email">Confirmer mon inscription</a>
HTML;
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From:  <rtiphonet@gmail.com>'
        
    ];
    mail($to, $subject, $message, implode("\r\n", $headers));
}
/**
 * Retourne vrai si la méthode d'appel est POST.
 */
function isPostMethod(): bool
{
    return  ($_SERVER['REQUEST_METHOD'] === 'POST') ;
}
/**
 * Affiche le footer du HTML.
 *
 * @return void
 */
function footer(): string
{
    return  <<<HTML_FOOTER
<footer></footer>
</body>
</html>
HTML_FOOTER;
}

function DecalageFour($date, $nb, $dbh) {
    // Séparer la date en jour, mois, année, heure et minute
    list($annee, $mois, $jour, $heure, $minute) = explode("-", explode(" ", $date)[0] . "-" . explode(" ", $date)[1]);

    // Vérifier si le four est fermé à cette heure-ci
    if ($heure < 12 || $heure >= 15) {
        return "Le four est fermé à cette heure-ci.";
    }

    // Vérifier le nombre de commandes prévues pour cette date et heure
    $sql_order = "SELECT * FROM `four` WHERE date = :date AND heure = :heure";
    $stmt_order = $dbh->prepare($sql_order);
    $stmt_order->bindParam(':date', $jour . "-" . $mois . "-" . $annee);
    $stmt_order->bindParam(':heure', $heure . ":" . $minute);
    $stmt_order->execute();
    $order = $stmt_order->fetch();

    // Si le nombre de commandes prévues est égal à 8, calculer le prochain créneau possible
    if ($order['nombre_fouees'] == 8) {
        $nextcreneau = calculerProchainCreneau($date, $dbh);
        return "Le four est plein pour ce créneau horaire, le prochain créneau possible est à : " . $nextcreneau;
    } elseif ($order['nombre_fouees'] + $nb > 8) {
        // Logique de répartition sur plusieurs créneaux
        $commandesRestantes = $nb;
        $creneauSuivant = 10; // Ajoutez 10 minutes pour le prochain créneau
        while ($commandesRestantes > 0) {
            $heureCreneauSuivant = $heure . ":" . str_pad($minute + $creneauSuivant, 2, '0', STR_PAD_LEFT);
            $orderCreneauSuivant = getOrderForCreneau($jour, $mois, $annee, $heureCreneauSuivant, $dbh);

            // Si le créneau suivant est disponible, enregistrez les commandes
            if ($orderCreneauSuivant['nombre_fouees'] < 8) {
                $commandesCreneau = min(8 - $orderCreneauSuivant['nombre_fouees'], $commandesRestantes);
                enregistrerCommandes($jour, $mois, $annee, $heureCreneauSuivant, $commandesCreneau, $dbh);
                $commandesRestantes -= $commandesCreneau;
            }

            $creneauSuivant += 10; // Passez au créneau suivant
        }
        return "Les commandes ont été réparties sur plusieurs créneaux.";
    } elseif ($order['nombre_fouees'] + $nb < 8) {
        return "Il reste " . (8 - $order['nombre_fouees']) . " fouées pour ce créneau horaire.";
    }
}

function calculerProchainCreneau($date, $dbh) {
    $sql = "SELECT * FROM `four` WHERE date = :date AND heure > :heure AND nombre_fouees < 8 ORDER BY heure ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':date', explode(" ", $date)[0]);
    $stmt->bindParam(':heure', explode(" ", $date)[1]);
    $stmt->execute();
    $order = $stmt->fetch();
    if ($order) {
        return $order['heure'];
    } else {
        return "Il n'y a plus de créneau disponible pour cette date.";
    }
}

function getOrderForCreneau($jour, $mois, $annee, $heure, $dbh) {
    $sql = "SELECT * FROM `four` WHERE date = :date AND heure = :heure";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':date', $jour . "-" . $mois . "-" . $annee);
    $stmt->bindParam(':heure', $heure);
    $stmt->execute();
    return $stmt->fetch();
}

function  enregistrerCommandes($jour, $mois, $annee, $heureCreneauSuivant, $commandesCreneau, $dbh){
    $sql = "INSERT INTO four (date, heure, nombre_fouees) VALUES ( :date, :heure, :nb) ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['date' => $annee . "-" . $mois . "-" . $jour, 'heure' => $heureCreneauSuivant, 'nb' => $commandesCreneau]);
}