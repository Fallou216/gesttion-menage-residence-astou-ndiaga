<?php
require_once 'auth_femme.php';
require_once '../includes/db.php';
include '../includes/header.php';
include 'navbar.php';

$user_id = $_SESSION['user']['id'];

// Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $chambre_id = $_POST['chambre_id'];
    $photo = $_FILES['photo'];

    if ($photo['error'] === 0) {
        $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $destination = '../uploads/' . $filename;

        if (move_uploaded_file($photo['tmp_name'], $destination)) {
            $stmt = $pdo->prepare("INSERT INTO photos (user_id, chambre_id, photo_path) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $chambre_id, $filename]);
            $success = "Photo envoyée avec succès.";
        } else {
            $error = "Erreur lors du téléchargement.";
        }
    } else {
        $error = "Fichier invalide.";
    }
}

$chambres = $pdo->query("SELECT * FROM chambres ORDER BY numero_chambre ASC")->fetchAll();
?>

<!-- Styles -->
<style>
/* Animations générales */
.container {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 1s forwards;
}

.alert {
    opacity: 0;
    transform: translateY(-10px);
    animation: fadeInAlert 0.8s forwards;
}

form.row.g-3 {
    opacity: 0;
    transform: translateY(10px);
    animation: fadeInForm 1s forwards;
    animation-delay: 0.5s;
}

/* Boutons et selects */
.btn {
    transition: transform 0.3s, box-shadow 0.3s;
}

.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

select.form-select {
    transition: all 0.3s ease;
}

select.form-select:focus {
    transform: scale(1.05);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

/* Keyframes */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInAlert {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInForm {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Input file */
input[type="file"] {
    transition: transform 0.3s, box-shadow 0.3s;
}

input[type="file"]:focus {
    transform: scale(1.02);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

/* Prévisualisation */
#photoPreview {
    display: none;
    max-width: 100%;
    border-radius: 10px;
    margin-top: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    transform: scale(0);
    animation: popIn 0.5s forwards;
}

/* Animation pop-in */
@keyframes popIn {
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Background & texte */
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

/* Gradient animation */
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

/* Styles pour l'interface de capture photo */
.camera-options {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.camera-btn {
    flex: 1;
    text-align: center;
    padding: 12px;
    background: #4a5568;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.camera-btn:hover {
    background: #2d3748;
    transform: translateY(-2px);
}

.camera-icon {
    font-size: 24px;
    margin-bottom: 5px;
}

.file-input-container {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
}

.file-input-label {
    display: block;
    padding: 10px;
    background: #4a5568;
    color: white;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    transition: background 0.3s;
}

.file-input-label:hover {
    background: #2d3748;
}

.hidden-input {
    position: absolute;
    left: -9999px;
}

/* Nouveaux styles pour les inputs cachés */
#cameraInput,
#galleryInput {
    display: none;
}

/* Webcam preview */
#webcamContainer {
    display: none;
    margin-top: 20px;
    text-align: center;
}

#webcamVideo {
    width: 100%;
    max-width: 400px;
    border-radius: 10px;
    border: 2px solid #4a5568;
}

#captureBtn {
    margin-top: 10px;
}

/* Message d'information */
.device-message {
    text-align: center;
    margin-top: 10px;
    font-size: 14px;
    color: #ccc;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const selects = document.querySelectorAll('select.form-select');
    selects.forEach(select => {
        select.addEventListener('blur', () => {
            select.style.transform = 'scale(1)';
        });
    });

    // Éléments DOM
    const cameraInput = document.getElementById('cameraInput');
    const galleryInput = document.getElementById('galleryInput');
    const photoPreview = document.getElementById('photoPreview');
    const mainPhotoInput = document.getElementById('photoInput');
    const webcamContainer = document.getElementById('webcamContainer');
    const webcamVideo = document.getElementById('webcamVideo');
    const captureBtn = document.getElementById('captureBtn');
    const deviceMessage = document.getElementById('deviceMessage');

    // Vérifier si on est sur mobile
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    if (isMobile) {
        deviceMessage.textContent = "Sur mobile : utilisez l'appareil photo ou la galerie";
    } else {
        deviceMessage.textContent = "Sur ordinateur : utilisez la webcam ou importez une image";
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            // Mettre à jour le fichier dans l'input principal
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            mainPhotoInput.files = dataTransfer.files;

            // Afficher la prévisualisation
            const reader = new FileReader();
            reader.onload = e => {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            photoPreview.src = '#';
            photoPreview.style.display = 'none';
        }
    }

    // Écouter les changements sur les deux inputs
    cameraInput.addEventListener('change', handleFileSelect);
    galleryInput.addEventListener('change', handleFileSelect);

    // Écouter les clics sur les boutons
    document.getElementById('useCamera').addEventListener('click', function() {
        if (isMobile) {
            // Sur mobile, utiliser l'attribut capture
            cameraInput.click();
        } else {
            // Sur ordinateur, activer la webcam
            activateWebcam();
        }
    });

    document.getElementById('useGallery').addEventListener('click', function() {
        galleryInput.click();
    });

    // Fonctions pour la webcam (ordinateur)
    let stream = null;

    function activateWebcam() {
        webcamContainer.style.display = 'block';

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    webcamVideo.srcObject = mediaStream;
                    webcamVideo.play();
                })
                .catch(function(error) {
                    console.error("Erreur d'accès à la webcam:", error);
                    alert("Impossible d'accéder à la webcam. Veuillez vérifier les permissions.");
                    webcamContainer.style.display = 'none';
                });
        } else {
            alert(
                "Votre navigateur ne supporte pas l'accès à la webcam. Veuillez utiliser la fonctionnalité de sélection de fichier.");
            webcamContainer.style.display = 'none';
        }
    }

    captureBtn.addEventListener('click', function() {
        if (!stream) return;

        // Créer un canvas pour capturer l'image
        const canvas = document.createElement('canvas');
        canvas.width = webcamVideo.videoWidth;
        canvas.height = webcamVideo.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(webcamVideo, 0, 0, canvas.width, canvas.height);

        // Convertir en blob puis en file
        canvas.toBlob(function(blob) {
            // Créer un fichier à partir du blob
            const file = new File([blob], 'webcam-photo.jpg', {
                type: 'image/jpeg'
            });

            // Mettre à jour l'input principal
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            mainPhotoInput.files = dataTransfer.files;

            // Afficher la prévisualisation
            photoPreview.src = URL.createObjectURL(file);
            photoPreview.style.display = 'block';

            // Arrêter la webcam
            stream.getTracks().forEach(track => track.stop());
            webcamContainer.style.display = 'none';
        }, 'image/jpeg', 0.9);
    });
});
</script>

