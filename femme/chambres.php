<?php
require_once 'auth_femme.php';
require_once '../includes/db.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Mettre à jour le statut d’une chambre
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['chambre_id'];
    $statut_menage = $_POST['statut_menage'];
    $statut_disponibilite = $_POST['statut_disponibilite'];

    $stmt = $pdo->prepare("UPDATE chambres SET statut_menage = ?, statut_disponibilite = ? WHERE id = ?");
    $stmt->execute([$statut_menage, $statut_disponibilite, $id]);
}

// Charger les chambres
$chambres = $pdo->query("SELECT * FROM chambres ORDER BY id DESC")->fetchAll();
?>

<div class="container mt-4">
    <h2>Chambres à Gérer</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Chambre</th>
                <th>Étage</th>
                <th>Ménage</th>
                <th>Disponibilité</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chambres as $ch) : ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="chambre_id" value="<?= $ch['id'] ?>">
                    <td><?= $ch['numero_chambre'] ?></td>
                    <td><?= $ch['etage'] ?></td>
                    <td>
                        <select name="statut_menage" class="form-select">
                            <option value="menage" <?= $ch['statut_menage'] === 'menage' ? 'selected' : '' ?>>Ménagée
                            </option>
                            <option value="non_menage" <?= $ch['statut_menage'] === 'non_menage' ? 'selected' : '' ?>>
                                Non ménagée</option>
                        </select>
                    </td>
                    <td>
                        <select name="statut_disponibilite" class="form-select">
                            <option value="disponible"
                                <?= $ch['statut_disponibilite'] === 'disponible' ? 'selected' : '' ?>>Disponible
                            </option>
                            <option value="occupee" <?= $ch['statut_disponibilite'] === 'occupee' ? 'selected' : '' ?>>
                                Occupée</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>