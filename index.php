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
?>

<?php include 'includes/header.php'; ?>

<!-- Page d’accueil design -->
<div class="container mt-5 text-center">
    <img src="assets/images/Logo1.png" alt="Logo Résidence" class="mb-4" style="max-width: 200px; border-radius: 12px;">

    <h1 class="text-white">Bienvenue sur le site</h1>
    <h2 class="text-gold">Gestion de Ménage</h2>
    <h3 class="text-white">Résidence Astou & Ndiaga</h3>

    <p class="mt-4 text-white">Veuillez vous connecter pour accéder à votre espace personnel.</p>
    <a href="login.php" class="btn btn-outline-light mt-3">
        <i class="fas fa-sign-in-alt"></i> Se connecter
    </a>
</div>
<br>
<br>
<?php include 'includes/footer.php'; ?>