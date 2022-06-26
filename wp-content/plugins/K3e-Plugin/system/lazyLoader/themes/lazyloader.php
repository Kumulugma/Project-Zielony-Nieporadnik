<?php

if (!is_admin()) {
    wp_enqueue_script('Lazy-Load', plugin_dir_url(__FILE__) . "../node_modules/lazyload/lazyload.min.js", null, true);
    wp_enqueue_script('K3eLazyLoader', plugin_dir_url(__FILE__) . "../_assets/K3eLazyLoader.js", ['Lazy-Load'], null, true);
}