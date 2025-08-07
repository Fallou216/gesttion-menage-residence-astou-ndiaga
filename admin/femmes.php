<?php
require_once 'auth_admin.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

// Ajouter une femme de ménage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

$femmes = $pdo->query("SELECT * FROM users WHERE role = 'femme' ORDER BY id DESC")->fetchAll();
?>

<div class="container mt-4">
    <h2>Gestion des Femmes de Ménage</h2>

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
            <button type="submit" class="btn btn-success w-100">Ajouter</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Date création</th>
                <th>Action</th>
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
                    <a href="?delete=<?= $f['id'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Supprimer ?')">Supprimer</a>
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