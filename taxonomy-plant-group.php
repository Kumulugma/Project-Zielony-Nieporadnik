<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<section class="space-ptb">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <?php
                // Pobierz miniaturkę z najnowszego wpisu
                $current_term = get_queried_object();
                $group_thumbnail = get_plant_group_thumbnail($current_term->term_id, 'large');
                ?>

                <?php if ($group_thumbnail): ?>
                    <div class="plant-group-header mb-4">
                        <img src="<?php echo esc_url($group_thumbnail); ?>" alt="<?php single_term_title(); ?>" class="img-fluid" style="max-height: 300px; width: 100%; object-fit: cover; border-radius: 8px;">
                    </div>
                <?php endif; ?>

                <h1>Grupa: <?php single_term_title(); ?></h1>
                <?php
                $term_description = term_description();
                if (!empty($term_description)) :
                    echo '<div class="term-description">' . $term_description . '</div>';
                endif;
                ?>
            </div>
        </div>

        <?php if (have_posts()) : ?>
            <div class="row">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <?php get_template_part('template-parts/blog/plant_content', null, array('data' => $post)); ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); ?>

        <?php else : ?>
            <div class="row">
                <div class="col-12">
                    <p>Brak roślin w tej grupie.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_template_part('template-parts/footer'); ?> 
<?php get_template_part('template-parts/to-top'); ?> 
<?php get_footer(); ?>