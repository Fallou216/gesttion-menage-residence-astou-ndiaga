<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

// Statistiques principales
$totalChambres = $pdo->query("SELECT COUNT(*) FROM chambres")->fetchColumn();
$totalFemmes   = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'femme'")->fetchColumn();
$totalPhotos   = $pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
$totalAdmins   = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();

// Statistiques par mois pour les photos (12 derniers mois)
$photosParMois = $pdo->query("
    SELECT DATE_FORMAT(date_upload, '%Y-%m') AS mois, COUNT(*) AS total
    FROM photos
    GROUP BY mois
    ORDER BY mois DESC
    LIMIT 12
")->fetchAll();

$labels = [];
$dataPhotos = [];
foreach (array_reverse($photosParMois) as $row) {
    $labels[] = $row['mois'];
    $dataPhotos[] = $row['total'];
}
?>

<div class="container mt-4">
    <h2 class="mb-4 text-center animate__animated animate__fadeInDown">
        <i class="fas fa-tachometer-alt"></i> Tableau de Bord - Admin
    </h2>

    <!-- Cartes statistiques -->
    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div
                class="card shadow-lg border-0 rounded-3 text-white bg-gradient bg-primary h-100 animate__animated animate__zoomIn">
                <div class="card-body">
                    <i class="fas fa-bed fa-2x mb-2"></i>
                    <h5 class="card-title">Chambres</h5>
                    <p class="fs-3 fw-bold"><?= $totalChambres ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-lg border-0 rounded-3 text-white bg-gradient bg-success h-100 animate__animated animate__zoomIn"
                style="animation-delay:0.2s;">
                <div class="card-body">
                    <i class="fas fa-user-friends fa-2x mb-2"></i>
                    <h5 class="card-title">Femmes de ménage</h5>
                    <p class="fs-3 fw-bold"><?= $totalFemmes ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-lg border-0 rounded-3 text-dark bg-gradient bg-warning h-100 animate__animated animate__zoomIn"
                style="animation-delay:0.4s;">
                <div class="card-body">
                    <i class="fas fa-camera fa-2x mb-2"></i>
                    <h5 class="card-title">Photos</h5>
                    <p class="fs-3 fw-bold"><?= $totalPhotos ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-lg border-0 rounded-3 text-white bg-gradient bg-danger h-100 animate__animated animate__zoomIn"
                style="animation-delay:0.6s;">
                <div class="card-body">
                    <i class="fas fa-user-shield fa-2x mb-2"></i>
                    <h5 class="card-title">Admins</h5>
                    <p class="fs-3 fw-bold"><?= $totalAdmins ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-5 g-4">
        <div class="col-md-6">
            <div class="card shadow h-100 border-0 rounded-3 animate__animated animate__fadeInLeft">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-chart-line"></i> Photos ajoutées par mois
                </div>
                <div class="card-body">
                    <canvas id="photosChart" style="height:220px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow h-100 border-0 rounded-3 animate__animated animate__fadeInRight">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-chart-pie"></i> Répartition des utilisateurs
                </div>
                <div class="card-body">
                    <canvas id="usersChart" style="height:220px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Autres graphiques -->
    <div class="row mt-5 g-4">
        <div class="col-md-6">
            <div class="card shadow h-100 border-0 rounded-3 animate__animated animate__fadeInUp">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-chart-bar"></i> Comparaison Femmes vs Chambres
                </div>
                <div class="card-body">
                    <canvas id="compareChart" style="height:220px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow h-100 border-0 rounded-3 animate__animated animate__fadeInUp"
                style="animation-delay:0.3s;">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-bullseye"></i> Vue Radar des Ressources
                </div>
                <div class="card-body">
                    <canvas id="radarChart" style="height:220px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Animation configuration
const animationCfg = {
    duration: 2000,
    easing: 'easeOutBounce'
};

// Graphique en ligne
new Chart(document.getElementById('photosChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Nombre de photos',
            data: <?= json_encode($dataPhotos) ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0,123,255,0.2)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#007bff',
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        animation: animationCfg,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

// Graphique circulaire
new Chart(document.getElementById('usersChart'), {
    type: 'doughnut',
    data: {
        labels: ['Femmes de ménage', 'Admins'],
        datasets: [{
            data: [<?= $totalFemmes ?>, <?= $totalAdmins ?>],
            backgroundColor: ['#28a745', '#dc3545'],
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        animation: animationCfg,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique en barres
new Chart(document.getElementById('compareChart'), {
    type: 'bar',
    data: {
        labels: ['Femmes de ménage', 'Chambres'],
        datasets: [{
            label: 'Quantité',
            data: [<?= $totalFemmes ?>, <?= $totalChambres ?>],
            backgroundColor: ['#28a745', '#007bff']
        }]
    },
    options: {
        responsive: true,
        animation: animationCfg,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Radar chart
new Chart(document.getElementById('radarChart'), {
    type: 'radar',
    data: {
        labels: ['Chambres', 'Femmes', 'Photos', 'Admins'],
        datasets: [{
            label: 'Ressources',
            data: [<?= $totalChambres ?>, <?= $totalFemmes ?>, <?= $totalPhotos ?>,
                <?= $totalAdmins ?>
            ],
            backgroundColor: 'rgba(255,193,7,0.2)',
            borderColor: '#ffc107',
            borderWidth: 2,
            pointBackgroundColor: '#ffc107',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        animation: animationCfg,
        scales: {
            r: {
                angleLines: {
                    color: '#eee'
                },
                grid: {
                    color: '#ddd'
                }
            }
        }
    }
});
</script>
<style>
h2 {
    color: #ffcc66;
    font-weight: 700;
}
</style>
<br><br>
<?php include 'footer.php'; ?>