<?php

// Dodaj kolumnę z miniaturką dla postów
function add_thumbnail_column_posts($columns) {
    // Sprawdź czy kolumna już istnieje
    if (isset($columns['featured_image'])) {
        return $columns;
    }
    
    $new_columns = array();
    foreach ($columns as $key => $value) {
        if ($key == 'title') {
            $new_columns['featured_image'] = 'Miniaturka';
        }
        $new_columns[$key] = $value;
    }
    return $new_columns;
}
add_filter('manage_posts_columns', 'add_thumbnail_column_posts');
add_filter('manage_plant_posts_columns', 'add_thumbnail_column_posts');

// Wyświetl miniaturkę w kolumnie
function display_thumbnail_column($column_name, $post_id) {
    if ($column_name == 'featured_image') {
        // Sprawdź czy już wyświetliliśmy (zabezpieczenie przed duplikatami)
        static $displayed = array();
        $key = $post_id . '_' . $column_name;
        
        if (isset($displayed[$key])) {
            return;
        }
        $displayed[$key] = true;
        
        $thumbnail = get_the_post_thumbnail($post_id, array(60, 60));
        if ($thumbnail) {
            echo $thumbnail;
        } else {
            echo '<span style="color: #999;">—</span>';
        }
    }
}
add_action('manage_posts_custom_column', 'display_thumbnail_column', 10, 2);
add_action('manage_plant_posts_custom_column', 'display_thumbnail_column', 10, 2);

// Ustaw szerokość kolumny
function thumbnail_column_width() {
    echo '<style>
        .column-featured_image {
            width: 80px;
            text-align: center;
        }
        .column-featured_image img {
            border-radius: 4px;
            object-fit: cover;
        }
    </style>';
}
add_action('admin_head', 'thumbnail_column_width');