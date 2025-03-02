<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

require('../connexionTableSQL.php');

$id = intval($_GET["id"]);
$resultat = mysqli_query($connexion, "SELECT * FROM ReseauSociaux WHERE id=$id");
$row = mysqli_fetch_assoc($resultat);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NomReseau = mysqli_real_escape_string($connexion, $_POST["NomReseau"]);
    $URL = mysqli_real_escape_string($connexion, $_POST["URL"]);
    $Icon = mysqli_real_escape_string($connexion, $_POST["Icon"]);

    mysqli_query($connexion, "UPDATE ReseauSociaux SET NomReseau='$NomReseau', URL='$URL', Icon='$Icon' WHERE id=$id");
    header("Location: admin_Reseau.php");
    exit();
}
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
    <!-- Barre de navigation -->
    <nav class="navbar">
        <h1>Administration</h1>
        <ul class="nav-links">
            <li><a href="admin_logout.php" class="logout-btn">Déconnexion</a></li>
        </ul>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <h2>Modification du Réseau Social</h2>

        <form method="post" class="edit-form">
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

            <button type="submit" class="btn-primary">Modifier</button>
        </form>

        <!-- Bouton de retour -->
        <a href="admin_Reseau.php" class="btn-back">Retour</a>
    </div>

</body>
</html>
