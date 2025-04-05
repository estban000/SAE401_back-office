<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = mysqli_real_escape_string($connexion, $_POST["texteDescription"]);
    
    // Gestion de l'upload de l'image
    $imageProfile = "";
    if (!empty($_FILES["imageProfile"]["name"])) {
        $imageProfile = basename($_FILES["imageProfile"]["name"]);
        move_uploaded_file($_FILES["imageProfile"]["tmp_name"], "../uploads/imgProfil/" . $imageProfile);
    }
    
    // Gestion de l'upload du CV
    $cv = "";
    if (!empty($_FILES["cv"]["name"])) {
        $cv_extension = pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION);
        if (strtolower($cv_extension) !== "pdf") {
            die("Seuls les fichiers PDF sont autorisés.");
        }
        $cv = basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], "../uploads/cv/" . $cv);
    }
    
    $query = "INSERT INTO ProfileBlock (imageProfile, texteDescription, cv) VALUES ('$imageProfile', '$description', '$cv')";
    mysqli_query($connexion, $query);
    
    header("Location: admin_profile.php");
    exit();
}
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
