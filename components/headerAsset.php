<?php

add_action('get_header', function () {
    if (!is_admin()) {

        wp_enqueue_style('Barlow', 'https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@100&display=swap');
        wp_enqueue_style('Roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
        wp_enqueue_style('All', get_template_directory_uri() . '/assets/css/font-awesome/all.min.css');
        wp_enqueue_style('Bootstrap', get_template_directory_uri() . '/assets/css/bootstrap/bootstrap.min.css');
        wp_enqueue_style('Select2', get_template_directory_uri() . '/assets/css/select2/select2.css');
        wp_enqueue_style('Owl', get_template_directory_uri() . '/assets/css/owl-carousel/owl.carousel.min.css');
        wp_enqueue_style('Swiper', get_template_directory_uri() . '/assets/css/swiper/swiper.min.css');
        wp_enqueue_style('Article', get_template_directory_uri() . '/assets/css/animate/animate.min.css');
        wp_enqueue_style('Style', get_template_directory_uri() . '/assets/css/style.css');

        wp_enqueue_script('jQuery', get_template_directory_uri() . '/assets/js/jquery-3.5.1.min.js', false, '1.0', 'all');
        wp_enqueue_script('Popper', get_template_directory_uri() . '/assets/js/popper/popper.min.js', false, '1.0', 'all');
        wp_enqueue_script('Bootstrap', get_template_directory_uri() . '/assets/js/bootstrap/bootstrap.min.js', false, '1.0', 'all');
        wp_enqueue_script('jQuery-appear', get_template_directory_uri() . '/assets/js/jquery.appear.js', false, '1.0', 'all');
        wp_enqueue_script('Owl', get_template_directory_uri() . '/assets/js/owl-carousel/owl.carousel.min.js', false, '1.0', 'all');
        wp_enqueue_script('Swiper', get_template_directory_uri() . '/assets/js/swiper/swiper.min.js', false, '1.0', 'all');
        wp_enqueue_script('SwiperAnimation', get_template_directory_uri() . '/assets/js/swiperanimation/SwiperAnimation.min.js', false, '1.0', 'all');
        wp_enqueue_script('instagramFeed', get_template_directory_uri() . '/assets/js/instagramFeed/jquery.instagramFeed.p.min.js', false, '1.0', 'all');
        wp_enqueue_script('Custom', get_template_directory_uri() . '/assets/js/custom.js', false, '1.0', 'all');
    }

    if (is_404()) {
        wp_enqueue_script('404', get_template_directory_uri() . '/assets/js/404.js', false, '1.0', 'all');
        wp_enqueue_style('404', get_template_directory_uri() . '/assets/css/404.css', false, '1.0', 'all');
    }
});

add_action('get_header', function () {
    if (is_single()) {
        
    }

    if (is_search()) {
        
    }

    if (is_tag()) {
        
    }

    if (is_tag()) {
        
    }

    if (is_404()) {
        
    }

    if (is_front_page()) {
        
    }

    if (is_category()) {
        
    }
});

