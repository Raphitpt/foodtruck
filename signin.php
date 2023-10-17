<?php
require './bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  
  // Validate the form data (you can add more validation rules here)
  if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
    $error = 'Please fill in all fields';
  } else {
   $password = password_hash($password, PASSWORD_DEFAULT);
    // Insert form data in the database
    $sql = "INSERT INTO users (nom, prenom, email, passwd) VALUES (:nom, :prenom, :email, :passwd)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'passwd' => $password]);
    if ($stmt->rowCount() == 1) {
      $success = 'User registered successfully!';
      header('Location: index.php');
    } else {
      $error = 'Something went wrong!';
    }
  }
}

// Display the registration form
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
</head>
<body>
  <h1>Register</h1>
  <?php if (isset($error)) { ?>
    <p><?php echo $error; ?></p>
  <?php } ?>
  <form method="post">
    <label>Nom:</label>
    <input type="text" name="nom"><br>    
    <label>Prenom:</label>
    <input type="text" name="prenom"><br>
    <label>Email:</label>
    <input type="email" name="email"><br>
    <label>Password:</label>
    <input type="password" name="password"><br>
    <input type="submit" value="Register">
  </form>
</body>
</html>
