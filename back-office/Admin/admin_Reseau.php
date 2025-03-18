<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

// Suppression d'un réseau social
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    mysqli_query($connexion, "DELETE FROM ReseauSociaux WHERE id=$id");
    header("Location: admin_Reseau.php");
    exit();
}

// Récupération des réseaux sociaux
$resultat = mysqli_query($connexion, "SELECT * FROM ReseauSociaux");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Réseaux Sociaux</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
	require('admin_header.php');
	?>

    <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Contenu Principal xxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <div class="container">
        <h2>Gestion des Réseaux Sociaux</h2>
        <a href="admin_ajoutReseau.php" class="btn-add"><i class="fa fa-plus"></i> Ajouter</a>
        
        <table class="social-table">
            <thead>
                <tr>
                    <th>Icône</th>
                    <th>Nom</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultat)) { ?>
                <tr>
                    <td class="icon-cell"><i class="fa-brands fa-<?= htmlspecialchars($row['Icon']) ?>"></i></td>
                    <td><?= htmlspecialchars($row['NomReseau']) ?></td>
                    <td><a href="<?= htmlspecialchars($row['URL']) ?>" target="_blank"><?= htmlspecialchars($row['URL']) ?></a></td>
                    <td class="actions-cell">
                        <a href="admin_modifyReseau.php?id=<?= $row['Id'] ?>" class="btn-icon edit"><i class="fa fa-edit"></i></a>
                        <a href="admin_Reseau.php?delete=<?= $row['Id'] ?>" class="btn-icon delete" onclick="return confirm('Voulez-vous supprimer ce réseau ?')">
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
