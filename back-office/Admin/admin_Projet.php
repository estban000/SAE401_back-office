<?php
require('session.php');
require('../connexionTableSQL.php');
$message = "";

require('gestion_suppressionProjet.php');

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
                    <th>URL</th>
                    <th>Lien vidéo</th>
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
                    <td>
                        <?php
                        if (!empty($row['url_projet'])) {
                            echo '<a href="' . htmlspecialchars($row['url_projet']) . '" target="_blank">Voir le projet</a>';
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (!empty($row['video'])) {
                            echo '<a href="' . htmlspecialchars($row['video']) . '" target="_blank">Voir la vidéo</a>';
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
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