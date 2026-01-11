<?php
/**
 * Template part for displaying plant-relation posts in blog loop
 */
$related_plant_id = get_post_meta($data->ID, '_related_plant_id', true);
$related_plant = $related_plant_id ? get_post($related_plant_id) : null;
?>
<div class="blog-post text-center mb-4 plant-relation-post">
    <div class="blog-post-image">
        <a href="<?= get_permalink($data->ID) ?>">
            <?php if (has_post_thumbnail($data->ID)): ?>
                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($data->ID), 'news'); ?>
                <img class="lazyload img-fluid" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title($data->ID); ?>">
            <?php endif; ?>
        </a>
        <!-- Badge oznaczający relację -->
        <div class="post-type-badge" style="position: absolute; top: 10px; left: 10px; background: #719367; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.75rem; font-weight: 600;">
            📝 Relacja
        </div>
    </div>
    <div class="blog-content">
        <?php if ($related_plant): ?>
            <!-- Link do rośliny -->
            <div class="mb-2">
                <a href="<?= get_permalink($related_plant->ID) ?>" class="badge bg-light text-dark" style="text-decoration: none;">
                    🌱 <?= esc_html($related_plant->post_title) ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="blog-post-title">
            <h5 class="mb-0"><a href="<?= get_permalink($data->ID) ?>"><?= get_the_title($data->ID) ?></a></h5>
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