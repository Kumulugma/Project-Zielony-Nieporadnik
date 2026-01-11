<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<?php
while (have_posts()) : the_post();
    $latin_name = get_post_meta(get_the_ID(), '_plant_latin_name', true);
    $common_name = get_post_meta(get_the_ID(), '_plant_common_name', true);
    $plant_code = get_post_meta(get_the_ID(), '_plant_code', true);
    $status = get_post_meta(get_the_ID(), '_plant_status', true);
    $acquisition_date = get_post_meta(get_the_ID(), '_plant_acquisition_date', true);
?>

<section class="space-ptb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <article class="blog-post">
                    
                    <?php if (has_post_thumbnail()): ?>
                        <div class="blog-post-image mb-4">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="blog-post-content">
                        <h1 class="mb-3">
                            <?php the_title(); ?>
                            <?php if ($plant_code): ?>
                                <small class="text-muted ms-2">(<?php echo esc_html($plant_code); ?>)</small>
                            <?php endif; ?>
                        </h1>
                        
                        <div class="plant-meta bg-light p-4 mb-4">
                            <?php if ($plant_code): ?>
                                <p class="mb-2"><strong>Kod rośliny:</strong> <?php echo esc_html($plant_code); ?></p>
                            <?php endif; ?>
                            
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
                                    <span class="badge <?php echo $status === 'own' ? 'badge-success' : 'badge-secondary'; ?>" style="font-size: 0.85rem; padding: 4px 10px; vertical-align: middle;">
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
                        // RELACJE - Historia i obserwacje (nowy system)
                        // WAŻNE: Sprawdź czy funkcja istnieje (czy plik cpt-plant-relations.php jest załadowany)
                        if (function_exists('get_plant_relations')):
                            $relations = get_plant_relations(get_the_ID());
                            if ($relations->have_posts()):
                        ?>
                            <div class="plant-relations mt-5 pt-4 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="mb-0">
                                        <i class="fas fa-history me-2" style="color: #719367;"></i>
                                        Historia i obserwacje
                                    </h4>
                                    <?php if (current_user_can('edit_posts')): ?>
                                        <a href="<?php echo admin_url('post-new.php?post_type=plant-relation&plant_id=' . get_the_ID()); ?>" class="btn btn-sm btn-outline-secondary">
                                            📝 Dodaj relację
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <?php while ($relations->have_posts()): $relations->the_post(); ?>
                                    <div class="relation-entry mb-4 p-4 bg-light rounded">
                                        <div class="row">
                                            <?php if (has_post_thumbnail()): ?>
                                                <div class="col-md-3 mb-3 mb-md-0">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid rounded')); ?>
                                                    </a>
                                                </div>
                                                <div class="col-md-9">
                                            <?php else: ?>
                                                <div class="col-12">
                                            <?php endif; ?>
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0">
                                                        <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted"><?php echo get_the_date(); ?></small>
                                                </div>
                                                <div class="relation-excerpt">
                                                    <?php the_excerpt(); ?>
                                                </div>
                                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-link p-0">
                                                    Czytaj więcej →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            </div>
                        <?php 
                            endif; // if have_posts
                        else: 
                            // Funkcja nie istnieje - plik nie został załadowany
                            if (current_user_can('manage_options')):
                        ?>
                            <div class="alert alert-warning mt-5">
                                <strong>⚠️ System relacji nie został załadowany</strong><br>
                                Dodaj do <code>functions.php</code>:<br>
                                <code>require_once get_template_directory() . '/includes/cpt-plant-relations.php';</code>
                            </div>
                        <?php 
                            endif;
                        endif; // if function_exists
                        ?>

                    </div>

                    <?php
                    // Nawigacja następny/poprzedni post
                    $next_post = get_next_post();
                    $prev_post = get_previous_post();
                    if ($next_post || $prev_post):
                    ?>
                        <div class="post-navigation d-flex justify-content-between mt-5 pt-4 border-top">
                            <?php if ($prev_post): ?>
                                <a href="<?php echo get_permalink($prev_post); ?>" class="btn btn-outline-secondary">
                                    ← <?php echo get_the_title($prev_post); ?>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($next_post): ?>
                                <a href="<?php echo get_permalink($next_post); ?>" class="btn btn-outline-secondary ms-auto">
                                    <?php echo get_the_title($next_post); ?> →
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </article>
            </div>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>