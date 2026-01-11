<?php /* Template Name: Grupy roślin */ ?>
<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<section class="space-ptb bg-light">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <header class="text-center mb-5">
                    <h1 class="mb-3">Katalog roślin według grup</h1>
                    <p class="lead text-muted">
                        Pełna lista moich roślin uporządkowana według gatunków i grup
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

                if ($plant_groups && !is_wp_error($plant_groups)) :
                    foreach ($plant_groups as $group) :
                        // Pobierz rośliny z tej grupy
                        $plants_in_group = new WP_Query(array(
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
                        ));
                ?>
                    <div class="group-section bg-white p-4 mb-4 rounded shadow-sm">
                        <div class="group-header mb-4 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-2" style="color: #719367;">
                                        <i class="fas fa-leaf me-2"></i><?php echo $group->name; ?>
                                    </h2>
                                    <?php if ($group->description): ?>
                                        <p class="text-muted mb-0"><?php echo $group->description; ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-light text-dark fs-6">
                                        <?php echo $plants_in_group->found_posts; ?> 
                                        <?php echo $plants_in_group->found_posts == 1 ? 'roślina' : 'roślin'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if ($plants_in_group->have_posts()) : ?>
                            <div class="plants-list">
                                <ul class="list-unstyled mb-0">
                                    <?php 
                                    while ($plants_in_group->have_posts()) : 
                                        $plants_in_group->the_post();
                                        $latin_name = get_post_meta(get_the_ID(), '_plant_latin_name', true);
                                        $plant_code = get_post_meta(get_the_ID(), '_plant_code', true);
                                        $status = get_post_meta(get_the_ID(), '_plant_status', true);
                                    ?>
                                        <li class="plant-item py-3 border-bottom">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h5 class="mb-1">
                                                        <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                                            <?php the_title(); ?>
                                                        </a>
                                                        <?php if ($plant_code): ?>
                                                            <small class="text-muted ms-2">(<?php echo esc_html($plant_code); ?>)</small>
                                                        <?php endif; ?>
                                                        <?php if ($status === 'own'): ?>
                                                            <span class="badge bg-success ms-2" style="font-size: 0.75rem; padding: 2px 6px;">✓</span>
                                                        <?php elseif ($status === 'lost'): ?>
                                                            <span class="badge bg-secondary ms-2" style="font-size: 0.75rem; padding: 2px 6px;">✗</span>
                                                        <?php endif; ?>
                                                    </h5>
                                                    <?php if ($latin_name): ?>
                                                        <p class="text-muted small mb-0"><em><?php echo esc_html($latin_name); ?></em></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-secondary">
                                                        Zobacz szczegóły
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                    <?php wp_reset_postdata(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php 
                    endforeach;
                else:
                ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Nie znaleziono żadnych grup roślin.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/footer'); ?> 
<?php get_template_part('template-parts/to-top'); ?> 
<?php get_footer(); ?>