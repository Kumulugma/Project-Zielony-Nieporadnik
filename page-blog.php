<?php /* Template Name: Blog */ ?>
<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<section class="space-ptb bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-4">Blog</h1>
                
                <div class="row" id="blog-container">
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    
                    $blog_query = new WP_Query(array(
                        'post_type' => array('post', 'plant-relation'), // BEZ 'plant'
                        'posts_per_page' => get_option('posts_per_page'),
                        'paged' => $paged,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                    
                    if ($blog_query->have_posts()) :
                        while ($blog_query->have_posts()) : $blog_query->the_post();
                            $current_post = get_post();
                    ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <?php 
                            if ($current_post->post_type === 'plant-relation') {
                                get_template_part('template-parts/blog/relation_content', null, array('data' => $current_post));
                            } else {
                                get_template_part('template-parts/blog/post_content', null, array('data' => $current_post));
                            }
                            ?>
                        </div>
                    <?php
                        endwhile;
                    ?>
                        <div class="col-12 mt-4">
                            <?php
                            echo paginate_links(array(
                                'total' => $blog_query->max_num_pages,
                                'current' => $paged,
                                'prev_text' => __('« Poprzednie'),
                                'next_text' => __('Następne »'),
                                'mid_size' => 2,
                            ));
                            ?>
                        </div>
                    <?php
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="col-12">
                            <p>Brak wpisów blogowych.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/footer'); ?> 
<?php get_template_part('template-parts/to-top'); ?> 
<?php get_footer(); ?>