<!-- PROJECT SHIELDS -->
<!--
*** This template uses markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
<div align=center>
<!--
[![Contributors][contributors-shield]][contributors-url] [![Forks][forks-shield]][forks-url] [![Stargazers][stars-shield]][stars-url] [![Issues][issues-shield]][issues-url] ![GitHub closed pull requests](https://img.shields.io/github/issues-pr-closed-raw/O-clock-Trinity/projet-place-2-go?style=flat-square)
-->
</div>

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

1. Clone the repo
```sh
git clone git@github.com:O-clock-Trinity/projet-place-2-go.git
```

2. Open VS Code
```sh
cd projet projet-place-2-go
code .
```

3. Install depedencies
```sh
composer install
```
4. Copy / Paste `.env` to `.env.local`

5. Add your Database configuration
   
6. Create the database
```sh
php bin/console doctrine:database:create
```

7. Launch the migrations
```sh
php bin/console doctrine:migrations:migrate
```

8. Launch the fixtures
```sh
php bin/console doctrine:fixtures:load
```

9. Finally, launch a PHP server
```sh
php -S localhost:8000 -t public
```

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
