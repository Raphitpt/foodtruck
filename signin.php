<?php
session_start();
require './bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];

  // Validate the form data (you can add more validation rules here)
  if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirmPassword)) {
    $error = 'Veuillez renseigner tous les champs';
  } elseif ($password !== $confirmPassword) {
    $error = 'Les mots de passe ne correspondent pas';
  } else {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16));
    // Insert form data in the database
    $sql = "INSERT INTO users (nom, prenom, email, passwd, mailverif) VALUES (:nom, :prenom, :email, :passwd, :mailverif)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'passwd' => $password, 'mailverif' => $token]);

    if ($stmt->rowCount() == 1) {
      $success = 'Vous êtes maintenant enregistré !';
      sendConfirmationMail($email, $token);
      $_SESSION['email'] = $email;
      header('Location: verifMail.php');
    } else {
      $error = "Quelque chose s'est mal passé...";
    }
  }
}

// Display the registration form
?>
<!DOCTYPE html>
<html>

<head>
  <title>Connectez-vous</title>
</head>

<body>
  <h1>Register</h1>
  <?php if (isset($error)) { ?>
    <p>
      <?php echo $error; ?>
    </p>
  <?php } ?>
  <form method="post">
    <label>Nom:</label>
    <input type="text" name="nom"><br>
    <label>Prénom:</label>
    <input type="text" name="prenom"><br>
    <label>Email:</label>
    <input type="email" name="email"><br>
    <label>Mot de passe:</label>
    <input type="password" name="password"><br>
    <label>Confirmation de Mot de passe</label>
    <input type="password" name="confirm_password">
    <input type="submit" value="Register">
  </form>
</body>

</html>