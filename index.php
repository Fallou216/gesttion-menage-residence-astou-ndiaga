<?php
session_start();

// Redirection selon rôle si déjà connecté
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

<!-- Background animé avec particles.js -->
<div id="particles-js" style="position: fixed; width: 100%; height: 100%; z-index: -1;"></div>

<div class="container text-center d-flex flex-column justify-content-center align-items-center"
    style="min-height: 100vh;">

    <!-- Logo animé -->
    <img src="assets/images/Logo1.png" alt="Logo Résidence" class="mb-4 animate__animated animate__zoomIn"
        style="max-width: 220px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">

    <!-- Titres animés en cascade -->
    <h1 class="text-white animate__animated animate__fadeInDown animate__delay-1s">Bienvenue sur le site</h1>
    <h2 class="text-gold animate__animated animate__fadeInDown animate__delay-2s">Gestion de Ménage</h2>
    <h3 class="text-white animate__animated animate__fadeInDown animate__delay-3s">Résidence Astou & Ndiaga</h3>

    <!-- Message -->
    <p class="mt-4 text-white animate__animated animate__fadeInUp animate__delay-4s fs-5">
        Connectez-vous pour gérer les chambres.
    </p>

    <!-- Bouton de connexion -->
    <a href="login.php"
        class="btn btn-outline-light btn-lg mt-3 animate__animated animate__pulse animate__infinite animate__delay-5s">
        <i class="fas fa-sign-in-alt me-2"></i> Se connecter
    </a>
</div>

<!-- Styles personnalisés -->
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    overflow-x: hidden;
    background: #0d1117;
}

.text-gold {
    color: #ffcc66;
    font-weight: 700;
}

.btn-outline-light {
    border-width: 2px;
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background-color: #ffcc66;
    border-color: #ffcc66;
    color: #000;
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 768px) {
    h1 {
        font-size: 2.2rem;
    }

    h2 {
        font-size: 1.8rem;
    }

    h3 {
        font-size: 1.4rem;
    }

    .fs-5 {
        font-size: 1rem;
    }
}
</style>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script>
particlesJS("particles-js", {
    "particles": {
        "number": {
            "value": 80,
            "density": {
                "enable": true,
                "value_area": 800
            }
        },
        "color": {
            "value": "#ffcc66"
        },
        "shape": {
            "type": "circle"
        },
        "opacity": {
            "value": 0.5,
            "random": true
        },
        "size": {
            "value": 4,
            "random": true
        },
        "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#ffcc66",
            "opacity": 0.4,
            "width": 1
        },
        "move": {
            "enable": true,
            "speed": 2,
            "direction": "none",
            "random": true,
            "straight": false,
            "out_mode": "bounce"
        }
    },
    "interactivity": {
        "detect_on": "canvas",
        "events": {
            "onhover": {
                "enable": true,
                "mode": "repulse"
            },
            "onclick": {
                "enable": true,
                "mode": "push"
            },
            "resize": true
        },
        "modes": {
            "repulse": {
                "distance": 100
            },
            "push": {
                "particles_nb": 4
            }
        }
    },
    "retina_detect": true
});
</script>

<?php include 'includes/footer.php'; ?>