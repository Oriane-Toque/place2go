export const errors = {

	dadaFound: false,
	dada: Math.floor(Math.random() * (6 - 1) + 1),

	init: function () {

		console.log(errors.dadaFound);

		// MISE EN PLACE DU JEU, ON CACHE DADA DE MANIERE ALEATOIRE
		// je récupère toutes les portes
		let doors = document.querySelectorAll('.where_is_dada');

		// je convertis au format tableau
		doors = Array.from(doors);

		// je mélange mon tableau
		doors = errors.shuffle(doors);

		let counter = 1;

		// si pas fin du jeu
		if (errors.dadaFound == false) {
			// je boucle sur mes portes
			for (const door of doors) {
				// je leur attribue un dataset
				door.dataset.dada = counter++;

				// et un écouteur d'évènement
				door.addEventListener('click', errors.handleDadaIsHere);
			}
		} 

		if (errors.dadaFound == true) {
			for (const door of doors) {

				// je récupère la balise contenant le message
				const message = document.querySelector('.error__message');
				// j'informe l'utilisateur qu'il a gagné
				message.textContent = "Merci d'avoir retrouvé Dada pour nous, votre porte de sortie vient d'apparaître ! A bientôt peut être ...";

				door.removeEventListener('click', errors.handleDadaIsHere);
			}
		}
	},

	/**
	 * Check if you are a winner
	 * 
	 * @param {*} evt
	 * @returns Void
	 */
	handleDadaIsHere: function (evt) {

		// je récupère la porte sélectionnée
		const door = evt.currentTarget;

		// je récupère son dataset
		const dataDoor = door.dataset.dada;

		// je check si c'est bien dada
		if (errors.isDada(dataDoor)) {

			errors.dadaFound = true;
			// je récupère mon bouton pour sortir de la page d'erreur
			const backHome = document.getElementById('error__win');

			backHome.style.display = "initial";

			// j'insère l'image dada
			door.src = "/img/dada.png";
			door.classList.add("avatar__game");

			// je récupère la balise contenant le message
			const message = document.querySelector('.error__message');
			// j'informe l'utilisateur qu'il a gagné
			message.textContent = "Merci d'avoir retrouvé Dada pour nous, votre porte de sortie vient d'apparaître ! A bientôt peut être ...";

		} 
		
		if(!errors.isDada(dataDoor)){
			// je récupère la balise contenant le message
			const message = document.querySelector('.error__message');
			// sinon pas de chance ce n'était pas la bonne
			message.textContent = errors.randomMessage();
			errors.init();
		}
	},

	/**
	 * Check if is dada behind this door
	 * 
	 * @param {*} dataDoor 
	 * @returns Bool
	 */
	isDada: function (dataDoor) {

		if (errors.dada == dataDoor) {
			return true;
		} else {
			return false;
		}
	},

	randomMessage: function () {

		const dadaMessage = [
			"Noooon !!! Dada, n'est pas là, attention il vient de changer de cachette ! Quel vicieux !",
			"Toujours pas, Dada est plus malin que toi, et ne croit pas sortir d'ici sans avoir perdu de cheveux !",
			"Tu t'es cru chez Tante Esther ? C'est pas du tout cuit, même elle aurait plus de chance de retrouver Dada !",
			"Par mes Ancêtres, quels calamités, je t'abandonne pour les rejoindre, tu me fais honte !",
		];

		return dadaMessage[Math.floor(Math.random() * 4)];
	},

	/**
	 * Shuffle an array
	 * 
	 * @param {*} array 
	 * @returns Array
	 */
	shuffle: function (array) {

		return array.sort(() => Math.random() - 0.5);
	},
}

document.addEventListener('DOMContentLoaded', errors.init);