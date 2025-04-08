<?php
// soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécurise les données envoyées par le formulaire
    $titre = mysqli_real_escape_string($connexion, $_POST["titre"]);
    $description = mysqli_real_escape_string($connexion, $_POST["description"]);
    $categorie_id = (int) $_POST["categorie"];
    $video = mysqli_real_escape_string($connexion, $_POST["video"]);
    $url_projet = mysqli_real_escape_string($connexion, $_POST["url_projet"]);

    // Vérification de catégorie choisie existante
    $sql_check = "SELECT id FROM Categories WHERE id = ?";
    $stmt_check = mysqli_prepare($connexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $categorie_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        die("<p class='error-message'>Catégorie invalide.</p>");
    }
    mysqli_stmt_close($stmt_check);

    $imagePath = $projet["image"];

    // gestion d'une nouvelle image uploadée
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/";
        $fileName = pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME);
        $fileName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $fileName);
        $fileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $fileName = uniqid() . "_" . $fileName . "." . $fileType;
        $targetFilePath = $targetDir . $fileName;

        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedTypes)) {
            die("<p class='error-message'>Types autorisés : JPG, JPEG, PNG.</p>");
        }

        if ($_FILES["image"]["size"] > 10 * 1024 * 1024) {
            die("<p class='error-message'>Fichier trop volumineux (max 10 Mo).</p>");
        }

        $checkImage = getimagesize($_FILES["image"]["tmp_name"]);
        if ($checkImage === false) {
            die("<p class='error-message'>Ce n'est pas une image valide.</p>");
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = "uploads/" . $fileName;
        } else {
            die("<p class='error-message'>Erreur lors de l'upload de la nouvelle image.</p>");
        }
    }

    // Mise à jour du projet dans la base de données
    $sql_update = "UPDATE projets SET titre = ?, description = ?, categorie_id = ?, image = ?, video = ?, url_projet = ? WHERE id = ?";
    $stmt = mysqli_prepare($connexion, $sql_update);
    mysqli_stmt_bind_param($stmt, "ssisssi", $titre, $description, $categorie_id, $imagePath, $video, $url_projet, $projet_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "<p class='success-message'>Projet mis à jour avec succès !</p>";
        // Met à jour les valeurs locales
        $projet["titre"] = $titre;
        $projet["description"] = $description;
        $projet["categorie_id"] = $categorie_id;
        $projet["image"] = $imagePath;
        $projet["video"] = $video;
        $projet["url_projet"] = $url_projet;
    } else {
        $message = "<p class='error-message'>Erreur lors de la mise à jour du projet.</p>";
    }

    mysqli_stmt_close($stmt);
}
?>