<?php
$args = array(
    'post_type' => 'post'
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

$paged = (get_query_var('page')) ? get_query_var('page') : 1;

$postsPerPage = get_option('posts_per_page');
$postOffset = ($paged * $postsPerPage);

$args['posts_per_page'] = $postsPerPage;
$args['offset'] = $postOffset;

$posts = new WP_Query($args);
?>

<?php if ($posts->have_posts()) { ?>
    <?php $all_posts = $posts; ?>

    <div class="col-lg-6 mb-4 mb-lg-0">
        <?php foreach ($all_posts->posts as $k => $current_post1) { ?>

                <div class="blog-post text-center mb-4">
                    <div class="blog-post-image">
                        <?php if (has_post_thumbnail($current_post1->ID)): ?>
                            <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($current_post1->ID), 'news'); ?>
                            <img class="img-fluid" src="<?php echo $image[0]; ?>" alt="<?php the_title() ?>">
                        <?php endif; ?>
                    </div>
                    <div class="blog-content">
                        <?php $post_categories = get_the_category($current_post1); ?>
                        <?php if (is_array($post_categories)) { ?>
                            <?php foreach ($post_categories as $category) { ?>
                                <a class="badge" href="<?= get_category_link($category) ?>"> <?= $category->name ?> </a>                        
                            <?php } ?>
                        <?php } ?>

                        <div class="blog-post-title">
                            <h5 class="mb-0"><a href="<?= get_permalink($current_post1) ?>"><?= get_the_title() ?></a></h5>
                        </div>
                        <div class="blog-post-footer blog-post-categorise">
                            <?php $author = get_the_author_meta('display_name', $current_post1->post_author); ?>


                            <div class="blog-post-author">
                                <span><img src="<?= get_avatar_url($current_post1->post_author) ?>" title="<?= $author ?>" alt="<?= $author ?>"> <?= $author ?></span>
                            </div>
                            <div class="blog-post-time">
                                <a href="<?= get_permalink($current_post1) ?>"><i class="far fa-clock"></i><?= get_the_date() ?></a>
                            </div>
                        </div>
                        <div class="blog-post-divider">
                        </div>
                        <?php the_excerpt() ?> 
                    </div>
                </div>
        <?php } ?>
    </div>

<?php } ?>
