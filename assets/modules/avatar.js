export const avatar = {
  apiBaseUrl: "https://api.multiavatar.com/",

  init: function () {
    // je récupère le bouton pour générer un avatar
    const buttonAvatar = document.getElementById("avatar__generate");
    // je récupère mon image
    const imgAvatar = document.querySelector(".avatar__generated > img");

    // je pose un écouteur d'évènement au click
    if (buttonAvatar != null) {
      buttonAvatar.addEventListener("click", avatar.handleLoadAvatar);
    }

    if (imgAvatar != null) {
      // je pose un écouteur d'évènement au chargement de l'image
      imgAvatar.addEventListener("load", avatar.handleStopLoader);
    }
  },

  /**
   * Récupère un avatar aléatoire sous forme png
   *
   * @param {*} evt
   */
  handleLoadAvatar: function (evt) {
    evt.preventDefault();

    // Anime un loader pour patienter durant la génération de l'avatar
    const buttonAvatar = evt.currentTarget;
    buttonAvatar.classList.add("button--loading");

    // Id aléatoire à chaque génération
    let avatarId = Math.floor(Math.random() * 300);

    // Récupération de l'avatar
    let avatarPng = avatar.apiBaseUrl + avatarId + ".png";

    // méthode pour afficher la prévisualisation de l'avatar
    avatar.previewAvatar(avatarPng);

    // méthode pour changer la value de mon hidden input
    avatar.setAvatar(avatarPng);
  },

  /**
   * Stop le loader quand l'image de l'avatar est chargé
   *
   * @param {*} evt
   */
  handleStopLoader: function (evt) {
    // retire la classe enclenchant l'animation du loader
    const buttonAvatar = document.getElementById("avatar__generate");
    buttonAvatar.classList.remove("button--loading");
  },

  /**
   * Prévisualise l'avatar généré
   *
   * @param {*} avatarPng
   */
  previewAvatar: function (avatarPng) {
    // je récupère mon image
    const avatarImg = document.querySelector(".avatar__generated > img");
    // j'injecte mon Png à l'intérieur
    avatarImg.src = avatarPng;
  },

  /**
   * Set la valeur de l'input avec l'avatar généré
   *
   * @param {*} avatarPng
   */
  setAvatar: function (avatarPng) {
    // je récupère l'input pour mon avatar
    const inputAvatar = document.getElementById("registration_form_avatar");
    // je lui donne pour value l'adresse de mon png
    inputAvatar.value = avatarPng;
  },
};

document.addEventListener("DOMContentLoaded", avatar.init);
