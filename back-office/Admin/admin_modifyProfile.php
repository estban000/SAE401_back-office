<?php
require('session.php');
require('../connexionTableSQL.php');

if (!isset($_GET["id"])) {
    header("Location: admin_profile.php");
    exit();
}

require('gestion_modifyProfile.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Modifier Profile</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <?php require('admin_header.php'); ?>

    <div class="container">
        <h2>Modifier le Profile description</h2>
        <form action="admin_modifyProfile.php?id=<?= $id ?>" method="post" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
                <label for="imageProfile">Image de Profil :</label>
                <input type="file" name="imageProfile" id="imageProfile" accept="image/*" class="form-input">
                <img src="../uploads/imgProfile/<?= htmlspecialchars($profil['imageProfile']) ?>" alt="Profile Image" width="50">
            </div>
            
            <div class="form-group">
                <label for="texteDescription">Description :</label>
                <textarea name="texteDescription" id="texteDescription" class="form-input" required><?= htmlspecialchars($profil['texteDescription']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="cv">CV (PDF) :</label>
                <input type="file" name="cv" id="cv" accept="application/pdf" class="form-input">
                <a href="../uploads/cv/<?= htmlspecialchars($profil['cv']) ?>" target="_blank" class="file-link">Télécharger le CV</a>
            </div>
            
            <button type="submit" class="btn btn-add">Mettre à jour</button>
        </form>

        <!-- Bouton de retour -->
        <a href="admin_profile.php" class="btn btn-back">Retour</a>
    </div>
</body>
</html>
