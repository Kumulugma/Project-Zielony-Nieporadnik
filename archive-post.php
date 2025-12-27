<?php
/**
 * Template Name: Archiwum Bloga
 */
get_header();
get_template_part('template-parts/header');
get_template_part('template-parts/search');
?>

<section class="space-ptb bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-4">Blog</h1>
                
                <div class="row" id="blog-container">
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    
                    $blog_query = new WP_Query(array(
                        'post_type' => 'post',
                        'posts_per_page' => get_option('posts_per_page'),
                        'paged' => $paged,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                    
                    if ($blog_query->have_posts()) :
                        while ($blog_query->have_posts()) : $blog_query->the_post();
                    ?>
                        <div class="col-md-4 mb-4">
                            <?php get_template_part('template-parts/blog/post_content', null, array('data' => $post)); ?>
                        </div>
                    <?php
                        endwhile;
                    ?>
                        <div class="col-12">
                            <div class="pagination">
                                <?php
                                echo paginate_links(array(
                                    'total' => $blog_query->max_num_pages,
                                    'current' => $paged,
                                    'prev_text' => __('&laquo; Poprzednie'),
                                    'next_text' => __('Następne &raquo;'),
                                    'type' => 'list',
                                ));
                                ?>
                            </div>
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

<?php
get_template_part('template-parts/footer');
get_template_part('template-parts/to-top');
get_footer();
?>