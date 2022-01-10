<?php

//Register headerAsset
include("components/headerAsset.php");
//Register Read More
include("includes/readMore.php");
//Register Load More News
include("includes/loadMoreNews.php");

add_theme_support('post-thumbnails');
add_theme_support('menus');

add_image_size('sticky', 350, 100, true);
add_image_size('news', 360, 220, true);

register_nav_menus(
        [
            'footer-left' => esc_html__('Stopka - lewa', 'nieporadnik'),
            'footer-right' => esc_html__('Stopka - prawa', 'nieporadnik'),
            'main' => esc_html__('Menu główne', 'nieporadnik')
        ],
);

function add_menu_link_class($atts, $item, $args) {
    if (property_exists($args, 'link_class')) {
        $atts['class'] = $args->link_class;
    }
    return $atts;
}

add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);

function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

//To keep the count accurate, lets get rid of prefetching
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function wpb_track_post_views($post_id) {
    if (!is_single())
        return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    wpb_set_post_views($post_id);
}

add_action('wp_head', 'wpb_track_post_views');

function add_cors_http_header() {
    header("Access-Control-Allow-Headers: Authorization, Content-Type");
    header("Access-Control-Allow-Origin: *");
    header('content-type: application/json; charset=utf-8');
}

add_action('init', 'add_cors_http_header');
