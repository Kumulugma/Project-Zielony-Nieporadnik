<div class="blog-post text-center mb-4">
    <div class="blog-post-image">
        <?php if (has_post_thumbnail($data->ID)): ?>
            <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($data->ID), 'nieporadnik'); ?>
            <img class="img-fluid" src="<?php echo $image[0]; ?>" alt="<?php the_title() ?>">
        <?php endif; ?>
    </div>
    <div class="blog-content">
        <?php $post_categories = get_the_category($data); ?>
        <?php if (is_array($post_categories)) { ?>
            <?php foreach ($post_categories as $category) { ?>
                <a class="badge" href="<?= get_category_link($category) ?>"> <?= $category->name ?> </a>                        
            <?php } ?>
        <?php } ?>

        <div class="blog-post-title">
            <h5 class="mb-0"><a href="<?= get_permalink($data) ?>"><?= get_the_title() ?></a></h5>
        </div>
        <div class="blog-post-footer blog-post-categorise">
            <?php $author = get_the_author_meta('display_name', $data->post_author); ?>


            <div class="blog-post-author">
                <span><img src="<?= get_avatar_url($data->post_author) ?>" title="<?= $author ?>" alt="<?= $author ?>"> <?= $author ?></span>
            </div>
            <div class="blog-post-time">
                <a href="<?= get_permalink($data) ?>"><i class="far fa-clock"></i><?= get_the_date() ?></a>
            </div>
        </div>
        <div class="blog-post-divider">
        </div>
        <?php the_excerpt() ?> 
    </div>
</div>
