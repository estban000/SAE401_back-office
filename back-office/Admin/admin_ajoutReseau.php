<?php
require('session.php');
require('../connexionTableSQL.php');
require('gestion_ajoutReseau.php');

// soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NomReseau = mysqli_real_escape_string($connexion, $_POST["NomReseau"]);
    $URL = mysqli_real_escape_string($connexion, $_POST["URL"]);
    $Icon = mysqli_real_escape_string($connexion, $_POST["Icon"]);

    $query = "INSERT INTO ReseauSociaux (NomReseau, URL, Icon) VALUES ('$NomReseau', '$URL', '$Icon')";
    if (mysqli_query($connexion, $query)) {
        header("Location: admin_Reseau.php");
        exit();
    } else {
        $error_message = "Erreur lors de l'ajout du réseau social.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Réseau Social</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <?php
	require('admin_header.php');
	?>

    <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxx Contenu principal xxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
    <div class="container">
        <h2>Ajouter un Réseau Social</h2>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form method="post" class="form-container">
            <div class="form-group">
                <label for="NomReseau">Nom du Réseau :</label>
                <input type="text" id="NomReseau" name="NomReseau" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="URL">URL du réseau :</label>
                <input type="url" id="URL" name="URL" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="Icon">Icône :</label>
                <input type="text" id="Icon" name="Icon" class="form-input" required>
            </div>

            <button type="submit" class="btn btn-add">Ajouter</button>
        </form>

        <!-- Bouton de retour -->
        <a href="admin_Reseau.php" class="btn btn-back">Retour</a>
    </div>

</body>
</html>
