<!DOCTYPE HTML>
<!--
	Strata by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>BANOL MORENO Marcell</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">
		<?php
		require('connexionTableSQL.php');
		?>
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

			
		// Récupération des projets
		//$resultat = mysqli_query($connexion, "SELECT * FROM projets");
		?>
		<?php
		require('header.php');
		?>

		<!-- Main -->
			<div id="main">

				<!-- DESCRIPTION DE MOI (entièrement dynamique) -->
				<section id="one">
					<header class="major">
						<h2>Profile</h2>
					</header>

					<?php
					$result = mysqli_query($connexion, "SELECT * FROM ProfileBlock ORDER BY id DESC LIMIT 1");
					$profil = mysqli_fetch_assoc($result);
					?>

					<?php if ($profil): ?>
						<p>
							<span class="image left">
								<img src="uploads/imgProfil/<?= htmlspecialchars($profil['imageProfile']) ?>" alt="Photo de profil" />
							</span>
							<?= nl2br(htmlspecialchars($profil['texteDescription'])) ?>
						</p>
						<ul class="actions">
							<li>
								<a href="uploads/cv/<?= htmlspecialchars($profil['cv']) ?>" class="button" download>Télécharger mon CV</a>
							</li>
						</ul>
					<?php else: ?>
						<p>Aucune description n’a pas encore été ajoutée.</p>
					<?php endif; ?>
				</section>


				<!-- PORTFOLIO (entièrement dynamique) -->
					<section id="two">
						<h2>PORTFOLIO</h2>
						<blockquote>Ensemble de Projets réalisé durant mes années Universitaire.</blockquote>
						<!-- Liste déroulante des catégories -->
						<form method="get" style="margin-bottom: 1em;">
							<label for="categorie">Filtrer par catégorie :</label>
							<select name="categorie" id="categorie" onchange="this.form.submit()">
								<option value="0">Toutes les catégories</option>
								<?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>
									<option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $categorie_filtre) ? 'selected' : '' ?>>
										<?= htmlspecialchars($cat['NomCategory']) ?>
									</option>
								<?php endwhile; ?>
							</select>
						</form>
						<div class="row">
							<?php while ($row = mysqli_fetch_assoc($resultat)) { ?>
								<article class="col-6 col-12-xsmall work-item">
									<a href="<?= htmlspecialchars($row['image']) ?>" class="image fit thumb"><img src="<?= htmlspecialchars($row['image']) ?>" alt="" /></a>
									<h3><?= htmlspecialchars($row['titre']) ?></h3>
									<p><?= htmlspecialchars($row['description']) ?></p>
								</article>
							<?php } ?>
						</div>
					</section>

				<!-- CONTACTEZ-MOI -->
					<section id="three">
						<h2>Contact me :D</h2>
						<p>N'hésitez pas à me contacter je vous répondrais dans les plus brefs délais.</p>
						<div class="row">
							<div class="col-8 col-12-small">
								<form method="post" action="#">
									<div class="row gtr-uniform gtr-50">
										<div class="col-6 col-12-xsmall"><input type="text" name="name" id="name" placeholder="Name" /></div>
										<div class="col-6 col-12-xsmall"><input type="email" name="email" id="email" placeholder="Email" /></div>
										<div class="col-12"><textarea name="message" id="message" placeholder="Message" rows="4"></textarea></div>
									</div>
								</form>
								<ul class="actions">
									<li><input type="submit" value="Send Message" /></li>
								</ul>
							</div>
							<div class="col-4 col-12-small">
								<ul class="labeled-icons">
									<li>
										<h3 class="icon solid fa-home"><span class="label">Adresse</span></h3>
										178 Boulevard Voltaire.<br />
										Asnières-sur-Seine, 92600<br />
										France
									</li>
									<li>
										<h3 class="icon solid fa-envelope"><span class="label">Email</span></h3>
										<a href="mailto:marcellbanol@hotmail.com">marcellbanol@hotmail.com</a>
									</li>
								</ul>
							</div>
						</div>
					</section>
			</div>
			<?php
			require('footer.php');
			?>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.poptrox.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>