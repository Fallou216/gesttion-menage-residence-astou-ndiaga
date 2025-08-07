<?php
session_start();
require_once 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $role = $_POST['role']; // Récupère le rôle depuis le formulaire

    if ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (!in_array($role, ['admin', 'femme'])) {
        $error = "Rôle invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nom, $email, $hash, $role])) {
                $success = "Inscription réussie. Vous pouvez vous connecter.";
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width: 480px;">
        <div class="text-center mb-3">
            <img src="assets/images/Logo1.png" width="80" alt="Logo">
            <h4 class="mt-2">Résidence Astou et Ndiaga</h4>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="nom" class="form-control" placeholder="Nom complet" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="confirm" class="form-control" placeholder="Confirmer" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                <select name="role" class="form-control" required>
                    <option value="">-- Sélectionnez votre rôle --</option>
                    <option value="femme">Femme de ménage</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">Créer compte</button>
        </form>

        <div class="text-center mt-3">
            <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>