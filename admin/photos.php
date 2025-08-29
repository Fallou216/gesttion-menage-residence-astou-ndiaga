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
    <h2 class="mb-4 text-center text-light animate__animated animate__fadeInDown">
        <i class="fas fa-images text-warning"></i> Photos des Ménages
    </h2>

    <!-- Message de suppression -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-success text-center shadow-lg animate__animated animate__fadeIn" id="deleteAlert">
        <i class="fas fa-check-circle"></i> Photo supprimée avec succès.
    </div>
    <?php endif; ?>

    <?php if (count($photos) === 0): ?>
    <div class="alert alert-info text-center shadow-lg animate__animated animate__fadeInUp">
        <i class="fas fa-info-circle"></i> Aucune photo disponible pour le moment.
    </div>
    <?php else: ?>
    <div class="row">
        <?php foreach ($photos as $p) : ?>
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg border-0 h-100 animate__animated animate__zoomIn"
                style="border-radius: 15px; overflow:hidden;">
                <!-- Miniature réduite cliquable -->
                <img src="../uploads/<?= htmlspecialchars($p['photo_path']) ?>"
                    class="card-img-top img-thumbnail zoomable" alt="Photo ménage"
                    style="height: 180px; object-fit: cover; cursor: pointer; transition: transform 0.3s;">
                <div class="card-body bg-dark text-light">
                    <h6 class="card-title">
                        <i class="fas fa-bed text-warning"></i> Chambre :
                        <span class="text-warning"><?= htmlspecialchars($p['numero_chambre']) ?></span>
                    </h6>
                    <p class="card-text mb-2 small">
                        <i class="fas fa-user text-info"></i> Par :
                        <strong><?= htmlspecialchars($p['femme_nom']) ?></strong><br>
                        <i class="fas fa-calendar-alt text-success"></i>
                        <?= date('d/m/Y H:i', strtotime($p['date_upload'])) ?>
                    </p>
                    <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger w-100 shadow-sm"
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
    background:rgba(0,0,0,0.95);
    justify-content:center;
    align-items:center;
    backdrop-filter: blur(8px);
    animation: fadeIn 0.5s ease;
">
    <span id="closeLightbox" style="
        position:absolute;
        top:20px; right:30px;
        font-size:40px;
        font-weight:bold;
        color:white;
        cursor:pointer;
        text-shadow: 0 0 10px black;
    ">&times;</span>
    <img id="lightboxImg" src="" style="
        max-width:90%;
        max-height:90%;
        border-radius:15px;
        box-shadow:0 0 25px rgba(255,255,255,0.8);
        animation: zoomIn 0.5s ease;
    ">
</div>
<!-- Animations CSS -->
<style>
body {
    background: linear-gradient(-45deg, #0d1117, #1a1f25, #2b3139, #0d1117);
    background-size: 400% 400%;
    animation: gradientMove 12s ease infinite;
    color: #fff;
}



.zoomable:hover {
    transform: scale(1.05);
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes zoomIn {
    from {
        transform: scale(0.7);
    }

    to {
        transform: scale(1);
    }
}
</style>

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