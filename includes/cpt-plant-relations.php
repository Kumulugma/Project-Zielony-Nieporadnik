<?php
/**
 * Custom Post Type: Relacje (Plant Updates/Journal)
 * Zastępuje stary system timeline
 */

// Rejestracja CPT Relacje
function create_plant_relation_post_type() {
    register_post_type('plant-relation',
        array(
            'labels' => array(
                'name' => __('Relacje'),
                'singular_name' => __('Relacja'),
                'add_new' => __('Dodaj relację'),
                'add_new_item' => __('Dodaj nową relację'),
                'edit_item' => __('Edytuj relację'),
                'new_item' => __('Nowa relacja'),
                'view_item' => __('Zobacz relację'),
                'search_items' => __('Szukaj relacji'),
                'not_found' => __('Nie znaleziono relacji'),
                'not_found_in_trash' => __('Brak relacji w koszu'),
                'menu_name' => __('Relacje'),
                'featured_image' => __('Zdjęcie relacji'),
                'set_featured_image' => __('Ustaw zdjęcie relacji'),
                'remove_featured_image' => __('Usuń zdjęcie'),
                'use_featured_image' => __('Użyj jako zdjęcie relacji')
            ),
            'public' => true,
            'has_archive' => false,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-edit',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
            'rewrite' => array('slug' => 'relacja'),
            'hierarchical' => false,
        )
    );
    
    // Upewnij się że thumbnails są włączone dla relacji
    add_post_type_support('plant-relation', 'thumbnail');
}
add_action('init', 'create_plant_relation_post_type');

// Odśwież permalinki przy aktywacji (tylko raz)
function plant_relations_rewrite_flush() {
    // Sprawdź czy już flush był wykonany
    if (get_option('plant_relations_flush_rewrite_rules') !== 'done') {
        create_plant_relation_post_type();
        flush_rewrite_rules();
        update_option('plant_relations_flush_rewrite_rules', 'done');
    }
}
add_action('init', 'plant_relations_rewrite_flush');

