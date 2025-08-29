<?php
require_once 'auth_femme.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

$user_id = $_SESSION['user']['id'];

// Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $chambre_id = $_POST['chambre_id'];
    $photo = $_FILES['photo'];

    if ($photo['error'] === 0) {
        $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $destination = '../uploads/' . $filename;

        if (move_uploaded_file($photo['tmp_name'], $destination)) {
            $stmt = $pdo->prepare("INSERT INTO photos (user_id, chambre_id, photo_path) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $chambre_id, $filename]);
            $success = "Photo envoyée avec succès.";
        } else {
            $error = "Erreur lors du téléchargement.";
        }
    } else {
        $error = "Fichier invalide.";
    }
}

$chambres = $pdo->query("SELECT * FROM chambres ORDER BY numero_chambre ASC")->fetchAll();
?>

<!-- Styles d'animation -->
<style>
/* Fade-in général pour le container */
.container {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 1s forwards;
}

/* Animation pour les alertes */
.alert {
    opacity: 0;
    transform: translateY(-10px);
    animation: fadeInAlert 0.8s forwards;
}

/* Animation pour le formulaire */
form.row.g-3 {
    opacity: 0;
    transform: translateY(10px);
    animation: fadeInForm 1s forwards;
    animation-delay: 0.5s;
}

/* Animation boutons */
.btn {
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* Animation selects */
select.form-select {
    transition: all 0.3s ease;
}

select.form-select:focus {
    transform: scale(1.05);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

/* Animation keyframes */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInAlert {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInForm {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animation input file */
input[type="file"] {
    transition: transform 0.3s, box-shadow 0.3s;
}

input[type="file"]:focus {
    transform: scale(1.02);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const selects = document.querySelectorAll('select.form-select');
    selects.forEach(select => {
        select.addEventListener('blur', () => {
            select.style.transform = 'scale(1)';
        });
    });
});
</script>
<div class="container mt-4">
    <h2>
        <i class="fas fa-camera-retro"></i> Envoyer une photo de chambre ménagée
    </h2>
    <?php if (isset($success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?= $success ?>
    </div>
    <?php elseif (isset($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-door-open"></i> Chambre</label>
            <select name="chambre_id" class="form-select" required>
                <option value=""><i class="fas fa-list"></i> Choisir une chambre</option>
                <?php foreach ($chambres as $ch) : ?>
                <option value="<?= $ch['id'] ?>">
                    <i class="fas fa-bed"></i> <?= $ch['numero_chambre'] ?> - <?= $ch['etage'] ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-image"></i> Photo (jpeg, png)</label>
            <input type="file" name="photo" class="form-control" accept="image/*" required>
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-upload"></i> Envoyer
            </button>
        </div>
    </form>
</div>
<style>
body {
    background: linear-gradient(-45deg, #0d1117, #1a1f25, #2b3139, #0d1117);
    background-size: 400% 400%;
    animation: gradientMove 12s ease infinite;
    color: #fff;
}

h2 {
    color: #ffcc66;
    font-weight: 700;
    text-align: center;
}
</style>
<br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php include '../includes/footer.php'; ?>