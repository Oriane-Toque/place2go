
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar')
    navbar.classList.toggle('sticky', window.scrollY > 0)
})  

// Auto close Flashmessage after 6sec
const flashMessage = document.querySelector('#flash_message')
if (flashMessage != null) {
    setTimeout(() =>
    {
        flashMessage.style.display = 'none'
    }, 6000)
}

// Set home page ibnput to 2.5rem height on homepage only
/*if (window.location.pathname == '/') {
    document.querySelector('#q').style.height = '2.5rem'
}*/

// Sidenav
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