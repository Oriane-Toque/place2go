const avatar = {

	apiBaseUrl: 'https://api.multiavatar.com/',

	init: function() {

		// je récupère le bouton pour générer un avatar
		const buttonAvatar = document.getElementById("avatar__generate");

		// je pose un écouteur d'évènement au click
		buttonAvatar.addEventListener("click", avatar.handleLoadAvatar);
	},

};

document.addEventListener('DOMContentLoaded', avatar.init);