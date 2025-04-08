<?php
require('session.php');
require('../connexionTableSQL.php');
$message = "";

// Récupère les catégories disponibles pour le menu déroulant
$sql_categories = "SELECT id, NomCategory FROM Categories";
$result_categories = mysqli_query($connexion, $sql_categories);
$categories = [];
if ($result_categories) {
    while ($row = mysqli_fetch_assoc($result_categories)) {
        $categories[] = $row;
    }
}

require('gestion_ajoutProjet.php');

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
    <!-- Affiche le message de retour (succès ou erreur) -->
    <?= !empty($message) ? $message : '' ?>

    <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxx Formulaire d'ajout xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <form action="admin_ajoutProjet.php" method="post" enctype="multipart/form-data" class="form-container">

        <!-- Titre -->
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" class="form-input" required>
        </div>

        <!-- Catégorie -->
        <div class="form-group">
            <label for="categorie">Catégorie :</label>
            <select name="categorie" class="form-input" required>
                <option value="">Sélectionnez une catégorie</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['NomCategory']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" class="form-input" rows="4" required></textarea>
        </div>

        <!-- Lien du projet -->
        <div class="form-group">
            <label for="url_projet">URL du projet :</label>
            <input type="url" name="url_projet" class="form-input" placeholder="https://mon-projet.com">
        </div>
        <!-- Lien vidéo YouTube -->
        <div class="form-group">
            <label for="video_link">Lien vidéo (optionnel) :</label>
            <input type="url" name="video_link" class="form-input" placeholder="https://www.youtube.com/...">
        </div>

        <!-- Image -->
        <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" name="image" class="form-input file-input" accept="image/*" required>
        </div>

        <!-- Zone drag & drop -->
        <div class="drop-zone" id="drop-zone">Glissez-déposez une image ici</div>

        <button type="submit" class="btn-submit">Ajouter</button>
    </form>
    
    <!-- Boutons retour -->
    <a href="admin_Projet.php" class="btn-back">Retour</a>
</div>

<script src="../assets/js/drag_drop.js"></script>
</body>
</html>
