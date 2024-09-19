$(function() {
    // Show dropdown when clicked
    $('#header-btn').on('click', function(e) {
        $('#header-menu').toggleClass('active');
        $('.nav-btn').toggleClass('active');
    });

    // Hide menu after clicking menu item
    $('.dropdown-menu li').on('click', function(e) {
        $('#header-menu').removeClass('active');
        $('.nav-btn').removeClass('active');
    });

    // scroll body to 0px on click
    $('#back-top').click(function () {
        $('body,html').animate({
          scrollTop: 0 
        }, 800);
        return false; 
    });

    // drop down menu desktop
    $('.has-sub').on('click', function(e) { // Get all dropdown menu toggles
        $('.dropdown-menu').not($(this).children('.dropdown-menu')).removeClass('dropdown-shown'); // Hide all other dropdown menus
        $('.has-sub').not($(this)).removeClass('active'); // Remove the active selector from the other dropdown toggles
        $(this).children('.dropdown-menu').toggleClass('dropdown-shown'); // Show/hide the dropdown menu associated with the toggle being clicked
        $(this).toggleClass('active'); // Toggle the active selector on the nav-item
        if($('.dropdown-menu').hasClass('dropdown-shown')){
            // menu is already open when a click to close is present
            $('.dropdown-background').addClass('dropdown-shown');
        }else {
            $('.dropdown-background').removeClass('dropdown-shown');
        }        
    });

    sal(); 
});

// fade in/out on scroll #scroll-top and navbar

$(window).scroll(function () {
    if ($(window).scrollTop() > 20) {
        $('#scroll-down').fadeOut();
        $('#navbar-desktop').addClass('nav-shrink');
    }else{
        $('#navbar-desktop').removeClass('nav-shrink');
    }
});

if($(window).scrollTop() > 20){
    $('#navbar-desktop').addClass('nav-shrink');
    $('#scroll-down').fadeOut();
}

// Back to top on scroll
$(window).scroll(function () {
    if ($(window).scrollTop() > 50) {
        $('#back-top').fadeIn();

    }else{
        $('#back-top').fadeOut();
    }
});

if($(window).scrollTop() > 50){
    $('#back-top').fadeIn();
    $('#back-top').css('display', 'flex');
}else{
    $('#back-top').fadeOut();
}

// AOS.init();
  
$(window).load(function(){
    $('.page').addClass('fade'); 
});