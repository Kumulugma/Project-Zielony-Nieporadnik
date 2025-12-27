<?php /* Template Name: Galeria */ ?>
<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?> 
<?php get_template_part('template-parts/search'); ?> 

<section class="space-ptb bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <header class="text-center mb-5">
                    <h1 class="mb-3">Galeria roślin</h1>
                    <p class="lead text-muted">
                        Zdjęcia z mojej kolekcji
                    </p>
                </header>

                <?php
                // Pobierz wszystkie rośliny z miniaturkami
                $plants_query = new WP_Query(array(
                    'post_type' => 'plant',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => array(
                        array(
                            'key' => '_thumbnail_id',
                            'compare' => 'EXISTS'
                        )
                    )
                ));

                if ($plants_query->have_posts()) :
                ?>
                    <!-- Filtry -->
                    <div class="filters mb-4 text-center">
                        <button class="btn btn-sm btn-primary filter-btn active" data-filter="all">
                            Wszystkie (<?php echo $plants_query->found_posts; ?>)
                        </button>
                        <?php
                        $plant_groups = get_terms(array(
                            'taxonomy' => 'plant-group',
                            'hide_empty' => true,
                        ));
                        foreach ($plant_groups as $group):
                        ?>
                            <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="<?php echo $group->slug; ?>">
                                <?php echo $group->name; ?> (<?php echo $group->count; ?>)
                            </button>
                        <?php endforeach; ?>
                        
                        <button class="btn btn-sm btn-outline-success filter-btn" data-filter="own">
                            Posiadam
                        </button>
                        <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="lost">
                            Już nie mam
                        </button>
                    </div>

                    <!-- Galeria z Lightbox -->
                    <div class="row gallery-grid" id="gallery">
                        <?php 
                        while ($plants_query->have_posts()) : 
                            $plants_query->the_post();
                            $latin_name = get_post_meta(get_the_ID(), '_plant_latin_name', true);
                            $status = get_post_meta(get_the_ID(), '_plant_status', true);
                            $groups = get_the_terms(get_the_ID(), 'plant-group');
                            
                            // Zbierz wszystkie klasy filtrów
                            $filter_classes = array();
                            $filter_classes[] = $status; // own lub lost
                            if ($groups && !is_wp_error($groups)) {
                                foreach ($groups as $group) {
                                    $filter_classes[] = $group->slug;
                                }
                            }
                            $filter_class_string = implode(' ', $filter_classes);
                            
                            // Pełny rozmiar obrazka do lightboxa
                            $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 gallery-item <?php echo $filter_class_string; ?>">
                                <div class="gallery-card h-100">
                                    <!-- Link do powiększenia -->
                                    <a href="<?php echo $full_image[0]; ?>" class="gallery-link lightbox" data-title="<?php the_title(); ?><?php if ($latin_name): ?> - <em><?php echo esc_html($latin_name); ?></em><?php endif; ?>">
                                        <div class="gallery-image">
                                            <?php if (has_post_thumbnail()): ?>
                                                <?php the_post_thumbnail('medium', array('class' => 'img-fluid w-100')); ?>
                                            <?php endif; ?>
                                            
                                            <div class="gallery-overlay">
                                                <i class="fas fa-search-plus fa-2x"></i>
                                            </div>
                                            
                                            <div class="gallery-badge">
                                                <?php if ($status === 'own'): ?>
                                                    <span class="badge bg-success">✓</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">✗</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- Link do karty rośliny -->
                                    <div class="gallery-info p-3 bg-white">
                                        <h6 class="mb-1">
                                            <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"><?php the_title(); ?></a>
                                        </h6>
                                        <?php if ($latin_name): ?>
                                            <p class="small text-muted mb-0"><em><?php echo esc_html($latin_name); ?></em></p>
                                        <?php endif; ?>
                                        <?php if ($groups && !is_wp_error($groups)): ?>
                                            <div class="mt-2">
                                                <?php foreach ($groups as $group): ?>
                                                    <span class="badge bg-light text-dark small">
                                                        <?php echo $group->name; ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                <?php 
                    wp_reset_postdata();
                else :
                ?>
                    <div class="alert alert-info text-center">
                        <p class="mb-0">Nie znaleziono żadnych zdjęć.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="lightbox-modal" style="display: none;">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightbox-img" alt="">
    <div id="lightbox-caption"></div>
</div>

<!-- Style -->
<style>
/* Galeria */
.gallery-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}

.gallery-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.gallery-image {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.gallery-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.gallery-card:hover .gallery-image img {
    transform: scale(1.05);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(113, 147, 103, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    color: white;
}

.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

.gallery-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

.gallery-info h6 {
    color: #333;
    font-weight: 600;
}

.gallery-item {
    transition: opacity 0.3s, transform 0.3s;
}

.gallery-item.hidden {
    display: none;
}

.filter-btn {
    margin: 0 5px 10px 5px;
    transition: all 0.3s;
}

.filter-btn.active {
    background: #719367 !important;
    border-color: #719367 !important;
    color: white !important;
}

/* Lightbox */
.lightbox-modal {
    position: fixed;
    z-index: 9999;
    padding-top: 50px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.95);
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.lightbox-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 80vh;
    animation: zoomIn 0.3s;
}

@keyframes zoomIn {
    from { transform: scale(0.8); }
    to { transform: scale(1); }
}

.lightbox-close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
    z-index: 10000;
}

.lightbox-close:hover,
.lightbox-close:focus {
    color: #bbb;
}

#lightbox-caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 20px 0;
    font-size: 18px;
}

/* Responsywność */
@media (max-width: 768px) {
    .lightbox-content {
        max-width: 95%;
        max-height: 70vh;
    }
    
    .lightbox-close {
        top: 10px;
        right: 15px;
        font-size: 30px;
    }
    
    #lightbox-caption {
        font-size: 14px;
        padding: 10px 0;
    }
    
    .gallery-image {
        height: 200px;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtry
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Aktywny przycisk
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                if (!btn.classList.contains('filter-btn')) return;
                
                // Przywróć oryginalne klasy
                if (btn.getAttribute('data-filter') === 'all') {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                } else if (btn.getAttribute('data-filter') === 'own') {
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-success');
                } else if (btn.getAttribute('data-filter') === 'lost') {
                    btn.classList.remove('btn-secondary');
                    btn.classList.add('btn-outline-secondary');
                } else {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                }
            });
            
            this.classList.add('active');
            
            // Filtrowanie
            galleryItems.forEach(item => {
                if (filter === 'all') {
                    item.classList.remove('hidden');
                } else {
                    if (item.classList.contains(filter)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                }
            });
        });
    });
    
    // Lightbox
    const modal = document.getElementById('lightbox-modal');
    const modalImg = document.getElementById('lightbox-img');
    const captionText = document.getElementById('lightbox-caption');
    const lightboxLinks = document.querySelectorAll('.lightbox');
    const closeBtn = document.querySelector('.lightbox-close');
    
    lightboxLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'block';
            modalImg.src = this.href;
            captionText.innerHTML = this.getAttribute('data-title');
            document.body.style.overflow = 'hidden'; // Zapobiega scrollowaniu w tle
        });
    });
    
    // Zamykanie lightboxa
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    // Kliknięcie poza zdjęciem zamyka lightbox
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Klawisz ESC zamyka lightbox
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});
</script>

<?php get_template_part('template-parts/footer'); ?> 
<?php get_template_part('template-parts/to-top'); ?> 
<?php get_footer(); ?>