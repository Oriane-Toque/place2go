<!-- PROJECT SHIELDS -->
<!--
*** This template uses markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->

<!--
<div align=center>
[![Contributors][contributors-shield]][contributors-url] [![Forks][forks-shield]][forks-url] [![Stargazers][stars-shield]][stars-url] [![Issues][issues-shield]][issues-url] ![GitHub closed pull requests](https://img.shields.io/github/issues-pr-closed-raw/O-clock-Trinity/projet-place-2-go?style=flat-square)
</div>
-->
[![Product Name Screen Shot][product-screenshot]](https://example.com)

# Place 2 Go

Application développée en équipe lors de l'apothéose avec l'école O'Clock.

<!-- ABOUT THE PROJECT -->
## About The Project

Ce site a pour but de rassembler plusieurs personnes qui ne se connaissent pas mais qui partagent un même intérêt : sortir.

### Built With

* 💻 VS Code
* ❤ Symfony
* ⚡ Turbo

<!-- USAGE EXAMPLES -->
## Getting Started

```bash
# Clone the repo
git clone git@github.com:O-clock-Trinity/projet-place-2-go.git

# Open VS Code
cd projet projet-place-2-go
code .

# Install depedencies
composer install

# Because of webpack, install node dependencies
npm install

# Create the database
php bin/console doctrine:database:create

# Apply migrations
php bin/console doctrine:migrations:migrate

# Apply fixtures if needed
php bin/console doctrine:fixtures:load

# Launch a PHP server and enjoy our good work !
symfony server:start

```

### How to use webpack ?

Webpack will create a new folder in the root directory called `assets`.
Inside, you will find a `styles` folder with `app.css` where you need to add your css rules.  
This file is imported in `app.js`, where you can add you own javascripts scripts.  
Everytime you add something in these two files, you need to compile to apply changes.

#### Compile assets with webpack

```bash
# Compile assets once
npm run dev

# Or, recompile assets automatically when files change
npm run watch

# On deploy, create a production file
npm run build
```

#### Webpack features

Place 2 go is also using with webpack :

* Sass-Loader
* Sass
* PostCSS
* Autoprefixer

<!-- CONTACT -->
## Project Team

Oriane **oriane.toque@gmail.com**  
[@twitter](https://twitter.com/xxx) - [@linkedin](https://www.linkedin.com/in/xxx/)

Yohann **yohann.hommet@gamil.com**  
[@twitter](https://twitter.com/YoH_DevBack) - [@linkedin](https://www.linkedin.com/in/yohann-hommet/)

Daniel **daniel@gamil.com**  
[@twitter](https://twitter.com/xxx) - [@linkedin](https://www.linkedin.com/in/xxx/)

Fred **fred@gmail.com**  
[@twitter](https://twitter.com/xxx) - [@linkedin](https://www.linkedin.com/in/xxx/)

## Licence

_Code propriétaire_
Toute reproduction est explicetement interdite sans l'accord de tous les créateurs du projet.

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[contributors-shield]: https://img.shields.io/github/contributors/O-clock-Trinity/projet-place-2-go?style=flat-square

[contributors-url]: https://github.com/O-clock-Trinity/projet-place-2-go/graphs/contributors

[forks-shield]: https://img.shields.io/github/forks/O-clock-Trinity/projet-place-2-go?style=flat-square

[forks-url]: https://github.com/O-clock-Trinity/projet-place-2-go/network/members

[stars-shield]: https://img.shields.io/github/stars/O-clock-Trinity/projet-place-2-go?style=flat-square

[stars-url]: https://github.com/O-clock-Trinity/projet-place-2-go/stargazers

[issues-shield]: https://img.shields.io/github/issues/O-clock-Trinity/projet-place-2-go?style=flat-square

[issues-url]: https://github.com/O-clock-Trinity/projet-place-2-go/issues

[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=flat-square&logo=linkedin&colorB=555

[product-screenshot]: public/img/logo_readme.png
