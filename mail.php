<?php
// Inclusion de la bibliothèque mPDF (assurez-vous de l'avoir installée)
require_once __DIR__ . '/vendor/autoload.php'; 

// Récupération des données depuis un formulaire POST (à adapter selon votre cas)
$nom = 'Yvart';
$prenom = 'Marius';
$details_commande = 'fjqjdioqzd';
$heure_retrait = '12h35';
$email_client = 'rtiphonet@gmail.com';

// Génération de la facture en HTML
$html = "
<!DOCTYPE html>
<html>
<head>
    <title>Facture</title>
</head>
<body>
    <h1>Facture</h1>
    <p>Nom: $nom</p>
    <p>Prénom: $prenom</p>
    <p>Détails de la commande: $details_commande</p>
    <p>Heure de retrait: $heure_retrait</p>
</body>
</html>
";

// Génération du PDF avec mPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$pdf = $mpdf->Output('', 'S');

// Envoi de l'e-mail avec la facture en pièce jointe
$to = $email_client;
$subject = "Facture de commande";

$fileatttype = "application/pdf";
$fileattname = "Facture.pdf";

$headers = "From: Votre Nom <votre@email.com>\r\n";
$headers .= "Reply-To: votre@email.com\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers .= "MIME-Version: 1.0\r\n";

$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
$headers .= "Content-Type: multipart/mixed;\n" .
  " boundary={$mime_boundary}";

$message = "Ceci est un message en format MIME multipart.\n\n" .
  "--{$mime_boundary}\n" .
  "Content-type:text/html;charset=UTF-8\n" .
  "Content-Transfer-Encoding: 7bit\n\n" .
  $html. "\n\n";

$data = chunk_split(base64_encode($pdf));
$message .= "--{$mime_boundary}\n" .
  "Content-Type: {$fileatttype};\n" .
  " name={$fileattname}\n" .
  "Content-Disposition: attachment;\n" .
  " filename={$fileattname}\n" .
  "Content-Transfer-Encoding: base64\n\n" .
  $data . "\n\n" .
  "--{$mime_boundary}--\n";

// Envoyer l'e-mail
if (mail($to, $subject, $message, $headers)) {
  echo "Facture envoyée avec succès.";
} else {
  $error = error_get_last();
  if ($error) {
    echo "Erreur lors de l'envoi de la facture : " . $error['message'];
  } else {
    echo "Erreur inconnue lors de l'envoi de la facture.";
  }
}
?>
