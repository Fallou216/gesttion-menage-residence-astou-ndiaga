<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        // Redirection selon le rôle
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
            exit();
        } elseif ($user['role'] === 'femme') {
            header('Location: femme/dashboard.php');
            exit();
        } else {
            $error = "Rôle utilisateur inconnu.";
        }
    } else {
        $error = "Email ou mot de passe invalide.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width: 420px;">
        <div class="text-center mb-3">
            <img src="assets/images/Logo1.png" width="80" alt="Logo">
            <h4 class="mt-2">Résidence Astou et Ndiaga</h4>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Mot de passe" required>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <div class="text-center mt-3">
            <p>Pas encore de compte ? <a href="register.php">S’inscrire</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>