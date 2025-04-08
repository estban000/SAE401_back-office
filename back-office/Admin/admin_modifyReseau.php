<?php
require('session.php');
require('../connexionTableSQL.php');
require('gestion_modifyReseau.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Réseau Social</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <?php
	require('admin_header.php');
	?>

    <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxx Contenu principal xxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <div class="container">
        <h2>Modification du Réseau Social</h2>

        <form method="post" class="form-container">
            <div class="form-group">
                <label for="NomReseau">Nom du Réseau :</label>
                <input type="text" id="NomReseau" name="NomReseau" value="<?= htmlspecialchars($row['NomReseau']) ?>" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="URL">URL :</label>
                <input type="url" id="URL" name="URL" value="<?= htmlspecialchars($row['URL']) ?>" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="Icon">Icône :</label>
                <input type="text" id="Icon" name="Icon" value="<?= htmlspecialchars($row['Icon']) ?>" class="form-input" required>
            </div>

            <button type="submit" class="btn-submit">Modifier</button>
        </form>

        <!-- Bouton de retour -->
        <a href="admin_Reseau.php" class="btn-back">Retour</a>
    </div>

</body>
</html>
