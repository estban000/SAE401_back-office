<?php
// Suppression d'un projet
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);

    // Récupérer l'image pour la supprimer du serveur
    $query = mysqli_query($connexion, "SELECT image FROM projets WHERE id=$id");
    $row = mysqli_fetch_assoc($query);
    if ($row) {
        $imagePath = "../" . $row["image"];
        // Vérifier si le fichier existe avant de tenter de le supprimer
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $message = "<p class='success-message'>L'image a été supprimée avec succès.</p>";
            } else {
                $message = "<p class='error-message'>Erreur lors de la suppression de l'image.</p>";
            }
        } else {
            $message = "<p class='error-message'>L'image n'existe pas ou a déjà été supprimée.</p>";
        }

        // Suppression du projet de la base de données
        $deleteQuery = mysqli_query($connexion, "DELETE FROM projets WHERE id=$id");
        if ($deleteQuery) {
            $message .= "<p class='success-message'>Le projet a été supprimé avec succès.</p>";
        } else {
            $message .= "<p class='error-message'>Erreur lors de la suppression du projet de la base de données.</p>";
        }
    } else {
        $message = "<p class='error-message'>Le projet n'existe pas.</p>";
    }

    header("Location: admin_Projet.php?message=" . urlencode($message));
    exit();
}
?>