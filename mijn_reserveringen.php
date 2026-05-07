<?php
session_start();
require_once 'php/config.php';

// Gebruiker moet ingelogd zijn
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION["user_id"];

// Haal reserveringen van deze gebruiker op
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ? ORDER BY datum DESC, tijd DESC");
$stmt->execute([$user_id]);
$reserveringen = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mijn Reserveringen - BeemBrug Connect</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <nav class="navbar navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="index.html">BeemBrug Connect</a>
      <a href="php/logout.php" class="btn btn-outline-light">Uitloggen</a>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="text-center">Mijn Reserveringen</h2>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">Reservering succesvol toegevoegd!</div>
    <?php endif; ?>

    <?php if (isset($_GET['geannuleerd'])): ?>
      <div class="alert alert-warning">Reservering is geannuleerd.</div>
    <?php endif; ?>

    <table class="table table-striped table-reserveringen mt-4">
      <thead>
        <tr>
          <th>Activiteit</th>
          <th>Datum</th>
          <th>Tijd</th>
          <th>Status</th>
          <th>Actie</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reserveringen as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r["activiteit"]) ?></td>
          <td><?= htmlspecialchars($r["datum"]) ?></td>
          <td><?= htmlspecialchars(substr($r["tijd"], 0, 5)) ?></td>
          <td class="<?= $r["status"] === "geboekt" ? 'status-geboekt' : 'status-geannuleerd' ?>">
            <?= ucfirst($r["status"]) ?>
          </td>
          <td>
            <?php if ($r["status"] === "geboekt"): ?>
              <a href="php/annuleer.php?id=<?= $r["id"] ?>" class="btn btn-sm btn-danger">Annuleer</a>
            <?php else: ?>
              <em>n.v.t.</em>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>