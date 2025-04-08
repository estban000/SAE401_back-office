<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    // Validation simple des champs
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Traitement de l'envoi de l'email
        $to = "marcellbanol@hotmail.com"; // Remplace par ton adresse email
        $subject = "Message de $name";
        $body = "Nom: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email";

        // Envoi de l'email
        if (mail($to, $subject, $body, $headers)) {
            // Si le message a été envoyé avec succès, rediriger vers index.php avec le paramètre de succès
            header("Location: ../index.php?message=success");
            exit();  // Arrêter l'exécution du script après la redirection
        } else {
            $error = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
        }
    } else {
        // Si les champs sont vides
        $error = "Tous les champs doivent être remplis.";
    }
}
?>
