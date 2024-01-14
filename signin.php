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
$infos = "SELECT * FROM settings";
$infos = $dbh->query($infos);
$infos = $infos->fetch();
echo head('Inscription');

?>


<body>
<header>
        <nav class="navfr">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p><?= $infos['nom_entreprise'] ?></p>
                </li>
                <li><button onclick="location.href = './accueil.php'" class="button_nav"><?= htmlspecialchars("Accueil") ?></button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav"><?= htmlspecialchars("Commander") ?></button></li>
                <li><button onclick="location.href = './contact.php'" class="button_nav"><?= htmlspecialchars("Nous contacter") ?></button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav"><?= htmlspecialchars("Back Office") ?></button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) { ?>
                    <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                <?php } else { ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect"><?= htmlspecialchars("Se connecter") ?></button></li>
                    <li><button onclick="location.href = './signin.php'" class="button_nav connect"><?= htmlspecialchars("S'inscrire") ?></button></li>
                <?php } ?>

            </ul>
        </nav>
        <nav style="display:none;" class="navang">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p><?= htmlspecialchars("Fouée't Moi") ?>
                </li>
                <li><button onclick="location.href = '#'" class="button_nav"><?= htmlspecialchars("Home") ?></button></li>
                <li><button onclick="location.href = './index.php'" class="button_nav"><?= htmlspecialchars("Order") ?></button></li>
                <li><button onclick="location.href = ''" class="button_nav"><?= htmlspecialchars("Contact us") ?></button></li>
                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                    <li><button onclick="location.href = 'indexBO.php'" class="button_nav"><?= htmlspecialchars("Back Office") ?></button></li>
                <?php endif; ?>
            </ul>
            <ul class="nav_right">
                <?php if (isset($_SESSION['email'])) { ?>
                    <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                <?php } else { ?>
                    <li><button onclick="location.href = './login.php'" class="button_nav connect"><?= htmlspecialchars("Connexion") ?></button></li>
                    <li><button onclick="location.href = './signin.php'" class="button_nav connect"><?= htmlspecialchars("Inscription") ?></button></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="menu-container">
            <ul class="nav_left">
                <li class="nav_title"><img src="<?= htmlspecialchars($infos['url_logo']) ?>" alt="logo fouee">
                    <p><?= htmlspecialchars("Fouée't Moi") ?>
                </li>
            </ul>
            <div class="menu-btn" id="menu-btn">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <nav class="menu">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="index.php">Commander</a></li>
                    <li><a href="contact.php">Nous contacter</a></li>
                    <?php if (isset($_SESSION['email'])) { ?>
                        <button onclick="location.href = 'profil.php'" class="image"><img src="<?php echo $photo['photoprofil'] == NULL ? "./assets/img/grandprofilfb.jpg" : $photo['photoprofil']; ?>" /></button>
                    <?php } else { ?>
                        <li><a href="login.php">Connexion/Inscription</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com') : ?>
                        <li><button onclick="location.href = 'indexBO.php'" class="button_nav"><?= htmlspecialchars("Back Office") ?></button></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
  <main>
    <div class="btn-retour">
      <a href="login.php" class="btn"><i class="fa-solid fa-arrow-left"></i></a>
    </div>

    <section class="form">
      <h1>S'inscrire</h1>
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
        <input type="password" name="confirm_password"><br>
        <input type="submit" value="S'inscrire">
      </form>
  </main>
  </section>


</body>

</html>