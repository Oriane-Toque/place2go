/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/admin.css";

// start the Stimulus application
import "./bootstrap";


(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
    
    // Toggle the side navigation when window is resized below 480px
    if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
      $("body").addClass("sidebar-toggled");
      $(".sidebar").addClass("toggled");
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });
  
  // Display user details in floating div
  $( ".user-details-floating" )
  .on("mouseenter", function (e) {

    var $floating = $(this).find(".details-floating");

    if ($floating.length > 0){
      $floating.show();
    }
    else{

      var $anchor = $(this);
      var url = $anchor.attr('href') + '/floating';
      var html = '';

      $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        success: function(data){

          html += '<div class="details-floating position-absolute p-4 bg-white shadow">';
          html += '<h3>' + data.firstname + ' ' + data.lastname + '</h3>';
          html += '<img src="' + data.avatar + '" width="150" class="mx-auto mb-2">';
          html += '<p class="mb-0">Ville : ' + data.city + '</p>';
          html += '<p class="mb-0">Age : ' + data.birthday + '</p>';
          html += '<p class="mb-0">Inscrit depuis le ' + data.createdAt + '</p>';
          html += '</div>';

          $anchor.append(html);
        }
      })
    }
    
  }).on("mouseout", function () {
    $( ".details-floating" ).hide();
  });

})(jQuery); // End of use strict