// Meta Box dla przypisania rośliny
function add_plant_relation_meta_boxes() {
    add_meta_box(
        'plant_relation_plant',
        '🌱 Powiązanie z rośliną',
        'plant_relation_plant_callback',
        'plant-relation',
        'side',
        'high'
    );
    
    // Meta box dla rośliny - lista i szybkie dodawanie relacji
    add_meta_box(
        'plant_relations_list',
        '📝 Relacje / Historia',
        'plant_relations_list_callback',
        'plant',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_plant_relation_meta_boxes');

function plant_relation_plant_callback($post) {
    wp_nonce_field('plant_relation_nonce', 'plant_relation_nonce');
    
    $related_plant = get_post_meta($post->ID, '_related_plant_id', true);
    
    // Pobierz wszystkie rośliny do selecta
    $plants = get_posts(array(
        'post_type' => 'plant',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    
    ?>
    <p>
        <label for="related_plant_id"><strong>Wybierz roślinę:</strong></label>
        <select name="related_plant_id" id="related_plant_id" style="width: 100%; margin-top: 5px;">
            <option value="">-- Wybierz roślinę --</option>
            <?php foreach ($plants as $plant): ?>
                <option value="<?php echo $plant->ID; ?>" <?php selected($related_plant, $plant->ID); ?>>
                    <?php echo esc_html($plant->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p class="description">
        Relacja zostanie wyświetlona w historii wybranej rośliny oraz na liście postów bloga.
    </p>
    <?php
}

// Meta box dla rośliny - lista relacji i formularz
function plant_relations_list_callback($post) {
    wp_nonce_field('quick_relation_nonce', 'quick_relation_nonce');
    
    // Pobierz relacje tej rośliny
    $relations = get_plant_relations($post->ID);
    
    ?>
    <div class="plant-relations-manager">
        <style>
        .plant-relations-manager {
            padding: 10px 0;
        }
        .relation-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .relation-item:hover {
            background: #f0f0f0;
        }
        .relation-info {
            flex: 1;
        }
        .relation-info strong {
            color: #719367;
            display: block;
            margin-bottom: 5px;
        }
        .relation-info small {
            color: #666;
        }
        .relation-actions {
            display: flex;
            gap: 10px;
        }
        .quick-add-form {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 5px;
            border: 2px dashed #719367;
            margin-bottom: 20px;
        }
        .quick-add-form h4 {
            margin-top: 0;
            color: #719367;
        }
        .form-field {
            margin-bottom: 15px;
        }
        .form-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .form-field input[type="text"],
        .form-field input[type="date"],
        .form-field textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .form-field textarea {
            min-height: 100px;
            resize: vertical;
        }
        .no-relations {
            text-align: center;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 5px;
            color: #666;
        }
        .image-upload-wrapper {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        .upload-image-button {
            cursor: pointer;
        }
        .remove-image-button {
            background: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
        }
        #image-preview img {
            display: block;
        }
        </style>
        
        <!-- Formularz szybkiego dodawania -->
        <div class="quick-add-form">
            <h4>➕ Dodaj nową relację</h4>
            <div class="form-field">
                <label>Tytuł relacji *</label>
                <input type="text" name="quick_relation_title" placeholder="np. Przesadzenie, Podlanie, Pierwsza obserwacja..." required>
            </div>
            <div class="form-field">
                <label>Data</label>
                <input type="date" name="quick_relation_date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-field">
                <label>Treść / Opis</label>
                <textarea name="quick_relation_content" placeholder="Opisz co się wydarzyło, jakie zmiany zauważyłeś..."></textarea>
            </div>
            <div class="form-field">
                <label>Zdjęcie wyróżniające</label>
                <div class="image-upload-wrapper">
                    <input type="hidden" id="quick_relation_image" name="quick_relation_image" value="">
                    <button type="button" class="button upload-image-button" id="upload_image_button">
                        📷 Wybierz zdjęcie
                    </button>
                    <button type="button" class="button remove-image-button" id="remove_image_button" style="display:none;">
                        ✕ Usuń zdjęcie
                    </button>
                    <div id="image-preview" style="margin-top: 10px;"></div>
                </div>
                <p class="description" style="margin-top: 5px;">
                    Zdjęcie pojawi się w historii rośliny i w galerii lightbox.
                </p>
            </div>
            <p class="description">
                Po zapisaniu rośliny, nowa relacja zostanie utworzona i automatycznie przypisana do tej rośliny.
            </p>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            var mediaUploader;
            
            $('#upload_image_button').click(function(e) {
                e.preventDefault();
                
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                
                mediaUploader = wp.media({
                    title: 'Wybierz zdjęcie do relacji',
                    button: {
                        text: 'Wybierz to zdjęcie'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#quick_relation_image').val(attachment.id);
                    $('#image-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">');
                    $('#remove_image_button').show();
                });
                
                mediaUploader.open();
            });
            
            $('#remove_image_button').click(function(e) {
                e.preventDefault();
                $('#quick_relation_image').val('');
                $('#image-preview').html('');
                $(this).hide();
            });
        });
        </script>
        
        <!-- Lista istniejących relacji -->
        <h4 style="margin-bottom: 15px;">📋 Istniejące relacje (<?php echo $relations->found_posts; ?>)</h4>
        
        <?php if ($relations->have_posts()): ?>
            <?php while ($relations->have_posts()): $relations->the_post(); ?>
                <div class="relation-item">
                    <div class="relation-info">
                        <strong><?php the_title(); ?></strong>
                        <small>
                            <?php echo get_the_date(); ?>
                            <?php if (has_post_thumbnail()): ?>
                                📷
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="relation-actions">
                        <a href="<?php echo get_edit_post_link(); ?>" class="button button-small">
                            Edytuj
                        </a>
                        <a href="<?php the_permalink(); ?>" class="button button-small" target="_blank">
                            Zobacz
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="no-relations">
                <p><strong>Brak relacji</strong></p>
                <p>Dodaj pierwszą relację używając formularza powyżej</p>
            </div>
        <?php endif; ?>
        
        <p style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <a href="<?php echo admin_url('post-new.php?post_type=plant-relation&plant_id=' . $post->ID); ?>" class="button button-primary">
                📝 Dodaj pełną relację (z edytorem i zdjęciami)
            </a>
        </p>
    </div>
    <?php
}

// Zapisywanie powiązania
function save_plant_relation_meta($post_id) {
    if (!isset($_POST['plant_relation_nonce']) || !wp_verify_nonce($_POST['plant_relation_nonce'], 'plant_relation_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['related_plant_id'])) {
        update_post_meta($post_id, '_related_plant_id', intval($_POST['related_plant_id']));
    }
}
add_action('save_post_plant-relation', 'save_plant_relation_meta');

// Zapisywanie szybkiej relacji z formularza w edycji rośliny
function save_quick_plant_relation($post_id) {
    // Sprawdź czy to roślina
    if (get_post_type($post_id) !== 'plant') {
        return;
    }
    
    if (!isset($_POST['quick_relation_nonce']) || !wp_verify_nonce($_POST['quick_relation_nonce'], 'quick_relation_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Sprawdź czy formularz został wypełniony
    if (!empty($_POST['quick_relation_title'])) {
        $title = sanitize_text_field($_POST['quick_relation_title']);
        $date = !empty($_POST['quick_relation_date']) ? sanitize_text_field($_POST['quick_relation_date']) : date('Y-m-d');
        $content = sanitize_textarea_field($_POST['quick_relation_content']);
        $image_id = !empty($_POST['quick_relation_image']) ? intval($_POST['quick_relation_image']) : 0;
        
        // Utwórz nową relację
        $relation_data = array(
            'post_type' => 'plant-relation',
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_date' => $date . ' ' . current_time('H:i:s'),
        );
        
        $relation_id = wp_insert_post($relation_data);
        
        if ($relation_id && !is_wp_error($relation_id)) {
            // Przypisz do rośliny
            update_post_meta($relation_id, '_related_plant_id', $post_id);
            
            // Ustaw obrazek wyróżniający jeśli został wybrany
            if ($image_id > 0) {
                set_post_thumbnail($relation_id, $image_id);
            }
            
            // Dodaj notyfikację admin
            add_settings_error(
                'plant_relations',
                'relation_created',
                'Relacja "' . $title . '" została utworzona' . ($image_id ? ' ze zdjęciem' : '') . '!',
                'success'
            );
        }
    }
}
add_action('save_post', 'save_quick_plant_relation');

// Automatyczne przypisanie do rośliny z której dodano relację
// Dodaj przycisk "Dodaj relację" na stronie edycji rośliny
function add_quick_relation_button() {
    global $post;
    
    if ($post && $post->post_type === 'plant') {
        $add_url = admin_url('post-new.php?post_type=plant-relation&plant_id=' . $post->ID);
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.wrap h1').after('<a href="<?php echo esc_url($add_url); ?>" class="page-title-action">📝 Dodaj relację do tej rośliny</a>');
        });
        </script>
        <?php
    }
}
add_action('admin_footer', 'add_quick_relation_button');

// Auto-przypisanie rośliny z parametru URL
function auto_assign_plant_to_relation() {
    global $post;
    
    if ($post && $post->post_type === 'plant-relation' && isset($_GET['plant_id'])) {
        $plant_id = intval($_GET['plant_id']);
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('#related_plant_id').val('<?php echo $plant_id; ?>');
        });
        </script>
        <?php
    }
}
add_action('admin_footer', 'auto_assign_plant_to_relation');

// Dodaj relacje do głównego query (TYLKO kategorie/tagi, NIE strona główna)
function add_relations_to_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // WAŻNE: NIE dodawaj na stronie głównej (home) - tam są tylko posty
        // Relacje pojawiają się w: kategoriach, tagach, wyszukiwaniu
        if ($query->is_category() || $query->is_tag() || $query->is_search()) {
            $post_types = $query->get('post_type');
            if (!$post_types) {
                $post_types = array('post');
            }
            if (is_string($post_types)) {
                $post_types = array($post_types);
            }
            if (!in_array('plant-relation', $post_types)) {
                $post_types[] = 'plant-relation';
            }
            $query->set('post_type', $post_types);
        }
    }
    return $query;
}
add_action('pre_get_posts', 'add_relations_to_main_query');

// Funkcja pomocnicza: Pobierz relacje dla danej rośliny
function get_plant_relations($plant_id, $limit = -1) {
    $args = array(
        'post_type' => 'plant-relation',
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => '_related_plant_id',
                'value' => $plant_id,
                'compare' => '='
            )
        )
    );
    
    return new WP_Query($args);
}

