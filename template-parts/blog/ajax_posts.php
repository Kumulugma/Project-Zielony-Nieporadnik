<?php
$args = [];
$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
$args['post_status'] = 'publish';
$postsPerPage = get_option('posts_per_page');
$postOffset = ($args['paged'] * $postsPerPage) - $postsPerPage;
$args['post_type'] = array('post', 'plant-relation'); // BEZ 'plant'

$args['posts_per_page'] = $postsPerPage;
$args['offset'] = $postOffset;
$args['orderby'] = 'date';
$args['ignore_sticky_posts'] = 1;

$posts = new WP_Query($args);
?>
<?php if ($posts->have_posts()) { ?>

    <?php foreach ($posts->posts as $current_post) { ?>

        <?php 
        // Sprawdź typ postu
        if ($current_post->post_type === 'plant-relation') {
            // Użyj template relacji
            set_query_var('data', $current_post);
            get_template_part('template-parts/blog/relation_content');
        } else {
            // Standardowy post
        ?>
        <div class="col-6 blog-post text-center mb-4">
            <div class="blog-post-image">
                <a href="<?= get_permalink($current_post) ?>">
                    <?php if (has_post_thumbnail($current_post->ID)): ?>
                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($current_post->ID), 'news'); ?>
                        <img class="img-fluid" src="<?php echo $image[0]; ?>" alt="<?php the_title() ?>">
                    <?php endif; ?>
                </a>
            </div>
            <div class="blog-content">
                <?php $post_categories = get_the_category($current_post); ?>
                <?php if (is_array($post_categories)) { ?>
                    <?php foreach ($post_categories as $category) { ?>
                        <a class="badge" href="<?= get_category_link($category) ?>"> <?= $category->name ?> </a>                        
                    <?php } ?>
                <?php } ?>

                <div class="blog-post-title">
                    <h5 class="mb-0"><a href="<?= get_permalink($current_post) ?>"><?= get_the_title($current_post->ID) ?></a></h5>
                </div>
                <div class="blog-post-footer blog-post-categorise">
                    <!-- USUNIĘTO AUTORA -->
                    <div class="blog-post-time">
                        <a href="<?= get_permalink($current_post) ?>"><i class="far fa-clock"></i><?= get_the_date('',$current_post->ID) ?></a>
                    </div>
                </div>
                <div class="blog-post-divider">
                </div>
                <?= get_the_excerpt($current_post->ID) ?> 
            </div>
        </div>
        <?php } // endif ?>
    <?php } ?>

<?php } ?>