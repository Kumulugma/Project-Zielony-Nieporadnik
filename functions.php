<?php

//Register headerAsset
include("components/headerAsset.php");
//Register Read More
include("includes/readMore.php");
//Register Load More News
include("includes/loadMoreNews.php");
//Menu link Class
include("includes/menuLinkClass.php");
//Post Views
include("includes/postViews.php");
//CPT Plants
include("includes/cpt-plants.php");
//Disable Comments
include("includes/disable-comments.php");
//Thumbnails Admin
include("includes/thumbnails-admin.php");



add_theme_support('post-thumbnails', array('post', 'plant'));

// Funkcja pobierająca miniaturkę z najnowszego wpisu w grupie
function get_plant_group_thumbnail($term_id, $size = 'medium') {
    $args = array(
        'post_type' => 'plant',
        'posts_per_page' => 1,
        'tax_query' => array(
            array(
                'taxonomy' => 'plant-group',
                'field' => 'term_id',
                'terms' => $term_id,
            ),
        ),
        'meta_key' => '_thumbnail_id', // Tylko posty z miniaturką
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        $query->the_post();
        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), $size);
        wp_reset_postdata();
        return $thumbnail;
    }
    
    return false;
}