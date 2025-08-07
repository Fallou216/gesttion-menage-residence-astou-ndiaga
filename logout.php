<?php
// logout.php
session_start();
session_unset();  // Supprime toutes les variables de session
session_destroy(); // Détruit complètement la session

// Redirection vers la page de connexion
header("Location: login.php");
exit();