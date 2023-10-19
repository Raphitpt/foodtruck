<?php
require_once 'vendor/autoload.php';

use Konekt\PdfInvoice\InvoicePrinter;

  $invoice = new InvoicePrinter('A4', '€', 'fr');
  
  /* Header settings */
  // $invoice->setLogo("images/sample1.jpg");   //logo image path
  $invoice->setColor("#007fff");      // pdf color scheme
  $invoice->setType("Facture");    // Invoice Type
  $invoice->setReference("INV-55033645");   // Reference
  $invoice->setDate(date('M dS ,Y',time()));   //Billing Date
  $invoice->setTime(date('h:i:s A',time()));   //Billing Time
  $invoice->setDue(date('M dS ,Y',strtotime('+3 months')));    // Due Date
  $invoice->setFrom(array("Tiphonet Raphaël","FoodTruck","5 allée des vignes","Trois-Palis , FR 16730"));
  $invoice->setTo(array("Marius ta mère","Acheteur","15 Rue de basseau","Angoulême , FR 16000"));
  
  $invoice->addItem("Fouée","Mougette",2,0,5,0,10);
  
  $invoice->addTotal("Total",10);
  $invoice->addTotal("TVA 5,5%",1);
  $invoice->addTotal("Montant total",11,true);
  
  $invoice->addBadge("Payé");
  
  $invoice->addTitle("Commentaire de livraison");
  
  $invoice->addParagraph("Vous pourrez venir chercher votre commande le 12/05/2021 à 12h30 au FoodTruck");
  
  $invoice->setFooternote("Le meilleur FoodTruck");
  $invoiceFileName = 'facture.pdf';
  $invoice->render($invoiceFileName, 'F');

// Envoyez la facture par e-mail
// Destinataire de l'e-mail
$to = 'mariusyvt@gmail.com';

// Sujet de l'e-mail
$subject = 'Facture';

// En-têtes MIME pour l'e-mail
$headers = "From: Votre Nom <votre_email@example.com>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

// Corps de l'e-mail
$message = "--boundary\r\n";
$message .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
$message .= "Content-Transfer-Encoding: 7bit\r\n";
$message .= "\r\n";
$message .= "Veuillez trouver ci-joint la facture en PDF.\r\n";
$message .= "\r\n";
$message .= "--boundary\r\n";

// Pièce jointe (facture en PDF)
$fileContent = file_get_contents($invoiceFileName);
$message .= "Content-Type: application/pdf\r\n";
$message .= "Content-Disposition: attachment; filename=\"$invoiceFileName\"\r\n";
$message .= "Content-Transfer-Encoding: base64\r\n";
$message .= "\r\n";
$message .= chunk_split(base64_encode($fileContent));
$message .= "\r\n";
$message .= "--boundary--\r\n";

// Envoyer l'e-mail avec la pièce jointe
if (mail($to, $subject, $message, $headers)) {
    echo 'Facture envoyée avec succès par e-mail.';
} else {
    echo 'Échec de l\'envoi de la facture par e-mail.';
}

// Supprimer le fichier PDF temporaire
unlink($invoiceFileName);
?>