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
            <th><label for="plant_status">Status:</label></th>
            <td>
                <select id="plant_status" name="plant_status">
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
    
    if (isset($_POST['plant_status'])) {
        update_post_meta($post_id, '_plant_status', sanitize_text_field($_POST['plant_status']));
    }
    
    if (isset($_POST['plant_acquisition_date'])) {
        update_post_meta($post_id, '_plant_acquisition_date', sanitize_text_field($_POST['plant_acquisition_date']));
    }
}
add_action('save_post_plant', 'save_plant_details');

// WAŻNE: Wyświetlanie roślin razem z postami na stronie głównej i w archiwach
function add_plants_to_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Strona główna
        if ($query->is_home()) {
            $query->set('post_type', array('post', 'plant'));
        }
        // Archiwa kategorii (jeśli używasz wspólnych kategorii)
        if ($query->is_category()) {
            $query->set('post_type', array('post', 'plant'));
        }
        // Archiwa tagów
        if ($query->is_tag()) {
            $query->set('post_type', array('post', 'plant'));
        }
        // Wyszukiwanie
        if ($query->is_search()) {
            $query->set('post_type', array('post', 'plant'));
        }
    }
    return $query;
}
add_action('pre_get_posts', 'add_plants_to_main_query');

// Rozszerz wyszukiwanie o meta fields roślin (nazwy łacińskie itp.)
function extend_search_to_plant_meta($search, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $search_term = $query->get('s');
        
        if (!empty($search_term)) {
            $search .= " OR (";
            $search .= "({$wpdb->postmeta}.meta_key = '_plant_latin_name' AND {$wpdb->postmeta}.meta_value LIKE '%" . esc_sql($wpdb->esc_like($search_term)) . "%')";
            $search .= " OR ({$wpdb->postmeta}.meta_key = '_plant_common_name' AND {$wpdb->postmeta}.meta_value LIKE '%" . esc_sql($wpdb->esc_like($search_term)) . "%')";
            $search .= ")";
        }
    }
    
    return $search;
}
add_filter('posts_search', 'extend_search_to_plant_meta', 10, 2);

// Join meta table dla wyszukiwania
function join_postmeta_for_search($join, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $join .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id";
    }
    
    return $join;
}
add_filter('posts_join', 'join_postmeta_for_search', 10, 2);

// Usuń duplikaty z wyników wyszukiwania
function distinct_search_results($distinct, $query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        return "DISTINCT";
    }
    
    return $distinct;
}
add_filter('posts_distinct', 'distinct_search_results', 10, 2);
// ====== TIMELINE - HISTORIA ROŚLINY ======

