<?php
	// Connexion à la base de données
	require_once('IDENTIFIANTS/id_SQL.php');
	// Connexion à la base de données
	$connexion = mysqli_connect($servername, $username, $password, $database);

	// Vérifier la connexion
	if (!$connexion) {
		die("Erreur de connexion : " . mysqli_connect_error());
	}
?>