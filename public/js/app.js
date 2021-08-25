
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar')
    navbar.classList.toggle('sticky', window.scrollY > 0)
})  

// Auto close Flashmessage after 6sec
if (document.getElementById('flash_messages') != null) {
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
