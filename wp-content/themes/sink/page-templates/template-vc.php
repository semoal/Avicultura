<?php
    /**
     * Template Name: Visual Composer Page
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
        
        <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'post-contents/content', 'page' ); ?>
        <?php endwhile; // end of the loop. ?>

    </div>
<?php get_footer();