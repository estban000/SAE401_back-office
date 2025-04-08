<?php
	// Récupération des catégories disponibles dans les projets
	$categories_result = mysqli_query($connexion, "
		SELECT DISTINCT c.id, c.NomCategory
		FROM projets p
		JOIN Categories c ON p.categorie_id = c.id
	");

	// Récupération de la catégorie sélectionnée via GET (si elle existe)
	$categorie_filtre = isset($_GET['categorie']) ? (int) $_GET['categorie'] : 0;

	// Récupération des projets
	$sql = "SELECT * FROM projets";
	if ($categorie_filtre > 0) {
		$sql .= " WHERE categorie_id = $categorie_filtre";
	}
	$resultat = mysqli_query($connexion, $sql);
?>