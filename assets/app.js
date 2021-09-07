/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";
import "./modules/avatar.js";
import "./modules/errors.js";
import "./modules/vanilla-tilt.min.js";

// start the Stimulus application
import "./bootstrap";


// Open the sidenav when clicking on the burger menu
window.toggleSidenav = function () {
  document.querySelector(".sidenav").classList.toggle("open");
};


// Remove a friend
window.removeFriend = function (item) {
  let url = item.getAttribute("data-url");
  let nickname = item.getAttribute("data-nickname");

  if (confirm("Supprimer " + nickname + " de vos amis ?"))
  {
    fetch(url, {
      method: 'DELETE'
    })
    .then(function(response) {
      response.text().then(function(text) {
        if(response){
          item.closest(".chip").remove();
        }
      });
    })
    .catch(err => console.log(err));
  }
};

window.addEventListener("scroll", function () {
  const navbar = document.querySelector(".navbar");
  navbar.classList.toggle("sticky", window.scrollY > 0);
});

// Auto close Flashmessage after 6sec
const flashMessage = document.querySelector("#flash_messages");
if (flashMessage != null) {
  setTimeout(() => {
    flashMessage.style.display = "none";
  }, 6000);
}

// toggle comment box
const targetDiv = document.querySelector(".create__comment");
const btn = document.getElementById("toggle__comments");

if (btn != null) {
  btn.onclick = function () {
    if (targetDiv.classList.contains("create__comment")) {
      targetDiv.classList.replace("create__comment", "create__comment_toggle");
      btn.style.display = "none";
    } else {
      targetDiv.classList.replace("create__comment_toggle", "create__comment");
    }
  };
}

//cancel comment
const cancelbtn = document.getElementById("cancel__comment");
if (cancelbtn != null) {
  cancelbtn.onclick = function () {
    if (targetDiv.classList.contains("create__comment_toggle")) {
      targetDiv.classList.replace("create__comment_toggle", "create__comment");
      btn.style.display = "block";
    }
  };
}

//counting characters in comment area
let textarea = document.getElementById("comment_content");
if (textarea != null) {
  textarea.addEventListener("input", (event) => {
    const target = event.currentTarget;
    const currentLength = target.value.length;

    var count = document.getElementById("count");
    count.innerHTML = `${currentLength}/500 caractères`;

    if (currentLength > 500) {
      textarea.style.border = "1px solid red";
      count.innerHTML = "Vous avez dépassé la limite de caractères autorisés";
    } else {
      textarea.style.border = "1px solid #00b4d4";
    }
  });
}

const textDisplay = document.getElementById('text')
const bold = {
  go: '2 Go',
  move: '2 Be..',
  party: '2 Party..',
  friends: '2 Make Friends..',
  fun: '2 have Fun..',
  people: "2 Meet People.."
}

const phrases = [`lace ${bold.go} !!`, `lace ${bold.move}`, `lace ${bold.party}`, `lace ${bold.friends}`, `lace ${bold.fun}`, `lace ${bold.people}`]
let i = 0
let j = 0
let currentPhrase = []
let isDeleting = false
let isEnd = false

function loop() {
    isEnd = false

    if (i < phrases.length) {

        if (!isDeleting && j <= phrases[i].length) {
            currentPhrase.push(phrases[i][j])
            j++
            textDisplay.innerHTML = currentPhrase.join('')
        }

        if (isDeleting && j <= phrases[i].length) {
            currentPhrase.pop(phrases[i][j])
            j--
            textDisplay.innerHTML = currentPhrase.join('')
        }

        if (j == phrases[i].length) {
            isEnd = true
            isDeleting = true
        }

        if (isDeleting && j === 0) {
            currentPhrase = []
            isDeleting = false
            i++
            if (i == phrases.length) {
                i = 0
            }
        }
    }
    const speedUp = Math.random() * (100 - 50) + 50
    const normalSpeed = Math.random() * (200 - 100) + 100
    const time = isEnd ? 1500 : isDeleting ? speedUp : normalSpeed
    setTimeout(loop, time)
}
loop()