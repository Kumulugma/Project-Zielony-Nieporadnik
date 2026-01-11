<?php
$latin_name = get_post_meta($data->ID, '_plant_latin_name', true);
$status = get_post_meta($data->ID, '_plant_status', true);
$plant_code = get_post_meta($data->ID, '_plant_code', true);
?>
<div class="blog-post text-center mb-4">
    <div class="blog-post-image">
        <a href="<?= get_permalink($data->ID) ?>">
            <?php if (has_post_thumbnail($data->ID)): ?>
                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($data->ID), 'news'); ?>
                <img class="lazyload img-fluid" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title($data->ID); ?>">
            <?php endif; ?>
        </a>
        <?php if ($status): ?>
            <div class="plant-status-badge-small <?php echo $status === 'own' ? 'badge-success' : 'badge-secondary'; ?>">
                <?php echo $status === 'own' ? '✓' : '✗'; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="blog-content">
        <?php 
        // Grupy roślin
        $plant_groups = get_the_terms($data->ID, 'plant-group');
        if ($plant_groups && !is_wp_error($plant_groups)): 
        ?>
            <?php foreach ($plant_groups as $group): ?>
                <a class="badge" href="<?= get_term_link($group) ?>"> <?= $group->name ?> </a>                        
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="blog-post-title">
            <h5 class="mb-0">
                <a href="<?= get_permalink($data->ID) ?>"><?= get_the_title($data->ID) ?></a>
                <?php if ($plant_code): ?>
                    <small class="text-muted ms-2">(<?php echo esc_html($plant_code); ?>)</small>
                <?php endif; ?>
            </h5>
            <?php if ($latin_name): ?>
                <p class="plant-latin text-muted small mt-1 mb-0"><em><?php echo esc_html($latin_name); ?></em></p>
            <?php endif; ?>
        </div>
        
        <div class="blog-post-footer blog-post-categorise">
            <!-- USUNIĘTO AUTORA -->
            <div class="blog-post-time">
                <a href="<?= get_permalink($data->ID) ?>"><i class="far fa-clock"></i><?= get_the_date('', $data->ID) ?></a>
            </div>
        </div>
        <div class="blog-post-divider">
        </div>
        <?php echo get_the_excerpt($data->ID); ?> 
    </div>
</div>