<?php
// annuleer.php
session_start();
require_once 'config.php';

if (!isset($_SESSION["user_id"])) {
    die("Je moet ingelogd zijn om een reservering te annuleren.");
}

if (!isset($_GET["id"])) {
    die("Geen reserverings-ID opgegeven.");
}

$reservering_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Controleer of deze reservering bij de ingelogde gebruiker hoort
$stmt = $pdo->prepare("SELECT id FROM reservations WHERE id = ? AND user_id = ?");
$stmt->execute([$reservering_id, $user_id]);
$reservering = $stmt->fetch();

if (!$reservering) {
    die("Deze reservering bestaat niet of behoort niet tot jouw account.");
}

// Status bijwerken naar geannuleerd
$stmt = $pdo->prepare("UPDATE reservations SET status = 'geannuleerd' WHERE id = ?");
$stmt->execute([$reservering_id]);

header("Location: ../mijn_reserveringen.php?geannuleerd=1");
exit;
?>