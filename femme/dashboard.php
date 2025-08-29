<?php
require_once 'auth_femme.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

$nom = $_SESSION['user']['nom'];

// Statistiques principales
$totalChambres = $pdo->query("SELECT COUNT(*) FROM chambres")->fetchColumn();
$totalFemmes = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'femme'")->fetchColumn();
$totalPhotosEnvoyees = $pdo->query("SELECT COUNT(*) FROM photos WHERE user_id = ".$_SESSION['user']['id'])->fetchColumn();

// Photos envoyées par mois
$photosParMois = $pdo->query("
    SELECT DATE_FORMAT(date_upload, '%Y-%m') AS mois, COUNT(*) AS total
    FROM photos
    WHERE user_id = ".$_SESSION['user']['id']."
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
    <h2 class="mb-4 text-center"><i class="fas fa-home"></i> Bienvenue <?= htmlspecialchars($nom) ?> 👋</h2>
    <p class="lead text-center">Gérez vos chambres et envoyez des preuves en photo de manière professionnelle.</p>

    <!-- Liens rapides -->
    <div class="row g-4 mt-3">
        <div class="col-md-6">
            <a href="chambres.php"
                class="btn btn-primary w-100 py-3 fs-5 animate__animated animate__pulse animate__infinite">
                <i class="fas fa-bed me-2"></i> Gérer les chambres
            </a>
        </div>
        <div class="col-md-6">
            <a href="uploader.php"
                class="btn btn-success w-100 py-3 fs-5 animate__animated animate__pulse animate__infinite"
                style="animation-delay:0.2s;">
                <i class="fas fa-upload me-2"></i> Envoyer une photo
            </a>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="row g-4 mt-4 text-center">
        <div class="col-md-4">
            <div
                class="card shadow-lg border-0 rounded-3 bg-gradient bg-primary text-white animate__animated animate__fadeInUp">
                <div class="card-body">
                    <i class="fas fa-bed fa-2x mb-2"></i>
                    <h5 class="card-title">Chambres Totales</h5>
                    <p class="fs-3 fw-bold"><?= $totalChambres ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3 bg-gradient bg-success text-white animate__animated animate__fadeInUp"
                style="animation-delay:0.2s;">
                <div class="card-body">
                    <i class="fas fa-user-friends fa-2x mb-2"></i>
                    <h5 class="card-title">Femmes de ménage</h5>
                    <p class="fs-3 fw-bold"><?= $totalFemmes ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3 bg-gradient bg-warning text-dark animate__animated animate__fadeInUp"
                style="animation-delay:0.4s;">
                <div class="card-body">
                    <i class="fas fa-camera fa-2x mb-2"></i>
                    <h5 class="card-title">Photos Envoyées</h5>
                    <p class="fs-3 fw-bold"><?= $totalPhotosEnvoyees ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-5 g-4">
        <div class="col-md-6">
            <div class="card shadow h-100 border-0 rounded-3 animate__animated animate__fadeInLeft">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-chart-line"></i> Photos envoyées par mois
                </div>
                <div class="card-body">
                    <canvas id="photosChart" style="height:180px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow h-100 border-0 rounded-3 animate__animated animate__fadeInRight">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-chart-pie"></i> Répartition
                </div>
                <div class="card-body">
                    <canvas id="repartitionChart" style="height:180px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique photos envoyées par mois
new Chart(document.getElementById('photosChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Photos envoyées',
            data: <?= json_encode($dataPhotos) ?>,
            borderColor: '#28a745',
            backgroundColor: 'rgba(40,167,69,0.2)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1500,
            easing: 'easeOutQuad'
        },
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

// Graphique circulaire (répartition)
new Chart(document.getElementById('repartitionChart'), {
    type: 'doughnut',
    data: {
        labels: ['Chambres', 'Photos envoyées', 'Femmes de ménage'],
        datasets: [{
            data: [<?= $totalChambres ?>, <?= $totalPhotosEnvoyees ?>, <?= $totalFemmes ?>],
            backgroundColor: ['#007bff', '#ffc107', '#28a745'],
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        animation: {
            animateRotate: true,
            animateScale: true
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 18,
                    padding: 10
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