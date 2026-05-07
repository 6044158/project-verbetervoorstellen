<?php
// reserveren.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$activiteit = $_POST['activiteit'] ?? '';
$datum = $_POST['datum'] ?? '';
$tijd = $_POST['tijd'] ?? '';
$instructeur = $_POST['instructeur_naam'] ?? null;

// Datumformaat fixen (B05)
try {
    $datumObj = DateTime::createFromFormat('Y-m-d', $datum);
    if (!$datumObj) {
        throw new Exception("Ongeldige datum");
    }
    $datum = $datumObj->format('Y-m-d');
} catch (Exception $e) {
    die("Fout in datum: " . $e->getMessage());
}

// Controleer of er al een reservering bestaat op deze datum/tijd/activiteit
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE datum = ? AND tijd = ? AND activiteit = ?");
$stmt->execute([$datum, $tijd, $activiteit]);
$aantal = $stmt->fetchColumn();

if ($aantal > 0) {
    echo "Dit tijdslot is al gereserveerd.";
    
    // B11 - ondanks foutmelding wordt de reservering toch opgeslagen:
    // (let op: normaal zou hier 'exit' moeten staan!)
}

// B17 - instructeurveld wordt genegeerd in de query!
$sql = "INSERT INTO reservations (user_id, activiteit, datum, tijd) VALUES (?, ?, ?, ?)"; // instructeur mist!
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $activiteit, $datum, $tijd]);

// B05 - verkeerde tijd in bevestiging
$fouteTijd = date("H:i", strtotime($tijd . " +1 hour")); // simuleer tijdverschuiving
echo "<h2>Reservering gelukt!</h2>";
echo "<p>Activiteit: $activiteit</p>";
echo "<p>Datum: " . date("d-m-Y", strtotime($datum)) . "</p>";
echo "<p><strong>Tijd (verkeerd!): $fouteTijd</strong></p>"; // B05
echo "<p><a href='../reserveren.html'>← Terug naar reserveren</a></p>";
?>