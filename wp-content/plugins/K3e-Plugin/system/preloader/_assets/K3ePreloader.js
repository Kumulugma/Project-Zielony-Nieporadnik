//jQuery(document).ready((function () {
//    jQuery("body").prepend('<div id="preloader" class="d-flex justify-content-center"><div class="spinner-grow" style="width: 5rem; height: 5rem;" role="status"><span class="visually-hidden">Ładowanie...</span></div></div>');
//    setTimeout(function () {
//        jQuery("body").find('#preloader').fadeTo("slow", 0);
//    }, 2000);
//    setTimeout(function () {
//        jQuery("body").find('#preloader').remove('#preloader');
//    }, 3000);
//
//}));
//jQuery(window).load((function () {
//    jQuery("#preloader").fadeTo('slow', 0);
//}));

//jQuery(window).on('load', function () { // makes sure the whole site is loaded 
//    jQuery('#preloader').delay(350).toggle(); // will fade out the white DIV that covers the website. 
//    jQuery('body').delay(350).css({'overflow': 'visible'});
//});

jQuery(document).ready(function($) {
    var Body = $('body');
    Body.addClass('preloader-site');
});
jQuery(window).load(function() {
    jQuery('.preloader-wrapper').fadeOut();
    jQuery('body').removeClass('preloader-site');
});