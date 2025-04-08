<?php
$id = intval($_GET["id"]);
$resultat = mysqli_query($connexion, "SELECT * FROM ReseauSociaux WHERE id=$id");
$row = mysqli_fetch_assoc($resultat);

// soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NomReseau = mysqli_real_escape_string($connexion, $_POST["NomReseau"]);
    $URL = mysqli_real_escape_string($connexion, $_POST["URL"]);
    $Icon = mysqli_real_escape_string($connexion, $_POST["Icon"]);

    mysqli_query($connexion, "UPDATE ReseauSociaux SET NomReseau='$NomReseau', URL='$URL', Icon='$Icon' WHERE id=$id");
    header("Location: admin_Reseau.php");
    exit();
}
?>