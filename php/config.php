<?php
// config.php – met opzettelijke fouten voor testdoeleinden

$host = 'localhost';
$db   = 'beembrug';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

// Bug B07/B09: Geen SMTP-config, mislukte mailfunctionaliteit
// (In werkelijkheid zou je PHPMailer of SMTP-instellingen gebruiken, maar hier ontbreekt dit)

// DSN opbouw
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Foutgevoelige opties
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => true  // Buggevoelig: emulatie aan i.p.v. prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Log fout maar toon generieke melding (of soms niets tonen = Bug B09)
    error_log("Databaseverbinding fout: " . $e->getMessage());

    // Bug B09: Geen foutmelding voor gebruiker
    // echo "Databaseverbinding mislukt.";
    exit;
}

// Bug B14: Simuleer dubbele notificatie (in een andere context gebruikt bij reserveringen)
// Bijvoorbeeld:
function stuurBeheerderNotificatie($boodschap) {
    // Eerste (legitieme) mail
    mail("beheerder@beembrug.nl", "Nieuwe actie", $boodschap);

    // Bug B14: Simuleer per ongeluk een tweede (dubbele) mail
    mail("beheerder@beembrug.nl", "Nieuwe actie (kopie)", $boodschap);
}
?>