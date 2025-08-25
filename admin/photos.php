<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

// Suppression photo
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Récupérer le chemin de la photo
    $stmt = $pdo->prepare("SELECT photo_path FROM photos WHERE id = ?");
    $stmt->execute([$id]);
    $photo = $stmt->fetch();

    if ($photo) {
        $filePath = "../uploads/" . $photo['photo_path'];
        if (file_exists($filePath)) {
            unlink($filePath); // Supprimer physiquement le fichier
        }
        $pdo->prepare("DELETE FROM photos WHERE id = ?")->execute([$id]);
    }
    header("Location: photos.php?msg=deleted");
    exit;
}

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
    <h2 class="mb-4 text-center"><i class="fas fa-images"></i> Photos des Ménages</h2>

    <!-- Message de suppression -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-success text-center" id="deleteAlert">
        <i class="fas fa-check-circle"></i> Photo supprimée avec succès.
    </div>
    <?php endif; ?>

    <?php if (count($photos) === 0): ?>
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> Aucune photo disponible pour le moment.
    </div>
    <?php else: ?>
    <div class="row">
        <?php foreach ($photos as $p) : ?>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100">
                <!-- Miniature réduite cliquable -->
                <img src="../uploads/<?= htmlspecialchars($p['photo_path']) ?>"
                    class="card-img-top img-thumbnail zoomable" alt="Photo ménage"
                    style="height: 180px; object-fit: cover; cursor: pointer;">

                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-bed"></i> Chambre :
                        <span class="text-primary"><?= htmlspecialchars($p['numero_chambre']) ?></span>
                    </h6>
                    <p class="card-text mb-2">
                        <i class="fas fa-user"></i> Par :
                        <strong><?= htmlspecialchars($p['femme_nom']) ?></strong><br>
                        <i class="fas fa-calendar-alt"></i>
                        <?= date('d/m/Y H:i', strtotime($p['date_upload'])) ?>
                    </p>
                    <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger w-100"
                        onclick="return confirm('Voulez-vous vraiment supprimer cette photo ?');">
                        <i class="fas fa-trash-alt"></i> Supprimer
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Lightbox (agrandissement image) -->
<div id="lightbox" style="
    display:none;
    position:fixed;
    z-index:1050;
    left:0; top:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.9);
    justify-content:center;
    align-items:center;
">
    <span id="closeLightbox" style="
        position:absolute;
        top:20px; right:30px;
        font-size:40px;
        font-weight:bold;
        color:white;
        cursor:pointer;
    ">&times;</span>
    <img id="lightboxImg" src="" style="
        max-width:90%;
        max-height:90%;
        border-radius:10px;
        box-shadow:0 0 15px rgba(255,255,255,0.7);
    ">
</div>

<!-- Script -->
<script>
// Masquer le message après 10 secondes
setTimeout(() => {
    const alertBox = document.getElementById("deleteAlert");
    if (alertBox) {
        alertBox.style.transition = "opacity 1s ease";
        alertBox.style.opacity = "0";
        setTimeout(() => alertBox.remove(), 1000);
    }
}, 10000);

// Lightbox (zoom image)
document.querySelectorAll('.zoomable').forEach(img => {
    img.addEventListener('click', () => {
        document.getElementById('lightboxImg').src = img.src;
        document.getElementById('lightbox').style.display = 'flex';
    });
});

document.getElementById('closeLightbox').addEventListener('click', () => {
    document.getElementById('lightbox').style.display = 'none';
});

// Fermer si on clique en dehors de l'image
document.getElementById('lightbox').addEventListener('click', (e) => {
    if (e.target.id === 'lightbox') {
        document.getElementById('lightbox').style.display = 'none';
    }
});
</script>

<br><br><br>
<?php include '../includes/footer.php'; ?>