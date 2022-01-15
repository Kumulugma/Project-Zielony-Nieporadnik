<?php
$stickies = get_option('sticky_posts');
rsort( $stickies );
$args = array(
    'post_type' => 'post',
    'post__in' => $stickies, 
    'ignore_sticky_posts' => 1
);
                        
$posts = new WP_Query($args);
?>
<section class="space-pb bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="advertise bg-overlay-black-50 bg-holder text-center h-100 align-items-center" style="background-image: url(<?= get_template_directory_uri() ?>/assets/images/about/05.jpg);">
                    <a href="https://carni24.pl"><img class="img-fluid" src="<?= get_template_directory_uri() ?>/assets/images/ads/ad.png" alt="Reklama"></a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="bg-white p-4">
                    <h6 class="widget-title text-uppercase fw-bolder">Polecane wpisy</h6>
                    <div class="blog-sidebar-post-divider mb-4">
                    </div>
                    <div class="owl-carousel blog-arrow" data-nav-arrow="true" data-nav-dots="false" data-items="2" data-md-items="2" data-sm-items="2" data-xs-items="1" data-xx-items="1" data-space="15">
                        <?php
                        if ($posts->have_posts()) {
                            ?>

                            <?php
                            while ($posts->have_posts()) {
                                $posts->the_post();
                                ?>
                                <div class="item">
                                    <div class="blog-post text-center p-0">
                                        <div class="blog-post-image">
                                            <?php if (has_post_thumbnail(get_the_ID())): ?>
                                                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'sticky'); ?>
                                                <img class="img-fluid mx-auto" src="<?php echo $image[0]; ?>" alt="<?php the_title() ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="blog-content">
                                            <?php $post_categories = get_the_category($current_post); ?>
                                            <?php if (is_array($post_categories)) { ?>
                                                <?php foreach ($post_categories as $category) { ?>
                                                    <a class="badge" href="<?= get_category_link($category) ?>"> <?= $category->name ?> </a>                        
                                                <?php } ?>
                                            <?php } ?>
                                            <div class="blog-post-title">
                                                <h6 class="mb-0"><a href="<?= get_permalink(get_the_ID()) ?>"><?= get_the_title() ?></a></h6>
                                            </div>
                                            <div class="blog-post-footer blog-post-categorise">
                                                <div class="blog-post-time">
                                                    <a href="<?= get_permalink(get_the_ID()) ?>"><i class="far fa-clock"></i><?= get_the_date() ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