// Funkcja pomocnicza: Pobierz wszystkie zdjęcia z relacji dla lightboxa
function get_plant_relation_images($plant_id) {
    $relations = get_plant_relations($plant_id);
    $images = array();
    
    if ($relations->have_posts()) {
        while ($relations->have_posts()) {
            $relations->the_post();
            
            // Zdjęcie wyróżniające
            if (has_post_thumbnail()) {
                $images[] = array(
                    'id' => get_post_thumbnail_id(),
                    'url' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    'title' => get_the_title(),
                    'date' => get_the_date()
                );
            }
            
            // Zdjęcia z galerii (jeśli dodane w treści)
            $content = get_the_content();
            preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches);
            
            if (!empty($matches[1])) {
                foreach ($matches[1] as $img_url) {
                    $images[] = array(
                        'url' => $img_url,
                        'title' => get_the_title(),
                        'date' => get_the_date()
                    );
                }
            }
        }
        wp_reset_postdata();
    }
    
    return $images;
}

// Dodaj kolumnę "Roślina" w liście relacji w admin
function add_plant_column_to_relations($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['related_plant'] = '🌱 Roślina';
        }
    }
    return $new_columns;
}
add_filter('manage_plant-relation_posts_columns', 'add_plant_column_to_relations');

function display_plant_column_content($column, $post_id) {
    if ($column === 'related_plant') {
        $plant_id = get_post_meta($post_id, '_related_plant_id', true);
        if ($plant_id) {
            $plant = get_post($plant_id);
            if ($plant) {
                echo '<a href="' . get_edit_post_link($plant_id) . '">' . esc_html($plant->post_title) . '</a>';
            } else {
                echo '<span style="color: #999;">Nie przypisano</span>';
            }
        } else {
            echo '<span style="color: #999;">Nie przypisano</span>';
        }
    }
}
add_action('manage_plant-relation_posts_custom_column', 'display_plant_column_content', 10, 2);

// Możliwość filtrowania relacji po roślinie w admin
function add_plant_filter_to_relations() {
    global $typenow;
    
    if ($typenow === 'plant-relation') {
        $plants = get_posts(array(
            'post_type' => 'plant',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        
        $selected = isset($_GET['plant_filter']) ? $_GET['plant_filter'] : '';
        
        echo '<select name="plant_filter">';
        echo '<option value="">Wszystkie rośliny</option>';
        foreach ($plants as $plant) {
            printf(
                '<option value="%s"%s>%s</option>',
                $plant->ID,
                selected($selected, $plant->ID, false),
                esc_html($plant->post_title)
            );
        }
        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'add_plant_filter_to_relations');

function filter_relations_by_plant($query) {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'plant-relation' && isset($_GET['plant_filter']) && $_GET['plant_filter'] !== '') {
        $query->set('meta_key', '_related_plant_id');
        $query->set('meta_value', $_GET['plant_filter']);
    }
}
add_filter('parse_query', 'filter_relations_by_plant');