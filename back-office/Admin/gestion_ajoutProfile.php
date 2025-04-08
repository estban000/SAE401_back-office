<?php
// soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = mysqli_real_escape_string($connexion, $_POST["texteDescription"]);
    
    // Gestion de l'upload de l'image
    $imageProfile = "";
    if (!empty($_FILES["imageProfile"]["name"])) {
        $imageProfile = basename($_FILES["imageProfile"]["name"]);
        move_uploaded_file($_FILES["imageProfile"]["tmp_name"], "../uploads/imgProfil/" . $imageProfile);
    }
    
    // Gestion de l'upload du CV
    $cv = "";
    if (!empty($_FILES["cv"]["name"])) {
        $cv_extension = pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION);
        if (strtolower($cv_extension) !== "pdf") {
            die("Seuls les fichiers PDF sont autorisés.");
        }
        $cv = basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], "../uploads/cv/" . $cv);
    }
    
    $query = "INSERT INTO ProfileBlock (imageProfile, texteDescription, cv) VALUES ('$imageProfile', '$description', '$cv')";
    mysqli_query($connexion, $query);
    
    header("Location: admin_profile.php");
    exit();
}
?>