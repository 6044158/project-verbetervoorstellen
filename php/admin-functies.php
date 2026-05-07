<?php
// admin-functies.php
require_once 'config.php';

function getAlleGebruikers($pdo) {
    $stmt = $pdo->query("SELECT id, naam, email, rol FROM users ORDER BY naam ASC");
    return $stmt->fetchAll();
}

function getAlleReserveringen($pdo) {
    $stmt = $pdo->query("
        SELECT r.id, u.naam AS gebruiker, r.activiteit, r.datum, r.tijd, r.status
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.datum DESC, r.tijd DESC
    ");
    return $stmt->fetchAll();
}
?>