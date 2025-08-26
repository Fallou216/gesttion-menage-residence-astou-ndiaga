<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

// Ajouter une chambre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero_chambre'])) {
    $num = $_POST['numero_chambre'];
    $etage = $_POST['etage'];

    $stmt = $pdo->prepare("INSERT INTO chambres (numero_chambre, etage) VALUES (?, ?)");
    $stmt->execute([$num, $etage]);
}

// Supprimer une chambre
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM chambres WHERE id = ?")->execute([$id]);
}

// Récupérer les chambres
$chambres = $pdo->query("SELECT * FROM chambres ORDER BY id DESC")->fetchAll();
?>

<div class="container mt-4">
    <h2>
        <i class="fas fa-bed"></i> Gestion des Chambres
    </h2>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="numero_chambre" class="form-control" placeholder="N° Chambre" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="etage" class="form-control" placeholder="Étage">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-plus-circle"></i> Ajouter
            </button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Numéro</th>
                <th>Étage</th>
                <th>Statut ménage</th>
                <th>Disponibilité</th>
                <th>Date/Heure</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chambres as $ch) : ?>
            <tr>
                <td><?= $ch['id'] ?></td>
                <td><?= $ch['numero_chambre'] ?></td>
                <td><?= $ch['etage'] ?></td>
                <td>
                    <span class="badge bg-<?= $ch['statut_menage'] == 'menage' ? 'success' : 'danger' ?>">
                        <?= ucfirst($ch['statut_menage']) ?>
                    </span>
                </td>
                <td>
                    <span class="badge bg-<?= $ch['statut_disponibilite'] == 'disponible' ? 'info' : 'secondary' ?>">
                        <?= ucfirst($ch['statut_disponibilite']) ?>
                    </span>
                </td>
                <td>
                    <?= $ch['date_jour'] ? date('d/m/Y', strtotime($ch['date_jour'])) : 'N/A' ?><br>
                    <?= $ch['heure'] ? substr($ch['heure'], 0, 5) : 'N/A' ?>
                </td>
                <td>
                    <a href="edit_chambre.php?id=<?= $ch['id'] ?>" class="btn btn-sm btn-warning" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="?delete=<?= $ch['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer"
                        onclick="return confirm('Supprimer cette chambre ?')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
<?php include '../includes/footer.php'; ?>