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

<div class="container mt-4">
    <h2><i class="fas fa-broom"></i> Gestion des Femmes de Ménage</h2>

    <!-- Formulaire ajout -->
    <form method="POST" class="row g-3 mb-4">
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
    <table class="table table-bordered table-hover">
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
            <tr>
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

<?php include '../includes/footer.php'; ?>