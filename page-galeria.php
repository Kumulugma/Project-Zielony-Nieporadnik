<?php /* Template Name: Galeria */ ?>
<?php get_header(); ?>
<?php get_template_part('template-parts/header'); ?>
<?php get_template_part('template-parts/search'); ?>

<section class="space-ptb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <header class="text-center mb-5">
                    <h1 class="mb-3">Galeria roślin</h1>
                    <p class="lead text-muted">Fotografie moich roślin - kliknij aby powiększyć</p>
                </header>
                
                <!-- Wyszukiwarka -->
                <div class="search-box mb-4 p-4 bg-white rounded shadow-sm">
                    <form method="get" action="">
                        <div class="row align-items-end">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="search-plant" class="form-label mb-2"><strong>🔍 Szukaj rośliny</strong></label>
                                <input type="text" id="search-plant" name="search" class="form-control" 
                                       placeholder="Wpisz nazwę rośliny, nazwę łacińską lub kod..."
                                       value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">Szukaj</button>
                                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                                    <a href="?" class="btn btn-outline-secondary w-100 mt-2">Wyczyść</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                // Pobierz rośliny
                $args = array(
                    'post_type' => 'plant',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC'
                );
                
                // Wyszukiwanie
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search_term = sanitize_text_field($_GET['search']);
                    $args['s'] = $search_term;
                    $args['meta_query'] = array(
                        'relation' => 'OR',
                        array('key' => '_plant_latin_name', 'value' => $search_term, 'compare' => 'LIKE'),
                        array('key' => '_plant_common_name', 'value' => $search_term, 'compare' => 'LIKE'),
                        array('key' => '_plant_code', 'value' => $search_term, 'compare' => 'LIKE')
                    );
                }
                
                $plants_query = new WP_Query($args);
                
                // Zbierz dane galerii
                $galleries_data = array();
                $plants_with_images = array();
                
                if ($plants_query->have_posts()) {
                    while ($plants_query->have_posts()) {
                        $plants_query->the_post();
                        $plant_id = get_the_ID();
                        
                        $all_images = array();
                        
                        // Zdjęcie główne
                        if (has_post_thumbnail()) {
                            $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                            $all_images[] = array(
                                'url' => $img[0],
                                'title' => get_the_title(),
                                'date' => 'Zdjęcie główne'
                            );
                        }
                        
                        // Zdjęcia z relacji
                        if (function_exists('get_plant_relation_images')) {
                            $rel_images = get_plant_relation_images($plant_id);
                            foreach ($rel_images as $img) {
                                $all_images[] = $img;
                            }
                        }
                        
                        // Tylko rośliny ze zdjęciami
                        if (!empty($all_images)) {
                            $gallery_id = 'gallery-' . $plant_id;
                            $galleries_data[$gallery_id] = $all_images;
                            $plants_with_images[] = $plant_id;
                        }
                    }
                    wp_reset_postdata();
                }
                
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    echo '<div class="alert alert-info mb-4">Znaleziono <strong>' . count($plants_with_images) . '</strong> roślin ze zdjęciami</div>';
                } else {
                    echo '<p class="text-muted mb-4">Wyświetlam <strong>' . count($plants_with_images) . '</strong> roślin ze zdjęciami</p>';
                }
                ?>
                
                <!-- Filtry -->
                <div class="text-center mb-4">
                    <button class="btn btn-sm btn-primary filter-btn active" data-filter="all">Wszystkie</button>
                    <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="own">Posiadam</button>
                    <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="lost">Już nie mam</button>
                </div>

                <?php if (!empty($plants_with_images)): ?>
                    <!-- Galeria -->
                    <div class="row gallery-grid" id="gallery">
                        <?php
                        $plants_query->rewind_posts();
                        while ($plants_query->have_posts()) {
                            $plants_query->the_post();
                            $plant_id = get_the_ID();
                            
                            // Pomiń rośliny bez zdjęć
                            if (!in_array($plant_id, $plants_with_images)) continue;
                            
                            $latin_name = get_post_meta($plant_id, '_plant_latin_name', true);
                            $status = get_post_meta($plant_id, '_plant_status', true);
                            $groups = get_the_terms($plant_id, 'plant-group');
                            
                            $filter_classes = array($status);
                            if ($groups && !is_wp_error($groups)) {
                                foreach ($groups as $group) {
                                    $filter_classes[] = $group->slug;
                                }
                            }
                            
                            $gallery_id = 'gallery-' . $plant_id;
                            $all_images = $galleries_data[$gallery_id];
                            $first_image = $all_images[0]['url'];
                        ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 gallery-item <?php echo implode(' ', $filter_classes); ?>">
                                <div class="gallery-card h-100">
                                    <a href="#" class="gallery-link lightbox-gallery" 
                                       data-gallery-id="<?php echo $gallery_id; ?>" data-index="0">
                                        <div class="gallery-image">
                                            <img src="<?php echo esc_url($first_image); ?>" 
                                                 alt="<?php the_title_attribute(); ?>" 
                                                 class="img-fluid w-100">
                                            
                                            <div class="gallery-overlay">
                                                <i class="fas fa-search-plus fa-2x"></i>
                                                <?php if (count($all_images) > 1): ?>
                                                    <div class="mt-2" style="font-size: 0.9rem;">
                                                        <?php echo count($all_images); ?> zdjęć
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="gallery-badge">
                                                <span class="badge <?php echo $status === 'own' ? 'bg-success' : 'bg-secondary'; ?>" 
                                                      style="font-size: 0.75rem; padding: 2px 6px;">
                                                    <?php echo $status === 'own' ? '✓' : '✗'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                    
                                    <div class="gallery-info p-3 bg-white">
                                        <h6 class="mb-1">
                                            <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                                <?php the_title(); ?>
                                            </a>
                                        </h6>
                                        <?php if ($latin_name): ?>
                                            <p class="small text-muted mb-0"><em><?php echo esc_html($latin_name); ?></em></p>
                                        <?php endif; ?>
                                        <?php if ($groups && !is_wp_error($groups)): ?>
                                            <div class="mt-2">
                                                <?php foreach ($groups as $group): ?>
                                                    <span class="badge bg-light text-dark small"><?php echo $group->name; ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } wp_reset_postdata(); ?>
                    </div>
                    
                    <!-- JavaScript data -->
                    <script>
                    window.plantGalleries = <?php echo json_encode($galleries_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <p class="mb-0">Nie znaleziono żadnych zdjęć.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="lightbox-modal">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
    <div class="lightbox-caption">
        <div id="lightbox-caption-text"></div>
        <div id="lightbox-counter" class="mt-2"></div>
    </div>
    <button class="lightbox-prev">&#10094;</button>
    <button class="lightbox-next">&#10095;</button>
</div>

<style>
.search-box { border: 1px solid #e0e0e0; }
.gallery-item.hidden, .gallery-item.search-hidden { display: none; }
.filter-btn { margin: 0 5px 10px 5px; transition: all 0.3s; }
.gallery-card { position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s; }
.gallery-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
.gallery-image { position: relative; overflow: hidden; background: #f5f5f5; }
.gallery-image img { width: 100%; height: 250px; object-fit: cover; }
.gallery-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; opacity: 0; transition: opacity 0.3s; }
.gallery-card:hover .gallery-overlay { opacity: 1; }
.gallery-badge { position: absolute; top: 10px; right: 10px; }

.lightbox-modal { display: none; position: fixed; z-index: 9999; padding-top: 50px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.95); }
.lightbox-content { margin: auto; display: block; max-width: 90%; max-height: 80vh; object-fit: contain; }
.lightbox-close { position: absolute; top: 15px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer; }
.lightbox-close:hover { color: #bbb; }
.lightbox-caption { text-align: center; color: #ccc; padding: 20px; font-size: 18px; }
.lightbox-prev, .lightbox-next { cursor: pointer; position: absolute; top: 50%; width: auto; padding: 16px; margin-top: -50px; color: white; font-weight: bold; font-size: 30px; transition: 0.3s; user-select: none; background: rgba(0,0,0,0.5); border: none; border-radius: 5px; }
.lightbox-prev { left: 20px; } .lightbox-next { right: 20px; }
.lightbox-prev:hover, .lightbox-next:hover { background-color: rgba(0,0,0,0.8); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentGallery = [];
    let currentIndex = 0;
    
    const modal = document.getElementById('lightbox-modal');
    const modalImg = document.getElementById('lightbox-img');
    const captionText = document.getElementById('lightbox-caption-text');
    const counterText = document.getElementById('lightbox-counter');
    const lightboxLinks = document.querySelectorAll('.lightbox-gallery');
    const closeBtn = document.querySelector('.lightbox-close');
    const prevBtn = document.querySelector('.lightbox-prev');
    const nextBtn = document.querySelector('.lightbox-next');
    
    // Filtry
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active', 'btn-primary'));
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.add('btn-outline-secondary'));
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-outline-secondary');
            
            const filter = this.getAttribute('data-filter');
            document.querySelectorAll('.gallery-item').forEach(item => {
                if (filter === 'all' || item.classList.contains(filter)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });
    
    // Lightbox
    function showImage(index) {
        if (!currentGallery[index]) return;
        
        currentIndex = index;
        modalImg.src = currentGallery[index].url;
        captionText.innerHTML = currentGallery[index].title + '<br><small>' + currentGallery[index].date + '</small>';
        counterText.textContent = (index + 1) + ' / ' + currentGallery.length;
        
        prevBtn.style.display = currentGallery.length > 1 ? 'block' : 'none';
        nextBtn.style.display = currentGallery.length > 1 ? 'block' : 'none';
    }
    
    lightboxLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const galleryId = this.getAttribute('data-gallery-id');
            currentGallery = window.plantGalleries[galleryId] || [];
            if (currentGallery.length > 0) {
                modal.style.display = 'block';
                showImage(0);
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    prevBtn.addEventListener('click', () => showImage((currentIndex - 1 + currentGallery.length) % currentGallery.length));
    nextBtn.addEventListener('click', () => showImage((currentIndex + 1) % currentGallery.length));
    closeBtn.addEventListener('click', () => { modal.style.display = 'none'; document.body.style.overflow = ''; });
    modal.addEventListener('click', (e) => { if (e.target === modal) { modal.style.display = 'none'; document.body.style.overflow = ''; } });
    
    document.addEventListener('keydown', (e) => {
        if (modal.style.display === 'block') {
            if (e.key === 'Escape') { modal.style.display = 'none'; document.body.style.overflow = ''; }
            if (e.key === 'ArrowLeft') prevBtn.click();
            if (e.key === 'ArrowRight') nextBtn.click();
        }
    });
});
</script>

<?php get_template_part('template-parts/footer'); ?>
<?php get_template_part('template-parts/to-top'); ?>
<?php get_footer(); ?>