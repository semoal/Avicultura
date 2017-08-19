<?php
    /**
     * Template Name: Home
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'content-inner' ); ?>>
        <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile; // end of the loop.
        ?>
    </div>
<?php get_footer();