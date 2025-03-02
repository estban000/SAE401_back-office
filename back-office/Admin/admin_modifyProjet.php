<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

// Vérification de l'existence du projet
if (!isset($_GET["id"])) {
    header("Location: admin_projets.php");
    exit();
}

$id = intval($_GET["id"]);
$result = mysqli_query($connexion, "SELECT * FROM projets WHERE id=$id");
$projet = mysqli_fetch_assoc($result);

if (!$projet) {
    header("Location: admin_projets.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = mysqli_real_escape_string($connexion, $_POST["titre"]);
    $description = mysqli_real_escape_string($connexion, $_POST["description"]);
    $imagePath = $projet["image"];

    // Vérification et traitement de l'image si elle est modifiée
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérifier si le fichier est une image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Supprimer l'ancienne image
                if (file_exists($projet["image"])) {
                    unlink($projet["image"]);
                }
                $imagePath = $target_file;
            } else {
                echo "Erreur lors de l'upload.";
            }
        } else {
            echo "Le fichier n'est pas une image.";
        }
    }

    // Mise à jour du projet dans la base de données
    $sql = "UPDATE projets SET titre='$titre', description='$description', image='$imagePath' WHERE id=$id";
    if (mysqli_query($connexion, $sql)) {
        header("Location: admin_Projet.php");
        exit();
    } else {
        echo "Erreur : " . mysqli_error($connexion);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Modifier un Projet</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <nav class="navbar">
        <h1>Administration</h1>
        <ul class="nav-links">
            <li><a href="admin_logout.php" class="logout-btn">Déconnexion</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>Modifier un Projet</h2>

        <form action="admin_modifyProjet.php?id=<?= $id ?>" method="post" enctype="multipart/form-data" class="form-modify">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea name="description" class="form-input" required><?= htmlspecialchars($projet['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image actuelle :</label>
                <img src="<?= htmlspecialchars($projet['image']) ?>" alt="Image projet" width="150" class="current-image">
            </div>

            <div class="form-group">
                <label for="image">Changer l'image :</label>
                <input type="file" name="image" class="file-input" accept="image/*">
            </div>

            <button type="submit" class="btn-update">Mettre à jour</button>
        </form>

        <!-- Bouton de retour -->
        <a href="admin_Projet.php" class="btn-back">Retour</a>
    </div>

</body>
</html>
