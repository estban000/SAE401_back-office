document.addEventListener("DOMContentLoaded", function () {
    var dropZone = document.getElementById("drop-zone");
    var fileInput = document.querySelector(".file-input");

    // Couleur de bordure lors du dragover
    dropZone.addEventListener("dragover", function (e) {
        e.preventDefault();
        dropZone.style.borderColor = "#000";  // Bordure lorsque le fichier est survolé
    });

    // Réinitialisation de la couleur de bordure lors du dragleave
    dropZone.addEventListener("dragleave", function () {
        dropZone.style.borderColor = "#ccc";  // Bordure réinitialisée 
    });

    // Gestion du fichier lorsqu'il est déposé
    dropZone.addEventListener("drop", function (e) {
        e.preventDefault();
        dropZone.style.borderColor = "#ccc";  // Réinitialise la bordure après le dépôt

        var files = e.dataTransfer.files;
        if (files.length > 0) {
            var file = files[0];  // On ne prend que le premier fichier
            var fileType = file.type;

            // Vérification du type de fichier (image uniquement)
            if (fileType.startsWith("image/")) {
                // Créer un nouvel objet DataTransfer
                var dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);  // Ajouter le fichier à l'objet DataTransfer

                // Assigner les fichiers à l'input de fichier
                fileInput.files = dataTransfer.files;

                // Afficher le nom du fichier
                dropZone.textContent = "Image sélectionnée : " + file.name;
            } else {
                alert("Seul un fichier image (JPG, PNG, JPEG) est autorisé.");
                dropZone.textContent = "Glissez-déposez une image ici";  // Réinitialisation du texte
            }
        }
    });
});