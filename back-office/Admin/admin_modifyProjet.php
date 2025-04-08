<?php
require('session.php');
require('../connexionTableSQL.php');

$message = "";
$projet = null;
$categories = [];

// Vérifie que l'ID du projet est bien fourni dans l'URL
if (!isset($_GET["id"])) {
    die("ID de projet manquant.");
}
$projet_id = (int)$_GET["id"];

// Récupère les informations du projet depuis la base de données
$sql_projet = "SELECT * FROM projets WHERE id = ?";
$stmt = mysqli_prepare($connexion, $sql_projet);
mysqli_stmt_bind_param($stmt, "i", $projet_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$projet = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Si aucun projet trouvé avec l'ID fourni
if (!$projet) {
    die("Projet introuvable.");
}

// Récupère toutes les catégories disponibles pour le menu déroulant
$sql_categories = "SELECT id, NomCategory FROM Categories";
$result_categories = mysqli_query($connexion, $sql_categories);
while ($row = mysqli_fetch_assoc($result_categories)) {
    $categories[] = $row;
}

require('gestion_modifyProjet.php');

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

    <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Formulaire de modification xxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <form method="post" action="admin_modifyProjet.php?id=<?= $projet_id ?>" enctype="multipart/form-data" class="form-container">

        <!-- Titre -->
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" class="form-input" value="<?= htmlspecialchars($projet["titre"]) ?>" required>
        </div>

        <!-- Catégorie -->
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

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" class="form-input" rows="4" required><?= htmlspecialchars($projet["description"]) ?></textarea>
        </div>

        <!-- Lien du projet -->
        <div class="form-group">
            <label for="url_projet">Lien du projet (facultatif) :</label>
            <input type="url" name="url_projet" class="form-input" value="<?= htmlspecialchars($projet["url_projet"]) ?>">
        </div>

        <!-- Lien vidéo YouTube -->
        <div class="form-group">
            <label for="video">Lien YouTube (facultatif) :</label>
            <input type="url" name="video" class="form-input" value="<?= htmlspecialchars($projet["video"]) ?>">
        </div>

        <!-- Aperçu de l’image actuelle -->
        <div class="form-group">
            <label>Image actuelle :</label>
            <img src="../<?= $projet["image"] ?>" alt="Image projet" style="max-width: 200px;">
        </div>

        <!-- Zone de drag & drop -->
        <div class="drop-zone" id="drop-zone">Glissez-déposez une image ici</div>

        <button type="submit" class="btn-submit">Mettre à jour</button>
    </form>

    <!-- Bouton de retour -->
    <a href="admin_Projet.php" class="btn-back">Retour</a>
</div>

<script src="../assets/js/drag_drop.js"></script>
</body>
</html>
