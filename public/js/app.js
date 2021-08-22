
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar')
    navbar.classList.toggle('sticky', window.scrollY > 0)

    if (window.scrollY > 0) {
        this.document.querySelector('img.logo').classList.add('d-none')
        this.document.querySelector('img.logo-sticky').classList.remove('d-none')
    } else {    
        this.document.qquerySelector('img.logo').classList.remove('d-none')
        this.document.querySelector('img.logo-sticky').classList.add('d-none')
    }
})  


if (document.getElementById('flash_messages') != null) {
    setTimeout(() =>
    {
        flashMessage.style.display = 'none'
    }, 6000)
}

