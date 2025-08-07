<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Compter les chambres, femmes, photos
$totalChambres = $pdo->query("SELECT COUNT(*) FROM chambres")->fetchColumn();
$totalFemmes = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'femme'")->fetchColumn();
$totalPhotos = $pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
?>

<div class="container mt-4">
    <h2 class="mb-4">Tableau de Bord - Admin</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Chambres</h5>
                    <p class="card-text fs-3"><?= $totalChambres ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Femmes de ménage</h5>
                    <p class="card-text fs-3"><?= $totalFemmes ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Photos</h5>
                    <p class="card-text fs-3"><?= $totalPhotos ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>