$( document ).ready(function() {
    console.log( "ready!" );

    // Sticky navbar on scroll
    $(window).scroll(function () {
        $('nav').toggleClass('sticky', $(this).scrollTop() > 50);
        if($(this).scrollTop() > 50){
            $('img.logo').addClass('d-none');
            $('img.logo-sticky').removeClass('d-none');
        }
        else{
            $('img.logo').removeClass('d-none');
            $('img.logo-sticky').addClass('d-none');
        }
        
    });

    if (document.getElementById('flash_messages') != null) {
        setTimeout(() =>
        {
            flashMessage.style.display = 'none'
        }, 6000)
    }
});