<?php
// admin.php
session_start();
require_once 'php/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: login.html");
    exit;
}

// Zoekfilter
$zoekterm = $_GET['zoek'] ?? '';
$query = "SELECT * FROM users";
$params = [];

if (!empty($zoekterm)) {
    // B18: Filtering werkt niet goed → hoofdlettergevoelig zoeken
    $query .= " WHERE naam LIKE ?";
    $params[] = "%$zoekterm%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$gebruikers = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <title>Beheer - BeemBrug Connect</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body>
  <div class="container mt-5">
    <h2>Beheerpagina</h2>
    <form method="GET" class="mb-3">
      <input type="text" name="zoek" placeholder="Zoek op naam" value="<?= htmlspecialchars($zoekterm) ?>" class="form-control" />
    </form>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Naam</th>
          <th>E-mail</th>
          <th>Rol</th>
          <th>Actie</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gebruikers as $gebruiker): ?>
          <tr>
            <td><?= htmlspecialchars($gebruiker['naam']) ?></td>
            <td><?= htmlspecialchars($gebruiker['email']) ?></td>
            <td><?= htmlspecialchars($gebruiker['rol']) ?></td>
            <td>
              <!-- B17: Verwijderknop werkt niet (geen name/submit) -->
              <form method="POST" action="php/verwijder.php">
                <input type="hidden" name="id" value="<?= $gebruiker['id'] ?>">
                <button class="btn btn-danger">Verwijder</button>
                <!-- Bug: ontbreekt name="verwijderen" of type="submit" -->
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="reserveren.html" class="btn btn-secondary mt-3">Terug</a>
  </div>
</body>
</html>