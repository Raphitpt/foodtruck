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
  
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/style.css">
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
    return ($_SERVER['REQUEST_METHOD'] === 'GET');
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
    return ($_SERVER['REQUEST_METHOD'] === 'POST');
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



function calculerProchainCreneau($date, $dbh)
{
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

function getOrderForCreneau($jour, $mois, $annee, $heure, $dbh)
{
    $sql = "SELECT * FROM `four` WHERE date = :date AND heure = :heure";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':date', $jour . "-" . $mois . "-" . $annee);
    $stmt->bindParam(':heure', $heure);
    $stmt->execute();
    return $stmt->fetch();
}

function  enregistrerCommandes($jour, $mois, $annee, $heureCreneauSuivant, $commandesCreneau, $dbh)
{
    $sql = "INSERT INTO four (date, heure, nombre_fouees) VALUES ( :date, :heure, :nb) ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['date' => $annee . "-" . $mois . "-" . $jour, 'heure' => $heureCreneauSuivant, 'nb' => $commandesCreneau]);
}
