
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


//counting characters in comment area
let textarea = document.getElementById('comment_content');

textarea.addEventListener("input", event => {
    const target = event.currentTarget;
    const currentLength = target.value.length;
    
    var count = document.getElementById('count');
    count.innerHTML = `${currentLength}/500 caractères`;

    if(currentLength > 500){
        textarea.style.border ="1px solid red"
        count.innerHTML = "Vous avez dépassé la limite de caractères autorisés"
    } else {
        textarea.style.border ="1px solid #00b4d4"
    }
});


// Home Side
function toggleSidenav() {
    document.querySelector('.sidenav').classList.toggle('open');
}


/**
 * Function to generate Toast
 * 
 * @param options object
 * 
 *  options.content
 *  options.action.url
 *  options.action.label
 *  options.cancel.label
 * 
 * @return toast
 * 
 */ 


// Delete a friend
function removeFriend(item)
{
    var url = item.getAttribute("data-url");
    var nickname = item.getAttribute("data-nickname");

    if ( confirm( 'Supprimer ' + nickname + ' ?' ) ) {
        const xhttp = new XMLHttpRequest();
        xhttp.open("GET", url);
        xhttp.send();
        item.closest('.chip').remove();
    }
}