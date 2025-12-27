<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<?php
while (have_posts()) : the_post();
    $latin_name = get_post_meta(get_the_ID(), '_plant_latin_name', true);
    $common_name = get_post_meta(get_the_ID(), '_plant_common_name', true);
    $status = get_post_meta(get_the_ID(), '_plant_status', true);
    $acquisition_date = get_post_meta(get_the_ID(), '_plant_acquisition_date', true);
?>

<section class="space-ptb">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="blog-post">
                    
                    <?php if (has_post_thumbnail()): ?>
                        <div class="blog-post-image mb-4">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="blog-post-content">
                        <h1 class="mb-3"><?php the_title(); ?></h1>
                        
                        <div class="plant-meta bg-light p-4 mb-4">
                            <?php if ($latin_name): ?>
                                <p class="mb-2"><strong>Nazwa łacińska:</strong> <em><?php echo esc_html($latin_name); ?></em></p>
                            <?php endif; ?>
                            
                            <?php if ($common_name): ?>
                                <p class="mb-2"><strong>Nazwa zwyczajowa:</strong> <?php echo esc_html($common_name); ?></p>
                            <?php endif; ?>
                            
                            <?php
                            $groups = get_the_terms(get_the_ID(), 'plant-group');
                            if ($groups && !is_wp_error($groups)):
                            ?>
                                <p class="mb-2"><strong>Grupa:</strong> 
                                    <?php
                                    $group_names = array();
                                    foreach ($groups as $group) {
                                        $group_names[] = '<a href="' . get_term_link($group) . '">' . $group->name . '</a>';
                                    }
                                    echo implode(', ', $group_names);
                                    ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($status): ?>
                                <p class="mb-2"><strong>Status:</strong> 
                                    <span class="badge <?php echo $status === 'own' ? 'badge-success' : 'badge-secondary'; ?>">
                                        <?php echo $status === 'own' ? '✓ Posiadam' : '✗ Już nie mam'; ?>
                                    </span>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($acquisition_date): ?>
                                <p class="mb-0"><strong>Data nabycia:</strong> <?php echo date_i18n('d.m.Y', strtotime($acquisition_date)); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <?php
                        // Kategorie i tagi
                        $categories_list = get_the_category_list(', ');
                        $tags_list = get_the_tag_list('', ', ');
                        ?>
                        
                        <?php if ($categories_list || $tags_list): ?>
                            <div class="blog-post-footer mt-4 pt-4 border-top">
                                <?php if ($categories_list): ?>
                                    <div class="mb-2"><strong>Kategorie:</strong> <?php echo $categories_list; ?></div>
                                <?php endif; ?>
                                <?php if ($tags_list): ?>
                                    <div><strong>Tagi:</strong> <?php echo $tags_list; ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
            
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_template_part('template-parts/footer'); ?> 
<?php get_template_part('template-parts/to-top'); ?> 
<?php get_footer(); ?>