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

<div class="container mt-4">
    <h2>Envoyer une photo de chambre ménagée</h2>

    <?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Chambre</label>
            <select name="chambre_id" class="form-select" required>
                <option value="">Choisir une chambre</option>
                <?php foreach ($chambres as $ch) : ?>
                <option value="<?= $ch['id'] ?>"><?= $ch['numero_chambre'] ?> - <?= $ch['etage'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Photo (jpeg, png)</label>
            <input type="file" name="photo" class="form-control" accept="image/*" required>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success">Envoyer</button>
        </div>
    </form>
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
<?php include '../includes/footer.php'; ?>