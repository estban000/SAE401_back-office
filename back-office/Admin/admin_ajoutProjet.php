<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

$message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = mysqli_real_escape_string($connexion, $_POST["titre"]);
    $description = mysqli_real_escape_string($connexion, $_POST["description"]);

    // Gestion de l'upload d'image
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Vérification si le dossier "uploads" est accessible en écriture
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            die("<p class='error-message'>Erreur : le dossier 'uploads' n'existe pas ou n'est pas accessible en écriture.</p>");
        }

        // Vérification du format du fichier
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedTypes)) {
            die("<p class='error-message'>Seuls les fichiers JPG, JPEG et PNG sont autorisés.</p>");
        }

        // Vérification de la taille du fichier (max 10 Mo)
        if ($_FILES["image"]["size"] > 10 * 1024 * 1024) {
            die("<p class='error-message'>Le fichier est trop volumineux (max 10 Mo).</p>");
        }

        // Déplacement du fichier uploadé
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            // Stocker le chemin relatif en base de données
            $imagePath = "uploads/" . $fileName;
            $sql = "INSERT INTO projets (titre, description, image) VALUES ('$titre', '$description', '$imagePath')";
            if (mysqli_query($connexion, $sql)) {
                $message = "<p class='success-message'>Projet ajouté avec succès !</p>";
            } else {
                $message = "<p class='error-message'>Erreur lors de l'ajout du projet.</p>";
            }
        } else {
            die("<p class='error-message'>Erreur lors du déplacement du fichier.</p>");
        }
    } else {
        die("<p class='error-message'>Veuillez sélectionner une image.</p>");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Ajouter un Projet</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <?php
	require('admin_header.php');
	?>

    <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxx Contenu Principal xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <div class="container">
        <h2>Ajouter un Projet</h2>
        <?= !empty($message) ? $message : '' ?>

        <form action="admin_ajoutProjet.php" method="post" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
                <label for="titre">Titre du projet :</label>
                <input type="text" name="titre" class="form-input" required>
            </div>
            <!--
            <div class="form-group">
                <label for="categorie">Catégorie du projet:</label>
            </div>-->

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea name="description" class="form-input" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Ajouter une image :</label>
                <input type="file" name="image" class="form-input file-input" accept="image/*" required>
            </div>
            <div class="drop-zone" id="drop-zone">Glissez-déposez une image ici</div>

            <button type="submit" class="btn-submit">Valider</button>
        </form>

        <a href="admin_Projet.php" class="btn-back">Retour</a>
    </div>

</body>
<script src="../assets/js/drag_drop.js"></script>
</html>