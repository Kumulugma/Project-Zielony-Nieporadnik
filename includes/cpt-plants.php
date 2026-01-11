<?php

// Rejestracja Custom Post Type - Rośliny
function create_plant_post_type() {
    register_post_type('plant',
        array(
            'labels' => array(
                'name' => __('Rośliny'),
                'singular_name' => __('Roślina'),
                'add_new' => __('Dodaj nową'),
                'add_new_item' => __('Dodaj nową roślinę'),
                'edit_item' => __('Edytuj roślinę'),
                'new_item' => __('Nowa roślina'),
                'view_item' => __('Zobacz roślinę'),
                'search_items' => __('Szukaj roślin'),
                'not_found' => __('Nie znaleziono roślin'),
                'not_found_in_trash' => __('Brak roślin w koszu')
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-carrot',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'rewrite' => array('slug' => 'rosliny'),
            'taxonomies' => array('category', 'post_tag'), // Wspólne kategorie i tagi!
        )
    );
}
add_action('init', 'create_plant_post_type');

// Rejestracja Taxonomii - Grupy roślin
function create_plant_group_taxonomy() {
    register_taxonomy(
        'plant-group',
        'plant',
        array(
            'label' => __('Grupy roślin'),
            'labels' => array(
                'name' => __('Grupy roślin'),
                'singular_name' => __('Grupa'),
                'add_new_item' => __('Dodaj nową grupę'),
                'new_item_name' => __('Nowa grupa')
            ),
            'rewrite' => array('slug' => 'grupa'),
            'hierarchical' => true,
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'create_plant_group_taxonomy');

// Meta Boxes dla roślin
function add_plant_meta_boxes() {
    add_meta_box(
        'plant_details',
        'Szczegóły rośliny',
        'plant_details_callback',
        'plant',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_plant_meta_boxes');

function plant_details_callback($post) {
    wp_nonce_field('plant_details_nonce', 'plant_details_nonce');
    
    $latin_name = get_post_meta($post->ID, '_plant_latin_name', true);
    $common_name = get_post_meta($post->ID, '_plant_common_name', true);
    $plant_code = get_post_meta($post->ID, '_plant_code', true);
    $status = get_post_meta($post->ID, '_plant_status', true);
    $acquisition_date = get_post_meta($post->ID, '_plant_acquisition_date', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="plant_latin_name">Nazwa łacińska:</label></th>
            <td><input type="text" id="plant_latin_name" name="plant_latin_name" value="<?php echo esc_attr($latin_name); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="plant_common_name">Nazwa zwyczajowa:</label></th>
            <td><input type="text" id="plant_common_name" name="plant_common_name" value="<?php echo esc_attr($common_name); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="plant_code">Kod rośliny:</label></th>
            <td><input type="text" id="plant_code" name="plant_code" value="<?php echo esc_attr($plant_code); ?>" class="regular-text" placeholder="np. R001, ALO-23"></td>
        </tr>
        <tr>
            <th><label for="plant_status">Status:</label></th>
            <td>
                <select id="plant_status" name="plant_status">
                    <option value="">-- Wybierz status --</option>
                    <option value="own" <?php selected($status, 'own'); ?>>Posiadam</option>
                    <option value="lost" <?php selected($status, 'lost'); ?>>Już nie mam</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="plant_acquisition_date">Data nabycia:</label></th>
            <td><input type="date" id="plant_acquisition_date" name="plant_acquisition_date" value="<?php echo esc_attr($acquisition_date); ?>"></td>
        </tr>
    </table>
    <?php
}

// Zapisywanie Meta Box
function save_plant_details($post_id) {
    if (!isset($_POST['plant_details_nonce']) || !wp_verify_nonce($_POST['plant_details_nonce'], 'plant_details_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['plant_latin_name'])) {
        update_post_meta($post_id, '_plant_latin_name', sanitize_text_field($_POST['plant_latin_name']));
    }
    
    if (isset($_POST['plant_common_name'])) {
        update_post_meta($post_id, '_plant_common_name', sanitize_text_field($_POST['plant_common_name']));
    }
    
    if (isset($_POST['plant_code'])) {
        update_post_meta($post_id, '_plant_code', sanitize_text_field($_POST['plant_code']));
    }
    
    if (isset($_POST['plant_status'])) {
        update_post_meta($post_id, '_plant_status', sanitize_text_field($_POST['plant_status']));
    }
    
    if (isset($_POST['plant_acquisition_date'])) {
        update_post_meta($post_id, '_plant_acquisition_date', sanitize_text_field($_POST['plant_acquisition_date']));
    }
}
add_action('save_post_plant', 'save_plant_details');

// WAŻNE: Wyświetlanie roślin TYLKO w wyszukiwaniu (nie na home, nie w kategoriach)
function add_plants_to_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Rośliny tylko w wyszukiwaniu
        if ($query->is_search()) {
            $query->set('post_type', array('post', 'plant', 'plant-relation'));
        }
    }
    return $query;
}
add_action('pre_get_posts', 'add_plants_to_main_query');

// Rozszerz wyszukiwanie o meta fields roślin (nazwy łacińskie, kod itp.)
function extend_search_to_plant_meta($search, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $search_term = $query->get('s');
        
        if (!empty($search_term)) {
            $search .= " OR (";
            $search .= "({$wpdb->postmeta}.meta_key = '_plant_latin_name' AND {$wpdb->postmeta}.meta_value LIKE '%" . esc_sql($wpdb->esc_like($search_term)) . "%')";
            $search .= " OR ({$wpdb->postmeta}.meta_key = '_plant_common_name' AND {$wpdb->postmeta}.meta_value LIKE '%" . esc_sql($wpdb->esc_like($search_term)) . "%')";
            $search .= " OR ({$wpdb->postmeta}.meta_key = '_plant_code' AND {$wpdb->postmeta}.meta_value LIKE '%" . esc_sql($wpdb->esc_like($search_term)) . "%')";
            $search .= ")";
        }
    }
    
    return $search;
}
add_filter('posts_search', 'extend_search_to_plant_meta', 10, 2);

function join_postmeta_for_search($join, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $join .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id";
    }
    
    return $join;
}
add_filter('posts_join', 'join_postmeta_for_search', 10, 2);

function group_by_post_id($groupby, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $groupby = "{$wpdb->posts}.ID";
    }
    
    return $groupby;
}
add_filter('posts_groupby', 'group_by_post_id', 10, 2);