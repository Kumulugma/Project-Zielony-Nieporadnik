<div class="col-lg-4  mt-5 mt-lg-0">
    <div class="blog-sidebar bg-white sidebar">
        <div class="widget">
            <?php
            $popularpost = new WP_Query(
                    array(
                'posts_per_page' => 8,
                'meta_key' => 'wpb_post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC')
            );
            ?>
            <h6 class="widget-title">Popularne wpisy</h6>
            <div class="blog-sidebar-post-divider">
            </div>
            <div class="widget-content mt-4">
                <?php while ($popularpost->have_posts()) : $popularpost->the_post(); ?>
                    <div class="d-flex mb-3 align-items-top">
                        <div class="ms-3">
                            <h6 class="text-dark"><a href="<?= get_permalink() ?>"> <?= get_the_title() ?> </a></h6>
                            <span class="small"><i class="far fa-clock text-primary me-1"></i><?= get_the_date() ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>


            </div>
        </div>

        <?php
        $categories = get_categories(array(
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        ?>

        <div class="widget">
            <h6 class="widget-title">Kategorie</h6>
            <div class="blog-sidebar-post-divider">
            </div>
            <div class="widget-content mt-4">
                <ul class="list-unstyled list-style mb-0">
                    <?php foreach ($categories as $category) { ?>
                        <li>
                            <div class="blog-post blog-overlay blog-post-05">
                                <div class="blog-image">
                                    
                                </div>
                                <div class="blog-name">
                                    <a href="<?= get_category_link($category->term_id) ?>"><?= $category->name ?> <span class="ms-auto">(<?= $category->count ?>)</span></a>
                                </div>
                            </div>
                        </li>
                    <?php } ?>

                </ul>
            </div>
        </div>
        <?php
        $tags = get_terms(array(
            'taxonomy' => 'post_tag',
            'orderby' => 'count',
            'order' => 'DESC',
        ));
        ?>

        <div class="widget mb-0">
            <h6 class="widget-title">Popularne Tagi</h6>
            <div class="blog-sidebar-post-divider">
            </div>
            <div class="widget-content mt-4">
                <div class="popular-tag">
                    <ul class="list-unstyled mb-0">

                        <?php foreach ($tags as $tag) { ?>
                            <li><a href="<?= get_tag_link($tag) ?>"><?= $tag->name ?></a></li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
