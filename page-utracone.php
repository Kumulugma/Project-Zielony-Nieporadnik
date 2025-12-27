<?php /* Template Name: Utracone rośliny */ ?>
<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<section class="space-ptb bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <header class="text-center mb-5">
                    <h1 class="mb-3">Rośliny których już nie mam</h1>
                    <p class="lead text-muted">
                        Historia roślin które odeszły - sprzedane, oddane lub te które nie przetrwały
                    </p>
                </header>

                <?php
                // Pobierz wszystkie grupy roślin
                $plant_groups = get_terms(array(
                    'taxonomy' => 'plant-group',
                    'hide_empty' => false,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ));

                $has_lost_plants = false;

                if ($plant_groups && !is_wp_error($plant_groups)) :
                    foreach ($plant_groups as $group) :
                        // Pobierz TYLKO utracone rośliny z tej grupy
                        $lost_plants = new WP_Query(array(
                            'post_type' => 'plant',
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'plant-group',
                                    'field' => 'term_id',
                                    'terms' => $group->term_id,
                                ),
                            ),
                            'meta_query' => array(
                                array(
                                    'key' => '_plant_status',
                                    'value' => 'lost',
                                    'compare' => '='
                                )
                            )
                        ));

                        // Pokaż grupę tylko jeśli ma utracone rośliny
                        if ($lost_plants->have_posts()) :
                            $has_lost_plants = true;
                ?>
                    <div class="group-section bg-white p-4 mb-4 rounded shadow-sm">
                        <div class="group-header mb-4 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-2 text-muted">
                                        <i class="fas fa-leaf me-2"></i><?php echo $group->name; ?>
                                    </h2>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary fs-6">
                                        <?php echo $lost_plants->found_posts; ?> 
                                        <?php echo $lost_plants->found_posts == 1 ? 'roślina' : 'roślin'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="plants-list">
                            <ul class="list-unstyled mb-0">
                                <?php 
                                while ($lost_plants->have_posts()) : 
                                    $lost_plants->the_post();
                                    $latin_name = get_post_meta(get_the_ID(), '_plant_latin_name', true);
                                    $acquisition_date = get_post_meta(get_the_ID(), '_plant_acquisition_date', true);
                                ?>
                                    <li class="plant-item py-3 border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h5 class="mb-1">
                                                    <i class="fas fa-times-circle text-secondary me-2"></i>
                                                    <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h5>
                                                <?php if ($latin_name): ?>
                                                    <p class="text-muted mb-0">
                                                        <em><?php echo esc_html($latin_name); ?></em>
                                                    </p>
                                                <?php endif; ?>
                                                <?php if ($acquisition_date): ?>
                                                    <small class="text-muted">
                                                        <i class="far fa-calendar me-1"></i>
                                                        Dodana: <?php echo date_i18n('d.m.Y', strtotime($acquisition_date)); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-secondary">
                                                    Zobacz historię <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                        
                        <?php wp_reset_postdata(); ?>
                    </div>
                <?php 
                        endif; // end if has posts
                    endforeach;
                endif;

                // Jeśli nie ma żadnych utraconych roślin
                if (!$has_lost_plants) :
                ?>
                    <div class="alert alert-success text-center">
                        <i class="fas fa-smile fa-3x mb-3" style="color: #719367;"></i>
                        <h4>Świetnie!</h4>
                        <p class="mb-0">Nie ma żadnych utraconych roślin. Wszystkie dobrze się trzymają! 🌱</p>
                    </div>
                <?php endif; ?>

                <!-- Podsumowanie -->
                <?php if ($has_lost_plants) : ?>
                    <div class="summary-box bg-white p-4 mt-5 rounded shadow-sm text-center">
                        <?php
                        $total_plants = wp_count_posts('plant')->publish;
                        $all_lost = new WP_Query(array(
                            'post_type' => 'plant',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => '_plant_status',
                                    'value' => 'lost',
                                    'compare' => '='
                                )
                            ),
                            'fields' => 'ids'
                        ));
                        $owned = new WP_Query(array(
                            'post_type' => 'plant',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => '_plant_status',
                                    'value' => 'own',
                                    'compare' => '='
                                )
                            ),
                            'fields' => 'ids'
                        ));
                        ?>
                        <h4 class="mb-4">Statystyka</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="h2 mb-1 text-secondary"><?php echo $all_lost->found_posts; ?></div>
                                <small class="text-muted text-uppercase">Utraconych</small>
                            </div>
                            <div class="col-md-4">
                                <div class="h2 mb-1" style="color: #719367;"><?php echo $owned->found_posts; ?></div>
                                <small class="text-muted text-uppercase">Aktualnie posiadam</small>
                            </div>
                            <div class="col-md-4">
                                <div class="h2 mb-1"><?php echo $total_plants; ?></div>
                                <small class="text-muted text-uppercase">Łącznie w bazie</small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/footer'); ?> 
<?php get_template_part('template-parts/to-top'); ?> 
<?php get_footer(); ?>