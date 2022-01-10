<div class="col-lg-8">
    <div class="row">
        <div class="col-lg-12">

            <div class="blog-post">
                <div class="blog-content pb-0">
                    <div class="blog-post-title">
                        <h5 class="mb-0"><a href="<?= get_permalink(get_the_ID()) ?>"><?= get_the_title(); ?></a></h5>
                    </div>
                    <div class="blog-post-footer blog-post-categorise justify-content-start">
                        <?php $author = get_the_author_meta('display_name', $post->post_author); ?>


                        <div class="blog-post-author">
                            <span><img src="<?= get_avatar_url($post->post_author) ?>" title="<?= $author ?>" alt="<?= $author ?>"> <?= $author ?></span>
                        </div>
                        <div class="blog-post-time">
                            <a href="<?= get_permalink(get_the_ID()) ?>"><i class="far fa-clock"></i><?= get_the_date() ?></a>
                        </div>
                    </div>
                    <div class="blog-post-image">
                        <?php if (has_post_thumbnail(get_the_ID())): ?>
                            <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'nieporadnik'); ?>
                            <img class="img-fluid" src="<?php echo $image[0]; ?>" alt="<?php the_title() ?>">
                        <?php endif; ?>
                    </div>
                    <div class="blog-post-divider">
                    </div>

                    <?= get_the_content() ?>
                </div>
            </div>
        </div>
    </div>
</div>
