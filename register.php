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

/* Carte register */
.register-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.register-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5);
}

/* Logo */
.register-logo img {
    width: 100px;
    height: auto;
    transition: transform 0.5s ease;
}

.register-logo img:hover {
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
.btn-success {
    background: linear-gradient(45deg, #ff7e5f, #feb47b);
    border: none;
    font-weight: 600;
    transition: 0.3s;
    color: #fff;
}

.btn-success:hover {
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
    <div class="register-card">
        <div class="register-logo text-center mb-4">
            <img src="assets/images/Logo1.png" alt="Logo">
            <h4>Résidence Astou & Ndiaga</h4>
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
                <input type="password" name="confirm" class="form-control" placeholder="Confirmer le mot de passe"
                    required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                <select name="role" class="form-control" required>
                    <option value="">-- Sélectionnez votre rôle --</option>
                    <option value="femme" style="background-color: #ffe4b5; color: #000;">Femme de ménage</option>
                    <option value="admin" style="background-color: #add8e6; color: #000;">Administrateur</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-user-plus me-1"></i> Créer compte
            </button>
        </form>

        <div class="text-center mt-3">
            <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>