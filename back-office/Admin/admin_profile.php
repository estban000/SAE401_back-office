<?php
require('session.php');
require('../connexionTableSQL.php');

// Récupération des profils
$resultat = mysqli_query($connexion, "SELECT * FROM ProfileBlock");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Profile </title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require('admin_header.php'); ?>
    
    <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Contenu Principal xxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <div class="container">
        <h2>Gestion du Profile description</h2>
        <a href="admin_ajoutProfile.php" class="btn-add"><i class="fa fa-plus"></i> Ajouter</a>
        
        <table class="social-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Description</th>
                    <th>CV</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultat)) { ?>
                <tr>
                    <td class="icon-cell">
                        <img src="../uploads/imgProfil/<?= htmlspecialchars($row['imageProfile']) ?>" alt="Profile Image" width="50">
                    </td>
                    <td><?= htmlspecialchars($row['texteDescription']) ?></td>
                    <td>
                        <a href="../uploads/cv/<?= htmlspecialchars($row['cv']) ?>" target="_blank">Télécharger</a>
                    </td>
                    <td class="actions-cell">
                        <a href="admin_modifyProfile.php?id=<?= $row['id'] ?>" class="btn-icon edit"><i class="fa fa-edit"></i></a>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
