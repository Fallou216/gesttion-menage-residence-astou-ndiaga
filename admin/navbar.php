<?php
// includes/navbar.php
// session_start(); // Décommenter si pas déjà lancé dans index.php ou ailleurs
?>

<style>
/* Navbar moderne et professionnelle */
.navbar {
    background-color: #000;
    /* noir profond */
    border-bottom: 2px solid #ffcc00;
    /* ligne or */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
    transition: all 0.3s ease;
}

.navbar-brand span {
    color: #ffcc00;
    /* texte or */
    font-size: 1.25rem;
    font-weight: 700;
    transition: transform 0.3s ease, text-shadow 0.3s ease;
}

.navbar-brand:hover span {
    transform: scale(1.1);
    text-shadow: 0 0 8px #ffc107;
}

.nav-link {
    color: #ffcc00 !important;
    font-weight: 500;
    position: relative;
    transition: all 0.3s ease;
}

.nav-link i {
    margin-right: 6px;
    transition: transform 0.3s ease, color 0.3s ease;
}

.nav-link:hover {
    color: #fff !important;
}

.nav-link:hover i {
    transform: rotate(20deg) scale(1.2);
    color: #ffc107;
}

/* Navbar toggler */
.navbar-toggler {
    border-color: #ffcc00;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,204,0,1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/ %3E%3C/svg%3E");
}

/* Hover pour mobile */
@media (max-width: 992px) {
    .nav-link:hover {
        color: #ffc107 !important;
    }
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container-fluid">

        <!-- Logo + Nom résidence -->
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="../assets/images/Logo1.png" alt="Logo Résidence" width="45" height="45"
                class="me-2 rounded-circle border border-warning shadow-sm">
            <span>Résidence Astou & Ndiaga</span>
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
                    <a class="nav-link" href="chambres.php">
                        <i class="fas fa-bed"></i> Gestion Chambres
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