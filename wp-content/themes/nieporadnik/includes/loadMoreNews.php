<?php

function loadmore_scripts() {

    global $wp_query;

    wp_enqueue_script('jquery');

    wp_register_script('loadmore', get_template_directory_uri() . '/assets/js/loadmore.js', array('jquery'));

    wp_localize_script('loadmore', 'loadmore_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
        'posts' => json_encode($wp_query->query_vars), // everything about your loop is here
        'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
    ));

    wp_enqueue_script('loadmore');
}

add_action('wp_enqueue_scripts', 'loadmore_scripts');

function loadmore_ajax_handler() {

    echo get_template_part('template-parts/blog/ajax_posts');

    die;
}

add_action('wp_ajax_loadmore', 'loadmore_ajax_handler');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_ajax_handler');
