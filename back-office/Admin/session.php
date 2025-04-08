<?php
session_start();

// Vérifie que l'administrateur est bien connecté
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}
?>