<div class="container mt-4">
    <h2><i class="fas fa-camera-retro"></i> Envoyer une photo de chambre ménagée</h2>

    <?php if(isset($success)): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
    <?php elseif(isset($error)): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> <?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-door-open"></i> Chambre</label>
            <select name="chambre_id" class="form-select" required>
                <option value="">Choisir une chambre</option>
                <?php foreach($chambres as $ch): ?>
                <option value="<?= $ch['id'] ?>"><?= $ch['numero_chambre'] ?> - <?= $ch['etage'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-image"></i> Photo (jpeg, png)</label>

            <div class="camera-options">
                <div class="camera-btn" id="useCamera">
                    <div class="camera-icon"><i class="fas fa-camera"></i></div>
                    <div>Prendre une photo</div>
                </div>
                <div class="camera-btn" id="useGallery">
                    <div class="camera-icon"><i class="fas fa-images"></i></div>
                    <div>Choisir depuis la galerie</div>
                </div>
            </div>

            <p id="deviceMessage" class="device-message"></p>

            <!-- Inputs cachés pour chaque option -->
            <input type="file" id="cameraInput" accept="image/*" capture="camera">
            <input type="file" id="galleryInput" accept="image/*">

            <!-- Input principal qui sera soumis avec le formulaire -->
            <input type="file" name="photo" id="photoInput" class="hidden-input" required>

            <!-- Conteneur pour la webcam (ordinateur) -->
            <div id="webcamContainer">
                <video id="webcamVideo" autoplay playsinline></video>
                <button type="button" id="captureBtn" class="btn btn-primary mt-2">
                    <i class="fas fa-camera"></i> Capturer la photo
                </button>
            </div>

            <img id="photoPreview" src="#" alt="Prévisualisation">
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Envoyer</button>
        </div>
    </form>
</div>

<br><br><br><br>
<?php include 'footer.php'; ?>