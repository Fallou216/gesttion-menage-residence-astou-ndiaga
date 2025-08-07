<?php
// includes/auth_femme.php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'femme') {
    header('Location: /login.php');
    exit();
}
?>