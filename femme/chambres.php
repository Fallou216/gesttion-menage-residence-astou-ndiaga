<?php
require_once 'auth_femme.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

// Mettre à jour le statut d'une chambre
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['chambre_id'];
    $statut_menage = $_POST['statut_menage'];
    $statut_disponibilite = $_POST['statut_disponibilite'];

    $stmt = $pdo->prepare("UPDATE chambres SET 
                          statut_menage = ?, 
                          statut_disponibilite = ?,
                          date_jour = CURDATE(),
                          heure = CURTIME()
                          WHERE id = ?");
    $stmt->execute([$statut_menage, $statut_disponibilite, $id]);
}

// Charger les chambres
$chambres = $pdo->query("SELECT * FROM chambres ORDER BY id DESC")->fetchAll();
?>

<div class="container mt-4">
    <h2><i class="fas fa-door-open"></i> Chambres à Gérer</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Chambre</th>
                <th>Étage</th>
                <th>Ménage</th>
                <th>Disponibilité</th>
                <th>Date/Heure</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chambres as $ch) : ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="chambre_id" value="<?= $ch['id'] ?>">
                    <td><?= htmlspecialchars($ch['numero_chambre']) ?></td>
                    <td><?= htmlspecialchars($ch['etage']) ?></td>
                    <td>
                        <select name="statut_menage" class="form-select">
                            <option value="menage" <?= $ch['statut_menage'] === 'menage' ? 'selected' : '' ?>>
                                <i class="fas fa-check-circle text-success"></i> Ménagée
                            </option>
                            <option value="non_menage" <?= $ch['statut_menage'] === 'non_menage' ? 'selected' : '' ?>>
                                <i class="fas fa-times-circle text-danger"></i> Non ménagée
                            </option>
                        </select>
                    </td>
                    <td>
                        <select name="statut_disponibilite" class="form-select">
                            <option value="disponible"
                                <?= $ch['statut_disponibilite'] === 'disponible' ? 'selected' : '' ?>>
                                <i class="fas fa-bed text-info"></i> Disponible
                            </option>
                            <option value="occupee" <?= $ch['statut_disponibilite'] === 'occupee' ? 'selected' : '' ?>>
                                <i class="fas fa-user-clock text-secondary"></i> Occupée
                            </option>
                        </select>
                    </td>
                    <td>
                        <?= $ch['date_jour'] ? date('d/m/Y', strtotime($ch['date_jour'])) : 'N/A' ?>
                        <br>
                        <?= $ch['heure'] ? substr($ch['heure'], 0, 5) : 'N/A' ?>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </td>
                </form>
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
<?php include '../includes/footer.php'; ?>