<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

$message = "";
$projet = null;
$categories = [];

// Vérifier que l'ID est présent
if (!isset($_GET["id"])) {
    die("ID de projet manquant.");
}
$projet_id = (int)$_GET["id"];

// Récupération du projet
$sql_projet = "SELECT * FROM projets WHERE id = ?";
$stmt = mysqli_prepare($connexion, $sql_projet);
mysqli_stmt_bind_param($stmt, "i", $projet_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$projet = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$projet) {
    die("Projet introuvable.");
}

// Récupération des catégories
$sql_categories = "SELECT id, NomCategory FROM Categories";
$result_categories = mysqli_query($connexion, $sql_categories);
while ($row = mysqli_fetch_assoc($result_categories)) {
    $categories[] = $row;
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = mysqli_real_escape_string($connexion, $_POST["titre"]);
    $description = mysqli_real_escape_string($connexion, $_POST["description"]);
    $categorie_id = (int) $_POST["categorie"];
    $video = mysqli_real_escape_string($connexion, $_POST["video"]);

    // Vérifie que la catégorie existe
    $sql_check = "SELECT id FROM Categories WHERE id = ?";
    $stmt_check = mysqli_prepare($connexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $categorie_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        die("<p class='error-message'>Catégorie invalide.</p>");
    }
    mysqli_stmt_close($stmt_check);

    $imagePath = $projet["image"]; // Garder l'image actuelle

    // Nouvelle image ?
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

    // Mise à jour
    $sql_update = "UPDATE projets SET titre = ?, description = ?, categorie_id = ?, image = ?, video = ? WHERE id = ?";
    $stmt = mysqli_prepare($connexion, $sql_update);
    mysqli_stmt_bind_param($stmt, "ssissi", $titre, $description, $categorie_id, $imagePath, $video, $projet_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "<p class='success-message'>Projet mis à jour avec succès !</p>";
        // Recharger les données
        $projet["titre"] = $titre;
        $projet["description"] = $description;
        $projet["categorie_id"] = $categorie_id;
        $projet["image"] = $imagePath;
        $projet["video"] = $video;
    } else {
        $message = "<p class='error-message'>Erreur lors de la mise à jour du projet.</p>";
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($connexion);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Modifier le projet</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
<?php require('admin_header.php'); ?>
<div class="container">
    <h2>Modifier un Projet</h2>
    <?= $message ?>
    <form method="post" action="admin_modifyProjet.php?id=<?= $projet_id ?>" enctype="multipart/form-data" class="form-container">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" class="form-input" value="<?= htmlspecialchars($projet["titre"]) ?>" required>
        </div>

        <div class="form-group">
            <label for="categorie">Catégorie :</label>
            <select name="categorie" class="form-input" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['id']) ?>" <?= ($projet["categorie_id"] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['NomCategory']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" class="form-input" rows="4" required><?= htmlspecialchars($projet["description"]) ?></textarea>
        </div>

        <div class="form-group">
            <label for="video">Lien YouTube (facultatif) :</label>
            <input type="url" name="video" class="form-input" value="<?= htmlspecialchars($projet["video"]) ?>">
        </div>

        <div class="form-group">
            <label>Image actuelle :</label>
            <img src="../<?= $projet["image"] ?>" alt="Image projet" style="max-width: 200px;">
        </div>

        <div class="form-group">
            <label for="image">Changer l’image :</label>
            <input type="file" name="image" class="form-input" accept="image/*">
        </div>
        <div class="drop-zone" id="drop-zone">Glissez-déposez une image ici</div>

        <button type="submit" class="btn-submit">Mettre à jour</button>
    </form>
    <a href="admin_Projet.php" class="btn-back">Retour</a>
</div>

<script src="../assets/js/drag_drop.js"></script>
</body>
</html>
