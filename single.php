<?php get_header(); ?>
<?php wpb_set_post_views(get_the_ID()); ?>
<?php get_template_part( 'template-parts/header' ); ?> 
<?php get_template_part( 'template-parts/search' ); ?> 
<?php get_template_part( 'template-parts/single' ); ?> 
<?php get_template_part( 'template-parts/footer' ); ?> 
<?php get_template_part( 'template-parts/to-top' ); ?> 
<?php get_footer(); 