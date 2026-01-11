<?php
$args = array(
    'post_type' => array('post', 'plant-relation'), // BEZ 'plant' - rośliny tylko w dedykowanych miejscach
    'orderby' => 'date',
    'ignore_sticky_posts' => 1,
);

$category = get_category(get_query_var('cat'));
if (isset($category->cat_ID)) {
    $catID = $category->cat_ID;
    $args['cat'] = $catID;
}

$tag = get_query_var('tag');
if (!empty($tag)) {
    $args['tag'] = $tag;
}

$s = get_query_var('s');
if (!empty($s)) {
    $args['s'] = $s;
}

$paged = (get_query_var('page')) ? get_query_var('page') : 0;

$postsPerPage = get_option('posts_per_page');
$postOffset = ($paged * $postsPerPage);

$args['posts_per_page'] = $postsPerPage;
$args['offset'] = $postOffset;

$blog = new WP_Query($args);
?>

<div class="col-lg-8">
    <div class="row" id="blog-container">
        <?php if ($blog->have_posts()) { ?>
            <div class="col-lg-12 mb-4 mb-lg-0">
                <div class="row" id="posts-loop">
<?php while ($blog->have_posts()) {
    $blog->the_post();
    $current_post = get_post();
    ?>
    <div class="col-6">
        <?php 
        // Relacje mają swój template
        if ($current_post->post_type === 'plant-relation') {
            get_template_part('template-parts/blog/relation_content', null, array('data' => $current_post));
        } else {
            // Standardowy post
        ?>
            <div class="blog-post text-center mb-4">
                <div class="blog-post-image">
                    <a href="<?= get_permalink(get_the_ID()) ?>">
                        <?php if (has_post_thumbnail(get_the_ID())): ?>
                            <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'news'); ?>
                            <img class="lazyload img-fluid" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $image[0]; ?>" alt="<?php the_title() ?>">
                        <?php endif; ?>
                    </a>
                </div>
                <div class="blog-content">
                    <?php $post_categories = get_the_category(get_the_ID()); ?>
                    <?php if (is_array($post_categories)) { ?>
                        <?php foreach ($post_categories as $category) { ?>
                            <a class="badge" href="<?= get_category_link($category) ?>"> <?= $category->name ?> </a>                        
                        <?php } ?>
                    <?php } ?>

                    <div class="blog-post-title">
                        <h5 class="mb-0"><a href="<?= get_permalink(get_the_ID()) ?>"><?= get_the_title() ?></a></h5>
                    </div>
                    <div class="blog-post-footer blog-post-categorise">
                        <!-- USUNIĘTO AUTORA -->
                        <div class="blog-post-time">
                            <a href="<?= get_permalink(get_the_ID()) ?>"><i class="far fa-clock"></i><?= get_the_date() ?></a>
                        </div>
                    </div>
                    <div class="blog-post-divider">
                    </div>
                    <?= get_the_excerpt(get_the_ID()->ID) ?> 
                </div>
            </div>
        <?php 
        } // koniec else
        ?>
    </div>
<?php } ?>

                </div>
            </div>
<?php } ?>

    </div>
    <div class="row">
<?php if ($blog->max_num_pages): ?>
            <div class="col-lg-12">
                <div class="d-grid mt-md-5 mt-4">
                    <div class="loadmore btn btn-primary btn-block" data-max="<?= $blog->max_num_pages ?>">Załaduj więcej</div>
                </div>
            </div>
<?php endif; ?>
    </div>

<?php wp_reset_postdata(); ?>
</div>