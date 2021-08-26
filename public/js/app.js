
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar')
    navbar.classList.toggle('sticky', window.scrollY > 0)

})  

// Auto close Flashmessage after 6sec
const flashMessage = document.querySelector('#flash_messages')
if (flashMessage != null) {
    setTimeout(() =>
    {
        flashMessage.style.display = 'none'
    }, 6000)
}

// Set home page ibnput to 2.5rem height on homepage only
if (window.location.pathname == '/') {
    document.querySelector('#q').style.height = '2.5rem'
}

// toggle comment box
const targetDiv = document.querySelector(".create__comment");
const btn = document.getElementById("toggle__comments");
btn.onclick = function () {
    if (targetDiv.classList.contains("create__comment")) {
    targetDiv.classList.replace("create__comment", "create__comment_toggle")
    btn.style.display = "none"
    } else {
    targetDiv.classList.replace("create__comment_toggle", "create__comment")
    }
};

//cancel comment
const cancelbtn = document.getElementById("cancel__comment");
cancelbtn.onclick = function () {
    if (targetDiv.classList.contains("create__comment_toggle")) {
        targetDiv.classList.replace("create__comment_toggle", "create__comment")
        btn.style.display = "block"
    } 
};



