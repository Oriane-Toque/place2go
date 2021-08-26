const avatar = {

	apiBaseUrl: 'https://api.multiavatar.com/',

	init: function() {

		// je récupère le bouton pour générer un avatar
		const buttonAvatar = document.getElementById("avatar__generate");

		// je pose un écouteur d'évènement au click
		buttonAvatar.addEventListener("click", avatar.handleLoadAvatar);
	},

	/**
	 * Récupère un avatar aléatoire sous forme png
	 * 
	 * @param {*} evt
	 */
	 handleLoadAvatar: function(evt) {

		evt.preventDefault();

		// Id aléatoire à chaque génération
		let avatarId = Math.floor(Math.random() * 300);
		
		// Récupération de l'avatar 
		let avatarPng = avatar.apiBaseUrl+avatarId+'.png';
		console.log(avatarPng);
	},
};

document.addEventListener('DOMContentLoaded', avatar.init);