<?php
require_once 'auth_femme.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

$nom = $_SESSION['user']['nom'];
?>

<div class="container mt-4">
    <h2>Bienvenue <?= htmlspecialchars($nom) ?> 👋</h2>
    <p class="lead">Vous pouvez gérer les chambres à nettoyer et envoyer des preuves en photo.</p>

    <div class="row g-4 mt-3">
        <div class="col-md-6">
            <a href="chambres.php" class="btn btn-primary w-100">
                <i class="fa-solid fa-bed me-2"></i> Gérer les chambres
            </a>
        </div>
        <div class="col-md-6">
            <a href="uploader.php" class="btn btn-success w-100">
                <i class="fa-solid fa-upload me-2"></i> Envoyer une photo
            </a>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php include '../includes/footer.php'; ?>