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

<!-- Styles d'animation -->
<style>
/* Animation fade-in pour tout le container */
.container {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 1s forwards;
}

/* Animation pour les lignes du tableau */
.table tbody tr {
    opacity: 0;
    transform: translateX(-20px);
    animation: fadeInRow 0.6s forwards;
}

/* Délai pour chaque ligne */
<?php foreach ($chambres as $index=> $ch) : ?>.table tbody tr:nth-child(<?=$index + 1 ?>) {
    animation-delay: <?=($index + 1) * 0.1 ?>s;
}

<?php endforeach;
?>

/* Animation pour les boutons */
.btn {
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* Animation keyframes */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInRow {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Animation hover sur les lignes du tableau */
.table tbody tr:hover {
    background-color: rgba(255, 204, 102, 0.1);
    /* léger survol */
    transition: background-color 0.3s;
}

/* Animation sur les selects au focus */
select.form-select {
    transition: all 0.3s ease;
}

select.form-select:focus {
    transform: scale(1.05);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}
</style>

<!-- Script pour animation des selects -->
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
<style>
body {
    background: linear-gradient(-45deg, #0d1117, #1a1f25, #2b3139, #0d1117);
    background-size: 400% 400%;
    animation: gradientMove 12s ease infinite;
    color: #fff;
}

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

<br><br><br><br><br><br><br><br><br><br>
<?php include 'footer.php'; ?>