<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: chambres.php");
    exit();
}

$id = $_GET['id'];

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero_chambre'];
    $etage = $_POST['etage'];
    $menage = $_POST['statut_menage'];
    $dispo = $_POST['statut_disponibilite'];
    
    $stmt = $pdo->prepare("UPDATE chambres SET 
                          numero_chambre = ?, 
                          etage = ?, 
                          statut_menage = ?, 
                          statut_disponibilite = ?,
                          date_jour = NOW(),
                          heure = CURRENT_TIME()
                          WHERE id = ?");
    $stmt->execute([$numero, $etage, $menage, $dispo, $id]);
    
    header("Location: chambres.php");
    exit();
}

// Récupération des données de la chambre (CORRECTION ICI)
$stmt = $pdo->prepare("SELECT * FROM chambres WHERE id = ?");
$stmt->execute([$id]);
$chambre = $stmt->fetch();

if (!$chambre) {
    header("Location: chambres.php");
    exit();
}

include '../includes/header.php';
include 'navbar.php';
?>

<div class="container mt-4">
    <h2>Modifier la chambre <?= htmlspecialchars($chambre['numero_chambre']) ?></h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Numéro de chambre</label>
            <input type="text" name="numero_chambre" class="form-control"
                value="<?= htmlspecialchars($chambre['numero_chambre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Étage</label>
            <input type="text" name="etage" class="form-control" value="<?= htmlspecialchars($chambre['etage']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Statut ménage</label>
            <select name="statut_menage" class="form-select">
                <option value="menage" <?= $chambre['statut_menage'] == 'menage' ? 'selected' : '' ?>>Menage</option>
                <option value="non_menage" <?= $chambre['statut_menage'] == 'non_menage' ? 'selected' : '' ?>>Non menage
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Disponibilité</label>
            <select name="statut_disponibilite" class="form-select">
                <option value="disponible" <?= $chambre['statut_disponibilite'] == 'disponible' ? 'selected' : '' ?>>
                    Disponible</option>
                <option value="occupee" <?= $chambre['statut_disponibilite'] == 'occupee' ? 'selected' : '' ?>>Occupée
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <a href="chambres.php" class="btn btn-secondary">
            <i class="fas fa-times"></i> Annuler
        </a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>