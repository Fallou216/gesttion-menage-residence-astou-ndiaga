<?php
// includes/navbar.php
// session_start(); // Décommenter si pas déjà lancé dans index.php ou ailleurs
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-light shadow">
    <div class="container-fluid">

        <!-- Logo + Nom résidence -->
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="../assets/images/Logo1.png" alt="Logo Résidence" width="40" height="40"
                class="me-2 rounded-circle">
            <span class="fw-bold text-white">Résidence Astou & Ndiaga</span>
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
                    <a class="nav-link" href="dashboard.php">
                        <i class="fa-solid fa-gauge-high"></i> Tableau de bord
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="femmes.php">
                        <i class="fas fa-user"></i> Gestion Femmes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="photos.php">
                        <i class="fa-solid fa-image"></i> Photos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">
                        <i class="fa-solid fa-right-to-bracket"></i> Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register.php">
                        <i class="fa-solid fa-user-plus"></i> S'inscrire
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>