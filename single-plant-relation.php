<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<?php
while (have_posts()) : the_post();
    $related_plant_id = get_post_meta(get_the_ID(), '_related_plant_id', true);
    $related_plant = $related_plant_id ? get_post($related_plant_id) : null;
?>

<section class="space-ptb">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="blog-post">
                    
                    <!-- Breadcrumb do rośliny -->
                    <?php if ($related_plant): ?>
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo get_permalink($related_plant->ID); ?>">
                                        🌱 <?php echo esc_html($related_plant->post_title); ?>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Relacja</li>
                            </ol>
                        </nav>
                    <?php endif; ?>
                    
                    <?php if (has_post_thumbnail()): ?>
                        <div class="blog-post-image mb-4">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="blog-post-content">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="mb-0"><?php the_title(); ?></h1>
                            <span class="badge bg-secondary">📝 Relacja</span>
                        </div>
                        
                        <div class="blog-post-meta mb-4 pb-3 border-bottom">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i><?php echo get_the_date(); ?>
                            </small>
                        </div>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Link powrotny do rośliny -->
                        <?php if ($related_plant): ?>
                            <div class="back-to-plant mt-5 pt-4 border-top">
                                <a href="<?php echo get_permalink($related_plant->ID); ?>" class="btn btn-outline-secondary">
                                    ← Wróć do rośliny: <?php echo esc_html($related_plant->post_title); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>

                    <?php
                    // Nawigacja poprzednia/następna relacja tej samej rośliny
                    if ($related_plant_id):
                        // Pobierz poprzednią i następną relację tej samej rośliny
                        $prev_relation = get_posts(array(
                            'post_type' => 'plant-relation',
                            'posts_per_page' => 1,
                            'date_query' => array(
                                array(
                                    'before' => get_the_date('Y-m-d H:i:s'),
                                )
                            ),
                            'meta_query' => array(
                                array(
                                    'key' => '_related_plant_id',
                                    'value' => $related_plant_id,
                                )
                            )
                        ));
                        
                        $next_relation = get_posts(array(
                            'post_type' => 'plant-relation',
                            'posts_per_page' => 1,
                            'date_query' => array(
                                array(
                                    'after' => get_the_date('Y-m-d H:i:s'),
                                )
                            ),
                            'meta_query' => array(
                                array(
                                    'key' => '_related_plant_id',
                                    'value' => $related_plant_id,
                                )
                            ),
                            'order' => 'ASC'
                        ));
                        
                        if (!empty($prev_relation) || !empty($next_relation)):
                    ?>
                        <div class="post-navigation d-flex justify-content-between mt-5 pt-4 border-top">
                            <?php if (!empty($prev_relation)): ?>
                                <a href="<?php echo get_permalink($prev_relation[0]); ?>" class="btn btn-outline-secondary">
                                    ← <?php echo get_the_title($prev_relation[0]); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($next_relation)): ?>
                                <a href="<?php echo get_permalink($next_relation[0]); ?>" class="btn btn-outline-secondary ms-auto">
                                    <?php echo get_the_title($next_relation[0]); ?> →
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php 
                        endif;
                    endif; 
                    ?>

                </article>
            </div>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>