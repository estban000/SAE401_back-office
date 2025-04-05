<!-- Footer -->
<footer id="footer">
	<div class="inner">
		<!-- xxxxxxxxxxxxxxxxxxxxxx  RESEAU SOCIAUX (entièrement dynamique)  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
		<ul class="icons">
			<?php
				// Requête SQL
				$requete = "SELECT NomReseau, URL, `Icon` FROM ReseauSociaux";
				$resultat = mysqli_query($connexion, $requete);

				
				if ($resultat) {
					while ($row = mysqli_fetch_assoc($resultat)) {
						echo '<li><a href="' . htmlspecialchars($row['URL']) . '"target="_blank" class="icon brands fa-' . htmlspecialchars($row['Icon']) . '">
								<span class="label">' . htmlspecialchars($row['NomReseau']) . '</span></a></li>';
					}
				}

				// Fermer la connexion
				mysqli_close($connexion);
			?>
		</ul>

		<ul class="copyright">
			<li>&copy; Marcell BANOL MORENO</li><li><a href="https://banol-moreno.alwaysdata.net/Semestre4/SAE401/back-office/Admin/admin_login.php">Administration</a></li>
		</ul>
	</div>
</footer>