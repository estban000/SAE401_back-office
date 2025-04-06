
document.addEventListener('DOMContentLoaded', function () {
	// Pour réinitialiser le message de confirmation après 5 secondes
	const message = document.getElementById('confirmation-message');
		if (message) {
			setTimeout(() => {
				message.style.transition = "opacity 1s ease";
				message.style.opacity = 0;
				setTimeout(() => message.remove(), 1000); // Retire le message du DOM après la transition
		}, 5000); // 5 secondes
	}

	// Réinitialiser les champs et labels après soumission du formulaire
	const form = document.querySelector('form');
		if (form) {
			form.addEventListener('submit', function () {
				// Réinitialise les champs de formulaire
				form.reset();

				// Remet les labels à leur état initial
				const labels = form.querySelectorAll('label');
				labels.forEach(function (label) {
					label.classList.remove('active'); // Si tu utilises une classe active pour les labels
				});
		    });
	    }
});
