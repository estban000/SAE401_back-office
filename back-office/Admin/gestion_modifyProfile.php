<?php
$id = intval($_GET["id"]);
$result = mysqli_query($connexion, "SELECT * FROM ProfileBlock WHERE id=$id");
$profil = mysqli_fetch_assoc($result);

if (!$profil) {
    header("Location: admin_profile.php");
    exit();
}

// soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = mysqli_real_escape_string($connexion, $_POST["texteDescription"]);
    
    // Gestion de l'upload de l'image
    if (!empty($_FILES["imageProfile"]["name"])) {
        $imageProfile = basename($_FILES["imageProfile"]["name"]);
        move_uploaded_file($_FILES["imageProfile"]["tmp_name"], "../uploads/imgProfile" . $imageProfile);
    } else {
        $imageProfile = $profil["imageProfile"];
    }
    
    // Gestion de l'upload du CV
    if (!empty($_FILES["cv"]["name"])) {
        $cv_extension = pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION);
        if (strtolower($cv_extension) !== "pdf") {
            die("Seuls les fichiers PDF sont autorisés.");
        }
        $cv = basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], "../uploads/cv/" . $cv);
    } else {
        $cv = $profil["cv"];
    }
    
    // Mise à jour des données dans la base
    $query = "UPDATE ProfileBlock SET imageProfile='$imageProfile', texteDescription='$description', cv='$cv' WHERE id=$id";
    mysqli_query($connexion, $query);
    
    header("Location: admin_profile.php");
    exit();
}
?>