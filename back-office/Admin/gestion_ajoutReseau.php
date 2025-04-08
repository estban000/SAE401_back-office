<?php
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