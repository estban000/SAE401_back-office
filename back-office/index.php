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

				<!-- DESCRIPTION DE MOI -->
					<section id="one">
						<header class="major">
							<h2>Description de Moi</h2>
						</header>
						<p><span class="image left"><img src="images/avatar.jpg" alt="" /></span>Personne de nature calme et motivé à donner le nécessaire pour le travail, le peu d'experience se resume à aidé au chantier (père ouvrier) mais ça m'aura appris le travail en équipe.<br/>Ce site permet d'observer l'ensemble des projets réalisé au cours de mon année universitaire et qui me servent de valise pour celles et ceux qui sont interreser par mon profile. <br/>Mes hobbies ? Les jeux-vidéo de tout type avec les potes (un vrai Gameur).</p>
						<ul class="actions">
							<li><a href="https://drive.google.com/uc?export=download&id=1e5zLEF2j7guQbxQHHW-DXvxGYOdrTfhD" class="button" download="BANOL MORENO_CV.pdf">Télécharger le CV</a></li>
						</ul>
					</section>

				<!-- PORTFOLIO (entièrement dynamique) -->
					<section id="two">
						<h2>PORTFOLIO</h2>
						<blockquote>Ensemble de Projets réalisé durant mes années Universitaire.</blockquote>
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