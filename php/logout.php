<?php
// logout.php
session_start();
session_unset(); // Alle sessievariabelen wissen
session_destroy(); // Sessie beëindigen

header("Location: ../login.html");
exit;
?>