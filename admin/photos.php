<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Requête pour récupérer les photos avec les infos utiles
$photos = $pdo->query("
  SELECT photos.*, users.nom AS femme_nom, chambres.numero_chambre
  FROM photos
  JOIN users ON photos.user_id = users.id
  JOIN chambres ON photos.chambre_id = chambres.id
  ORDER BY photos.date_upload DESC
")->fetchAll();
?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Photos des Ménages</h2>

    <?php if (count($photos) === 0): ?>
    <div class="alert alert-info text-center">Aucune photo disponible pour le moment.</div>
    <?php else: ?>
    <div class="row">
        <?php foreach ($photos as $p) : ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="../uploads/<?= htmlspecialchars($p['photo_path']) ?>" class="card-img-top" alt="Photo ménage">
                <div class="card-body">
                    <h6 class="card-title">Chambre : <?= htmlspecialchars($p['numero_chambre']) ?></h6>
                    <p class="card-text">
                        Par : <strong><?= htmlspecialchars($p['femme_nom']) ?></strong><br>
                        Date : <?= date('d/m/Y H:i', strtotime($p['date_upload'])) ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>