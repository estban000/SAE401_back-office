<!DOCTYPE HTML>
<!--
	Strata by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Strata by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">
		<?php
		require('connexionTableSQL.php');
		?>
		<?php
		// Récupération des projets
		$resultat = mysqli_query($connexion, "SELECT * FROM projets");
		?>
		<?php
		require('header.php');
		?>

		<!-- Main -->
			<div id="main">

				<!-- INTRODUCTION DE MOI MEME -->
					<section id="one">
						<header class="major">
							<h2>Petite description sur Moi</h2>
						</header>
						<p>Personne de nature calme et motivé à donner le nécessaire pour le travail, mes hobbies ? Les jeux-vidéo de tout type avec les potes (un vrai Gameur).</p>
						<ul class="actions">
							<li><a href="https://drive.google.com/file/d/15ydaPurVqEU3gjz14s-BVDPTRFp2kojJ/view?usp=sharing class="button">Voir le CV</a></li>
						</ul>
					</section>

				<!-- PARTIE PROJET (DYNAMIQUE) -->
					<section id="two">
						<h2>Projets réalisés</h2>
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

				<!-- Three -->
					<section id="three">
						<h2>Contactez-Moi :D</h2>
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