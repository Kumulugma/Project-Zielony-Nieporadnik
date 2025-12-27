<section class="space-pb bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bg-white p-4">
                    <h6 class="widget-title text-uppercase fw-bolder">Przeglądaj rośliny po grupach</h6>
                    <div class="blog-sidebar-post-divider mb-4"></div>
                    
                    <div class="row">
                        <?php
                        $plant_groups = get_terms(array(
                            'taxonomy' => 'plant-group',
                            'hide_empty' => true,
                        ));
                        
                        foreach ($plant_groups as $group):
                            $group_thumbnail = get_plant_group_thumbnail($group->term_id, 'medium');
                        ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <a href="<?php echo get_term_link($group); ?>" class="group-card text-decoration-none d-block">
                                    <div class="card h-100 border-0 shadow-sm hover-lift">
                                        <?php if ($group_thumbnail): ?>
                                            <img src="<?php echo esc_url($group_thumbnail); ?>" class="card-img-top" alt="<?php echo $group->name; ?>" style="height: 150px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <i class="fas fa-leaf fa-3x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body text-center">
                                            <h6 class="mb-1"><?php echo $group->name; ?></h6>
                                            <small class="text-muted"><?php echo $group->count; ?> roślin</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hover-lift {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
}
</style>