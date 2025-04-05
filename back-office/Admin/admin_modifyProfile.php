<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

if (!isset($_GET["id"])) {
    header("Location: admin_profile.php");
    exit();
}

$id = intval($_GET["id"]);
$result = mysqli_query($connexion, "SELECT * FROM ProfileBlock WHERE id=$id");
$profil = mysqli_fetch_assoc($result);

if (!$profil) {
    header("Location: admin_profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = mysqli_real_escape_string($connexion, $_POST["texteDescription"]);
    
    // Gestion de l'upload de l'image
    if (!empty($_FILES["imageProfile"]["name"])) {
        $imageProfile = basename($_FILES["imageProfile"]["name"]);
        move_uploaded_file($_FILES["imageProfile"]["tmp_name"], "../uploads/imgProfile" . $imageProfile);
    } else {
        $imageProfile = $profil["imageProfile"];
    }
    
    // Gestion de l'upload du CV
    if (!empty($_FILES["cv"]["name"])) {
        $cv_extension = pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION);
        if (strtolower($cv_extension) !== "pdf") {
            die("Seuls les fichiers PDF sont autorisés.");
        }
        $cv = basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], "../uploads/cv/" . $cv);
    } else {
        $cv = $profil["cv"];
    }
    
    // Mise à jour des données dans la base
    $query = "UPDATE ProfileBlock SET imageProfile='$imageProfile', texteDescription='$description', cv='$cv' WHERE id=$id";
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
