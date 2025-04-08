<?php
// soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécurisation des données du formulaire
    $titre = mysqli_real_escape_string($connexion, $_POST["titre"]);
    $description = mysqli_real_escape_string($connexion, $_POST["description"]);
    $categorie_id = (int) $_POST["categorie"];
    $video_link = mysqli_real_escape_string($connexion, $_POST["video_link"]);
    $url_projet = mysqli_real_escape_string($connexion, $_POST["url_projet"]);

    // Vérifie si la catégorie choisie existe dans la base
    $stmt_check = mysqli_prepare($connexion, "SELECT id FROM Categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt_check, "i", $categorie_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        die("<p class='error-message'>Catégorie invalide.</p>");
    }
    mysqli_stmt_close($stmt_check);

    // Vérifie si une image a été uploadée
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/";

        // Nettoyage et renommage du fichier
        $fileName = pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME);
        $fileName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $fileName);
        $fileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $fileName = uniqid() . "_" . $fileName . "." . $fileType;
        $targetFilePath = $targetDir . $fileName;

        // Vérification du type de fichier
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedTypes)) {
            die("<p class='error-message'>Seuls les fichiers JPG, JPEG et PNG sont autorisés.</p>");
        }

        // Vérifie la taille maximale (10 Mo)
        if ($_FILES["image"]["size"] > 10 * 1024 * 1024) {
            die("<p class='error-message'>Le fichier est trop volumineux (max 10 Mo).</p>");
        }

        // Vérifie si c'est une image valide
        $checkImage = getimagesize($_FILES["image"]["tmp_name"]);
        if ($checkImage === false) {
            die("<p class='error-message'>Le fichier n'est pas une image valide.</p>");
        }

        // Déplace l'image uploadée dans le dossier "uploads"
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = "uploads/" . $fileName;

            // Insère le projet dans la base de données
            $sql = "INSERT INTO projets (titre, description, categorie_id, image, video, url_projet) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssisss", $titre, $description, $categorie_id, $imagePath, $video_link, $url_projet);

            if (mysqli_stmt_execute($stmt)) {
                $message = "<p class='success-message'>Projet ajouté avec succès !</p>";
            } else {
                $message = "<p class='error-message'>Erreur lors de l'ajout du projet.</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            die("<p class='error-message'>Erreur lors du déplacement du fichier.</p>");
        }
    } else {
        die("<p class='error-message'>Veuillez sélectionner une image.</p>");
    }
}
?>