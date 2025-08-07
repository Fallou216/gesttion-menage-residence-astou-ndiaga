// === MENU BURGER POUR NAVBAR RESPONSIVE ===
document.addEventListener("DOMContentLoaded", function () {
  const toggler = document.querySelector(".navbar-toggler");
  const menu = document.querySelector("#navbarSupportedContent");

  if (toggler && menu) {
    toggler.addEventListener("click", function () {
      menu.classList.toggle("show");
    });
  }

  // === FERMER LES ALERTES AUTOMATIQUEMENT APRÈS 4 SECONDES ===
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = "0";
      setTimeout(() => alert.remove(), 500);
    }, 4000);
  });

  // === CONFIRMATION AVANT SUPPRESSION ===
  const deleteBtns = document.querySelectorAll(".btn-delete");
  deleteBtns.forEach(btn => {
    btn.addEventListener("click", function (e) {
      const confirmed = confirm("Voulez-vous vraiment supprimer cet élément ?");
      if (!confirmed) {
        e.preventDefault();
      }
    });
  });

  // === PRÉVISUALISATION D’IMAGE (upload photo chambre) ===
  const inputFile = document.querySelector("#imageUpload");
  const previewImg = document.querySelector("#imagePreview");

  if (inputFile && previewImg) {
    inputFile.addEventListener("change", function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewImg.setAttribute("src", e.target.result);
          previewImg.style.display = "block";
        }
        reader.readAsDataURL(file);
      }
    });
  }
});
