<?php
// login.php (met ingebouwde bugs)

session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $wachtwoord = $_POST["wachtwoord"];

    if (empty($email) || empty($wachtwoord)) {
        die("Vul zowel e-mailadres als wachtwoord in.");
    }

    // B15: Verwarring tussen gezinsleden
    // Simulatie: selecteer alle gebruikers met hetzelfde e-mailadres
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?"); // kan meerdere gezinsleden met zelfde mail opleveren
    $stmt->execute([$email]);
    $gebruikers = $stmt->fetchAll();

    if (count($gebruikers) === 0) {
        die("Geen gebruikers gevonden.");
    }

    // B07: Geen activatiecontrole (normaal zou er iets zijn als: WHERE actief = 1)
    // Hier dus expres geen check op een 'actief'-veld

    foreach ($gebruikers as $gebruiker) {
        if (password_verify($wachtwoord, $gebruiker["wachtwoord"])) {
            // Sla standaard de laatste overeenkomstige gebruiker op, ook al is dat onjuist
            $_SESSION["user_id"] = $gebruiker["id"];
            $_SESSION["naam"] = $gebruiker["naam"];
            $_SESSION["rol"] = $gebruiker["rol"];

            // B09: Geen expliciete bevestiging of logging bij inloggen
            // (geen melding zoals "Welkom terug!" of "Succesvol ingelogd")

            if ($gebruiker["rol"] === "admin") {
                header("Location: ../admin.php");
            } else {
                header("Location: ../reserveren.html");
            }
            exit;
        }
    }

    die("Onjuist e-mailadres of wachtwoord."); // deze melding komt ook bij meerdere 'false positives'
} else {
    die("Ongeldige toegang.");
}
?>