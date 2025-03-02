<?php
session_start();
$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('../IDENTIFIANTS/logins.php');

    if ($_POST["username"] == $user && $_POST["password"] == $mdp) {
        $_SESSION["admin"] = $user;
        header("Location: admin_Reseau.php");
        exit();
    } else {
        $erreur = "Identifiants incorrects, veuillez réessayer";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="../assets/css/login_style.css">
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Connexion Administrateur</h2>
        <form class="login-form" method="post">
            <div class="form-group">
                <label class="form-label">Nom d'utilisateur:</label>
                <input type="text" name="username" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mot de passe:</label>
                <input type="password" name="password" class="form-input" required>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
            <!-- Bouton pour revenir au PORTFOLIO -->
        <a href="../index.php" class="btn-other">Revenir au PORTFOLIO</a>
        </form>
    </div>
    <?php if ($erreur) echo "<p style='color:red;'>$erreur</p>"; ?>
</body>
</html>
