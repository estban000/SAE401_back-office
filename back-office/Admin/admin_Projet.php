<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

$message = "";

// Suppression d'un projet
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);

    // Récupérer l'image pour la supprimer du serveur
    $query = mysqli_query($connexion, "SELECT image FROM projets WHERE id=$id");
    $row = mysqli_fetch_assoc($query);
    if ($row) {
        $imagePath = "../" . $row["image"];
        // Vérifier si le fichier existe avant de tenter de le supprimer
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $message = "<p class='success-message'>L'image a été supprimée avec succès.</p>";
            } else {
                $message = "<p class='error-message'>Erreur lors de la suppression de l'image.</p>";
            }
        } else {
            $message = "<p class='error-message'>L'image n'existe pas ou a déjà été supprimée.</p>";
        }

        // Suppression du projet de la base de données
        $deleteQuery = mysqli_query($connexion, "DELETE FROM projets WHERE id=$id");
        if ($deleteQuery) {
            $message .= "<p class='success-message'>Le projet a été supprimé avec succès.</p>";
        } else {
            $message .= "<p class='error-message'>Erreur lors de la suppression du projet de la base de données.</p>";
        }
    } else {
        $message = "<p class='error-message'>Le projet n'existe pas.</p>";
    }

    header("Location: admin_Projet.php?message=" . urlencode($message));
    exit();
}

// Récupération des projets
$resultat = mysqli_query($connexion, "SELECT * FROM projets");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Projets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <?php
	require('admin_header.php');
	?>

    <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Contenu Principal xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <div class="container">
        <h2>Gestion des Projets</h2>
        <a href="admin_ajoutProjet.php" class="btn-add"><i class="fa fa-plus"></i> Ajouter</a>

        <!-- Affichage du message -->
        <?= !empty($_GET['message']) ? $_GET['message'] : '' ?>

        <table class="projects-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultat)) { ?>
                <tr>
                    <td>
                        <?php
                        $imagePath = "../" . htmlspecialchars($row['image']); // pour accéder au dossier uploads
                        if (file_exists($imagePath)) {
                            echo '<img src="' . $imagePath . '" alt="Image projet" width="80" height="80" class="project-image">';
                        } else {
                            echo '<p style="color: red;">Image non trouvée : ' . $imagePath . '</p>';
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($row['titre']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td class="actions-cell">
                        <a href="admin_modifyProjet.php?id=<?= $row['id'] ?>" class="btn-icon edit"><i class="fa fa-edit"></i></a>
                        <a href="admin_Projet.php?delete=<?= $row['id'] ?>" class="btn-icon delete" onclick="return confirm('Voulez-vous supprimer ce projet ?')">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>