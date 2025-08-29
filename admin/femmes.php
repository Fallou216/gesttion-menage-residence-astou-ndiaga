<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

// Ajouter une femme de ménage
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'femme')");
    $stmt->execute([$nom, $email, $password]);
}

// Supprimer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
}

// Modifier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET nom = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $password, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET nom = ?, email = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $id]);
    }
}

$femmes = $pdo->query("SELECT * FROM users WHERE role = 'femme' ORDER BY id DESC")->fetchAll();
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

/* ---- Styles ---- */
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

/* Modal */
.modal-content {
    border-radius: 15px;
    background: rgba(30, 30, 30, 0.9);
    color: #fff;
    backdrop-filter: blur(8px);
    animation: fadeInUp 0.6s ease;
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

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<div class="container mt-4">
    <h2 data-aos="fade-right"><i class="fas fa-broom"></i> Gestion des Femmes de Ménage</h2>

    <!-- Formulaire ajout -->
    <form method="POST" class="row g-3 mb-4" data-aos="zoom-in">
        <div class="col-md-3">
            <input type="text" name="nom" class="form-control" placeholder="Nom" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="col-md-3">
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="col-md-3">
            <button type="submit" name="ajouter" class="btn btn-success w-100">
                <i class="fas fa-plus"></i> Ajouter
            </button>
        </div>
    </form>

    <!-- Tableau -->
    <table class="table table-bordered table-hover shadow-lg" data-aos="fade-up">
        <thead class="table-dark">
            <tr>
                <th><i class="fas fa-hashtag"></i> ID</th>
                <th><i class="fas fa-user"></i> Nom</th>
                <th><i class="fas fa-envelope"></i> Email</th>
                <th><i class="fas fa-calendar-alt"></i> Date création</th>
                <th><i class="fas fa-cogs"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($femmes as $f) : ?>
            <tr data-aos="fade-up" data-aos-delay="100">
                <td><?= $f['id'] ?></td>
                <td><?= $f['nom'] ?></td>
                <td><?= $f['email'] ?></td>
                <td><?= $f['created_at'] ?></td>
                <td>
                    <!-- Bouton modifier -->
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editModal<?= $f['id'] ?>">
                        <i class="fas fa-edit"></i> Modifier
                    </button>

                    <!-- Bouton supprimer -->
                    <a href="?delete=<?= $f['id'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Supprimer cette femme de ménage ?')">
                        <i class="fas fa-trash-alt"></i> Supprimer
                    </a>
                </td>
            </tr>

            <!-- Modal édition -->
            <div class="modal fade" id="editModal<?= $f['id'] ?>" tabindex="-1"
                aria-labelledby="editLabel<?= $f['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="editLabel<?= $f['id'] ?>">
                                    <i class="fas fa-user-edit"></i> Modifier Femme de Ménage
                                </h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $f['id'] ?>">

                                <!-- Nom -->
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-user"></i> Nom complet</label>
                                    <input type="text" name="nom" class="form-control" value="<?= $f['nom'] ?>"
                                        required>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-envelope"></i> Adresse Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= $f['email'] ?>"
                                        required>
                                </div>

                                <!-- Mot de passe -->
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-lock"></i> Nouveau Mot de passe</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Laisser vide pour garder l'ancien">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="modifier" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
<br><br><br><br><br><br><br><br><br>
<?php include '../includes/footer.php'; ?>