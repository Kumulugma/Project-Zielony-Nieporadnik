<?php

if (!is_admin()) {
    wp_enqueue_script('K3ePreloader', plugin_dir_url(__FILE__) . "../_assets/K3ePreloader.js", ['jquery']);
    wp_enqueue_style('K3ePreloader', plugin_dir_url(__FILE__) . "../_assets/K3ePreloader.css");

    // Add code after opening body tag.
    add_action('wp_body_open', 'k3e_preloader_body_open_code');

    function k3e_preloader_body_open_code() {
        echo '<div class="preloader-wrapper">';
        echo '<div class="preloader" class="d-flex justify-content-center">';
        echo '<div class="spinner-grow" style="width: 5rem; height: 5rem;" role="status">';
        echo '<span class="visually-hidden">Ładowanie...</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

}