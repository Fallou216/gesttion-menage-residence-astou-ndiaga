<?php
// Auth.php — vérifie que l’utilisateur est connecté
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>