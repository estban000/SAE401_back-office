<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');
$message = "";

// Récupération des catégories
$sql_categories = "SELECT id, NomCategory FROM Categories";
$result_categories = mysqli_query($connexion, $sql_categories);
$categories = [];
if ($result_categories) {
    while ($row = mysqli_fetch_assoc($result_categories)) {
        $categories[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = mysqli_real_escape_string($connexion, $_POST["titre"]);
    $description = mysqli_real_escape_string($connexion, $_POST["description"]);
    $categorie_id = (int) $_POST["categorie"];
    $video_link = mysqli_real_escape_string($connexion, $_POST["video_link"]);

    // Vérifier que la catégorie existe
    $stmt_check = mysqli_prepare($connexion, "SELECT id FROM Categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt_check, "i", $categorie_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        die("<p class='error-message'>Catégorie invalide.</p>");
    }
    mysqli_stmt_close($stmt_check);

    // Vérifie et upload l'image
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "../uploads/";
        $fileName = pathinfo($_FILES["image"]["name"], PATHINFO_FILENAME);
        $fileName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $fileName);
        $fileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $fileName = uniqid() . "_" . $fileName . "." . $fileType;
        $targetFilePath = $targetDir . $fileName;

        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedTypes)) {
            die("<p class='error-message'>Seuls les fichiers JPG, JPEG et PNG sont autorisés.</p>");
        }
        if ($_FILES["image"]["size"] > 10 * 1024 * 1024) {
            die("<p class='error-message'>Le fichier est trop volumineux (max 10 Mo).</p>");
        }
        $checkImage = getimagesize($_FILES["image"]["tmp_name"]);
        if ($checkImage === false) {
            die("<p class='error-message'>Le fichier n'est pas une image valide.</p>");
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = "uploads/" . $fileName;

            $sql = "INSERT INTO projets (titre, description, categorie_id, image, video) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssiss", $titre, $description, $categorie_id, $imagePath, $video_link);

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
    <title>Administration - Ajouter un projet</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<?php require('admin_header.php'); ?>
<div class="container">
    <h2>Ajouter un Projet</h2>
    <?= !empty($message) ? $message : '' ?>

    <form action="admin_ajoutProjet.php" method="post" enctype="multipart/form-data" class="form-container">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="categorie">Catégorie :</label>
            <select name="categorie" class="form-input" required>
                <option value="">Sélectionnez une catégorie</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['NomCategory']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" class="form-input" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="video_link">Lien vidéo (YouTube) :</label>
            <input type="url" name="video_link" class="form-input" placeholder="https://www.youtube.com/...">
        </div>

        <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" name="image" class="form-input file-input" accept="image/*" required>
        </div>
        <div class="drop-zone" id="drop-zone">Glissez-déposez une image ici</div>

        <button type="submit" class="btn-submit">Ajouter</button>
        <a href="admin_Projet.php" class="btn-back">Retour</a>
    </form>
</div>

<script src="../assets/js/drag_drop.js"></script>
</body>
</html>
