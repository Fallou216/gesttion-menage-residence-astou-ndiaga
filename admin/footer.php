<?php
// includes/footer.php
?>
<!-- Pied de page animé -->
<footer class="footer-animated mt-auto">
    <div class="container text-center py-4">
        <div class="footer-logo mb-2">
            <img src="../assets/images/Logo1.png" alt="Logo" width="60">
        </div>
        <small class="footer-text">&copy; <?= date('Y') ?> Résidence Astou & Ndiaga — Tous droits réservés.</small>
    </div>
</footer>

<style>
/* Footer animé et moderne */
.footer-animated {
    background: linear-gradient(45deg, #1e1e2f, #4b6cb7, #2c3e50, #182848);
    background-size: 400% 400%;
    animation: footerGradient 15s ease infinite;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    position: relative;
    overflow: hidden;
}

@keyframes footerGradient {
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

.footer-logo img {
    transition: transform 0.5s ease;
}

.footer-logo img:hover {
    transform: rotate(-15deg) scale(1.1);
}

.footer-text {
    font-size: 0.9rem;
    color: #ffcc66;
    transition: color 0.3s, text-shadow 0.3s;
}

.footer-text:hover {
    color: #fff;
    text-shadow: 0 0 10px #ffcc66;
}

.footer-animated::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.2);
    pointer-events: none;
}
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Ton JS personnalisé -->
<script src="/assets/js/script.js"></script>

</body>

</html>