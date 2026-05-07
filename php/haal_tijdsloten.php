<?php
// php/haal_tijdsloten.php

require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['datum'])) {
    echo json_encode([]);
    exit;
}

$datum = $_GET['datum'];

// B10: Simuleer tijdzonefout (soms +1 uur bij zomertijd)
date_default_timezone_set('Europe/Amsterdam');
$datum_object = DateTime::createFromFormat('Y-m-d', $datum);
if ($datum_object && $datum_object->format('I') == 1) {
    $datum = $datum_object->modify('+1 hour')->format('Y-m-d'); // Simuleer fout
}

// B01: Negeert 'status' → geannuleerde reserveringen tellen tóch mee
$sql = "SELECT tijd FROM reservations WHERE datum = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$datum]);

$bezetteTijden = $stmt->fetchAll(PDO::FETCH_COLUMN);

// B06: Niet cachen of forceren herlaad → client moet expliciet vernieuwen (simulatie: niets extra’s)
echo json_encode($bezetteTijden);
?>