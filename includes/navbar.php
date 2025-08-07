<?php
// includes/navbar.php
//session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
        <!-- Logo + Nom résidence -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <!--<img src="/assets/images/Logo1.png" alt="Logo Résidence" width="40" height="40" class="me-2 rounded-circle">-->
            <span class="fw-bold">Résidence Astou et Ndiaga</span>
        </a>

        <!-- Bouton menu mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Déconnexion</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Connexion</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>