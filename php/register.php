<?php
 
require_once 'config.php';
 
$message = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $naam = trim($_POST['naam']);
    $email = trim($_POST['email']);
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoord_herhaal = $_POST['wachtwoord_herhaal'];
 
    // Check wachtwoorden
    if ($wachtwoord !== $wachtwoord_herhaal) {
 
        $message = '<div class="alert alert-danger">Wachtwoorden komen niet overeen.</div>';
 
    } else {
 
        // Check of email al bestaat
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
 
        if ($check->rowCount() > 0) {
 
            $message = '<div class="alert alert-warning">Dit e-mailadres bestaat al.</div>';
 
        } else {
 
            // Wachtwoord hashen
            $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);
 
            // Rol is nu "lid"
            $rol = "lid";
 
            // Insert gebruiker
            $stmt = $pdo->prepare("
                INSERT INTO users (naam, email, wachtwoord, rol)
                VALUES (?, ?, ?, ?)
            ");
 
            $success = $stmt->execute([
                $naam,
                $email,
                $hashedPassword,
                $rol
            ]);
 
            if ($success) {
                $message = '<div class="alert alert-success">Registratie succesvol!</div>';
               
            } else {
                $message = '<div class="alert alert-danger">Er ging iets fout.</div>';
            }
        }
    }
}
 
?>
 
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registreren - BeemBrug Connect</title>
 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>
 
<body>
 
  <nav class="navbar navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="index.html">BeemBrug Connect</a>
    </div>
  </nav>
 
  <div class="container mt-5">
 
    <h2 class="text-center">Account aanmaken</h2>
 
    <?php echo $message; ?>
 
    <form method="POST" id="registerForm">
 
      <div class="mb-3">
        <label for="naam" class="form-label">Naam</label>
        <input type="text" class="form-control" id="naam" name="naam" required />
      </div>
 
      <div class="mb-3">
        <label for="email" class="form-label">E-mailadres</label>
        <input type="email" class="form-control" id="email" name="email" required />
      </div>
 
      <div class="mb-3">
        <label for="wachtwoord" class="form-label">Wachtwoord</label>
        <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required />
      </div>
 
      <div class="mb-3">
        <label for="wachtwoord_herhaal" class="form-label">Herhaal wachtwoord</label>
        <input type="password" class="form-control" id="wachtwoord_herhaal" name="wachtwoord_herhaal" required />
      </div>
 
      <button type="submit" class="btn btn-primary w-100">
        Registreren
      </button>
 
    </form>
 
    <div class="text-center mt-3">
      <a href="../login.html">Ik heb al een account</a>
    </div>
 
  </div>
 
  <script src="js/scripts.js"></script>
 
</body>
</html>