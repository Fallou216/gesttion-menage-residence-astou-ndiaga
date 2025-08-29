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

<!-- AOS Animation CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
/* 🌟 Background animé */
body {
    background: linear-gradient(-45deg, #0d1117, #1a1f25, #2b3139, #0d1117);
    background-size: 400% 400%;
    animation: gradientMove 12s ease infinite;
    color: #fff;
}

@keyframes gradientMove {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

/* ---- Styles existants améliorés ---- */
h2 {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 25px;
    animation: fadeInDown 1s ease-in-out;
    color: #ffcc66;
}

.form-control {
    border-radius: 12px;
    transition: 0.3s ease;
}

.form-control:focus {
    box-shadow: 0 0 10px rgba(255, 204, 102, 0.7);
    transform: scale(1.03);
}

.btn {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 204, 102, 0.4);
}

.table {
    border-radius: 12px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(6px);
    color: #fff;
}

thead tr {
    animation: fadeInDown 1s ease-in-out;
}

tbody tr {
    transition: 0.3s ease;
}

tbody tr:hover {
    background-color: rgba(255, 204, 102, 0.1);
    transform: scale(1.01);
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<div class="container mt-4">
    <h2 data-aos="fade-right">
        <i class="fas fa-bed"></i> Gestion des Chambres
    </h2>
    <form method="POST" class="row g-3 mb-4" data-aos="zoom-in">
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

    <table class="table table-bordered table-hover shadow-lg" data-aos="fade-up" data-aos-delay="200">
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
            <tr data-aos="fade-up" data-aos-delay="100">
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

<!-- AOS Animation JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({
    duration: 900,
    once: true
});
</script>
<br><br><br><br><br><br><br><br><br><br>
<?php include '../includes/footer.php'; ?>