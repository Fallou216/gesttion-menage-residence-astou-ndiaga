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

<style>
/* Fond animé dégradé */
body {
    background: linear-gradient(-45deg, #1e1e2f, #4b6cb7, #182848, #2c3e50);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    font-family: 'Poppins', sans-serif;
}

@keyframes gradientBG {
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

/* Carte du login */
.login-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5);
}

/* Logo */
.login-logo img {
    width: 100px;
    height: auto;
    transition: transform 0.5s ease;
}

.login-logo img:hover {
    transform: rotate(15deg) scale(1.1);
}

/* Titres et textes */
h4 {
    color: #fff;
    font-weight: 600;
    margin-top: 15px;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
}

.alert {
    border-radius: 10px;
}

/* Champs input */
.input-group-text {
    background-color: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
}

.form-control {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #fff;
    transition: 0.3s;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.25);
    box-shadow: none;
    color: #fff;
}

::placeholder {
    color: #e0e0e0;
}

/* Bouton animé */
.btn-primary {
    background: linear-gradient(45deg, #ff7e5f, #feb47b);
    border: none;
    font-weight: 600;
    transition: 0.3s;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #feb47b, #ff7e5f);
    transform: scale(1.05);
}

a {
    color: #ffcc66;
    text-decoration: none;
    transition: 0.3s;
}

a:hover {
    color: #fff;
    text-decoration: underline;
}
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-card">
        <div class="login-logo text-center mb-4">
            <img src="assets/images/Logo1.png" alt="Logo">
            <h4>Résidence Astou & Ndiaga</h4>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt me-1"></i> Se connecter
            </button>
        </form>

        <div class="text-center mt-3">
            <p>Pas encore de compte ? <a href="register.php">S’inscrire</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>