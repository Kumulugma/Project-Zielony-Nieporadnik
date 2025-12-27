<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<section class="space-ptb">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Moja kolekcja roślin</h1>
                
                <?php
                $current_status = isset($_GET['status']) ? $_GET['status'] : 'all';
                ?>
                <div class="plant-filters mt-3">
                    <a href="<?php echo get_post_type_archive_link('plant'); ?>" 
                       class="btn btn-sm <?php echo $current_status === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        Wszystkie
                    </a>
                    <a href="<?php echo add_query_arg('status', 'own', get_post_type_archive_link('plant')); ?>" 
                       class="btn btn-sm <?php echo $current_status === 'own' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        Posiadam
                    </a>
                    <a href="<?php echo add_query_arg('status', 'lost', get_post_type_archive_link('plant')); ?>" 
                       class="btn btn-sm <?php echo $current_status === 'lost' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        Już nie mam
                    </a>
                </div>
            </div>
        </div>

        <?php
        // Modyfikacja zapytania
        if ($current_status !== 'all') {
            global $wp_query;
            $wp_query = new WP_Query(array(
                'post_type' => 'plant',
                'posts_per_page' => 12,
                'paged' => get_query_var('paged'),
                'meta_query' => array(
                    array(
                        'key' => '_plant_status',
                        'value' => $current_status,
                        'compare' => '='
                    )
                )
            ));
        }
        
        if (have_posts()) :
        ?>
            <div class="row">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <?php get_template_part('template-parts/blog/plant_content', null, array('data' => $post)); ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
            // Paginacja
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Poprzednie'),
                'next_text' => __('Następne &raquo;'),
            ));
            ?>

        <?php else : ?>
            <div class="row">
                <div class="col-12">
                    <p>Nie znaleziono żadnych roślin.</p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<?php get_template_part('template-parts/footer'); ?> 
<?php get_template_part('template-parts/to-top'); ?> 
<?php get_footer(); ?>