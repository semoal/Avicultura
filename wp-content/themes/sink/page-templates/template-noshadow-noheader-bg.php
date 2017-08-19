<?php
    /**
     * Template Name: No Shadow and Header Background
     */
    
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'content-inner no-shadow no-header-background' ); ?>>
        <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile; // end of the loop.
        ?>
    </div>
<?php get_footer();