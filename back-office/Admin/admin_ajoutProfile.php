<?php
require('session.php');
require('../connexionTableSQL.php');

require('gestion_ajoutProfile.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Ajouter un Profile</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <?php require('admin_header.php'); ?>

    <div class="container">
        <h2>Ajouter un Profil</h2>

        <form action="admin_ajoutProfile.php" method="post" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
                <label for="imageProfile">Image de Profil :</label>
                <input type="file" name="imageProfile" id="imageProfile" accept="image/*" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="texteDescription">Description :</label>
                <textarea name="texteDescription" id="texteDescription" class="form-input" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="cv">CV (au format PDF) :</label>
                <input type="file" name="cv" id="cv" accept="application/pdf" class="form-input" required>
            </div>
            
            <button type="submit" class="btn btn-add">Ajouter</button>
        </form>

        <!-- Bouton de retour -->
        <a href="admin_profile.php" class="btn btn-back">Retour</a>
    </div>
</body>
</html>