// Dodaj pole Timeline
function add_plant_timeline_box() {
    add_meta_box(
        'plant_timeline',
        '📅 Timeline - Historia rośliny',
        'plant_timeline_callback',
        'plant',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_plant_timeline_box');

function plant_timeline_callback($post) {
    wp_nonce_field('plant_timeline_nonce', 'plant_timeline_nonce');
    
    $timeline_entries = get_post_meta($post->ID, '_plant_timeline', true);
    if (!is_array($timeline_entries)) {
        $timeline_entries = array();
    }
    
    // Sortuj od najnowszych
    if (!empty($timeline_entries)) {
        usort($timeline_entries, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
    }
    
    ?>
    <div style="margin-bottom: 20px; padding: 15px; background: #f0f8ff; border-left: 4px solid #719367;">
        <p style="margin: 0;"><strong>Jak dodać zdjęcia?</strong></p>
        <ol style="margin: 10px 0 0 0; padding-left: 20px;">
            <li>Wgraj zdjęcia do <strong>Biblioteki mediów</strong></li>
            <li>Kliknij na zdjęcie i skopiuj jego <strong>ID</strong> (liczba w pasku adresu: post=<strong>123</strong>)</li>
            <li>Wpisz ID zdjęć oddzielone przecinkami: <code>123, 456, 789</code></li>
        </ol>
    </div>
    
    <div id="timeline-entries">
        <?php 
        if (!empty($timeline_entries)):
            foreach ($timeline_entries as $index => $entry): 
        ?>
            <div class="timeline-entry" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; background: #f9f9f9; border-radius: 5px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <strong style="color: #719367;">Wpis #<?php echo ($index + 1); ?></strong>
                    <button type="button" class="button remove-timeline" style="background: #dc3545; color: white; border: none;">
                        <span class="dashicons dashicons-trash" style="margin-top: 3px;"></span> Usuń
                    </button>
                </div>
                
                <p style="margin-bottom: 10px;">
                    <label><strong>Data:</strong></label><br>
                    <input type="date" name="timeline_date[]" value="<?php echo esc_attr($entry['date']); ?>" style="width: 100%; padding: 5px;">
                </p>
                <p style="margin-bottom: 10px;">
                    <label><strong>Notatka / Obserwacja:</strong></label><br>
                    <textarea name="timeline_note[]" rows="4" style="width: 100%; padding: 5px;"><?php echo esc_textarea($entry['note']); ?></textarea>
                </p>
                <p style="margin-bottom: 0;">
                    <label><strong>Zdjęcia (ID oddzielone przecinkami):</strong></label><br>
                    <input type="text" name="timeline_images[]" value="<?php echo esc_attr($entry['images']); ?>" placeholder="np. 123, 456, 789" style="width: 100%; padding: 5px;">
                    
                    <?php if (!empty($entry['images'])): 
                        $image_ids = array_map('trim', explode(',', $entry['images']));
                    ?>
                        <div style="margin-top: 10px; display: flex; gap: 10px; flex-wrap: wrap;">
                            <?php foreach ($image_ids as $img_id): 
                                if (is_numeric($img_id) && $img_id > 0):
                                    $img = wp_get_attachment_image($img_id, 'thumbnail');
                                    if ($img):
                            ?>
                                <div style="border: 2px solid #ddd; padding: 3px; background: white;">
                                    <?php echo $img; ?>
                                </div>
                            <?php 
                                    endif;
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>
                </p>
            </div>
        <?php 
            endforeach;
        else:
        ?>
            <p style="color: #666; font-style: italic;">Brak wpisów. Kliknij przycisk poniżej aby dodać pierwszy wpis do timeline.</p>
        <?php endif; ?>
    </div>
    
    <button type="button" id="add-timeline-entry" class="button button-primary" style="margin-top: 10px;">
        <span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span> Dodaj nowy wpis do timeline
    </button>
    
    <script>
    jQuery(document).ready(function($) {
        var entryCount = <?php echo count($timeline_entries); ?>;
        
        $('#add-timeline-entry').click(function() {
            entryCount++;
            var html = '<div class="timeline-entry" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; background: #f9f9f9; border-radius: 5px;">' +
                '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">' +
                '<strong style="color: #719367;">Nowy wpis #' + entryCount + '</strong>' +
                '<button type="button" class="button remove-timeline" style="background: #dc3545; color: white; border: none;">' +
                '<span class="dashicons dashicons-trash" style="margin-top: 3px;"></span> Usuń</button>' +
                '</div>' +
                '<p style="margin-bottom: 10px;"><label><strong>Data:</strong></label><br>' +
                '<input type="date" name="timeline_date[]" style="width: 100%; padding: 5px;"></p>' +
                '<p style="margin-bottom: 10px;"><label><strong>Notatka / Obserwacja:</strong></label><br>' +
                '<textarea name="timeline_note[]" rows="4" style="width: 100%; padding: 5px;"></textarea></p>' +
                '<p style="margin-bottom: 0;"><label><strong>Zdjęcia (ID oddzielone przecinkami):</strong></label><br>' +
                '<input type="text" name="timeline_images[]" placeholder="np. 123, 456, 789" style="width: 100%; padding: 5px;"></p>' +
                '</div>';
            $('#timeline-entries').append(html);
        });
        
        $(document).on('click', '.remove-timeline', function() {
            if (confirm('Czy na pewno chcesz usunąć ten wpis z timeline?')) {
                $(this).closest('.timeline-entry').fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
    });
    </script>
    
    <style>
    .timeline-entry:hover {
        background: #f0f0f0 !important;
    }
    </style>
    <?php
}

function save_plant_timeline($post_id) {
    if (!isset($_POST['plant_timeline_nonce']) || !wp_verify_nonce($_POST['plant_timeline_nonce'], 'plant_timeline_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $timeline_entries = array();
    
    if (isset($_POST['timeline_date']) && is_array($_POST['timeline_date'])) {
        foreach ($_POST['timeline_date'] as $index => $date) {
            if (!empty($date)) {
                $timeline_entries[] = array(
                    'date' => sanitize_text_field($date),
                    'note' => sanitize_textarea_field($_POST['timeline_note'][$index]),
                    'images' => sanitize_text_field($_POST['timeline_images'][$index])
                );
            }
        }
    }
    
    update_post_meta($post_id, '_plant_timeline', $timeline_entries);
}
add_action('save_post_plant', 'save_plant_timeline');