<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

$message = "";

// Récupération des catégories depuis la base de données
$sql_categories = "SELECT id, NomCategory FROM Categories";
$result_categories = mysqli_query($connexion, $sql_categories);
$categories = [];
if ($result_categories) {
    while ($row = mysqli_fetch_assoc($result_categories)) {
        $categories[] = $row;
    }
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($connexion)) {
        die("<p class='error-message'>Erreur de connexion à la base de données.</p>");
    }

    $titre = mysqli_real_escape_string($connexion, $_POST["titre"]);
    $description = mysqli_real_escape_string($connexion, $_POST["description"]);
    $categorie_id = (int) $_POST["categorie"];

    // Vérifier que la catégorie sélectionnée existe bien en base de données
    $sql_check_categorie = "SELECT id FROM Categories WHERE id = ?";
    $stmt_check = mysqli_prepare($connexion, $sql_check_categorie);
    mysqli_stmt_bind_param($stmt_check, "i", $categorie_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        die("<p class='error-message'>Catégorie invalide.</p>");
    }
    mysqli_stmt_close($stmt_check);

    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            die("<p class='error-message'>Erreur : dossier 'uploads' inaccessible.</p>");
        }

        // Nettoyage et renommage du fichier image
        $fileName = pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME);
        $fileName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $fileName); // Remplace caractères spéciaux
        $fileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $fileName = uniqid() . "_" . $fileName . "." . $fileType; // Ajoute un identifiant unique
        $targetFilePath = $targetDir . $fileName;

        // Vérification du type et de la taille du fichier
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedTypes)) {
            die("<p class='error-message'>Seuls les fichiers JPG, JPEG et PNG sont autorisés.</p>");
        }

        if ($_FILES["image"]["size"] > 10 * 1024 * 1024) {
            die("<p class='error-message'>Le fichier est trop volumineux (max 10 Mo).</p>");
        }

        // Vérification que le fichier est bien une image
        $checkImage = getimagesize($_FILES["image"]["tmp_name"]);
        if ($checkImage === false) {
            die("<p class='error-message'>Le fichier n'est pas une image valide.</p>");
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = "uploads/" . $fileName;
            $sql = "INSERT INTO projets (titre, description, categorie_id, image) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($connexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssis", $titre, $description, $categorie_id, $imagePath);

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

mysqli_close($connexion);
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
    <?php require('admin_header.php'); ?>

    <div class="container">
        <h2>Ajouter un Projet</h2>
        <?= !empty($message) ? $message : '' ?>

        <form action="admin_ajoutProjet.php" method="post" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
                <label for="titre">Titre du projet :</label>
                <input type="text" name="titre" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="categorie">Catégorie du projet :</label>
                <select name="categorie" class="form-input" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= htmlspecialchars($categorie['id']) ?>">
                            <?= htmlspecialchars($categorie['NomCategory']) ?>
                        </option required>
                    <?php endforeach; ?>
                </select>
            </div>

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
