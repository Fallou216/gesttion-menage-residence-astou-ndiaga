<?php
// index.php
session_start();

// Si l'utilisateur est déjà connecté, on le redirige selon son rôle
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: /admin/dashboard.php');
        exit();
    } elseif ($_SESSION['user']['role'] === 'femme') {
        header('Location: /femme/dashboard.php');
        exit();
    }
}

// Sinon, on redirige vers la page de connexion
//header('Location: /login.php');
exit();
?>