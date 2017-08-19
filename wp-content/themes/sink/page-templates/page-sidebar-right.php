<?php
    /**
     * Template Name: Page Sidebar Right
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'page-content page-template-wrapper' ); ?>>
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="entry-content">
                    <?php while ( have_posts() ) : the_post(); ?>
                        
                        <?php get_template_part( 'post-contents/content', 'page' ); ?>
                        
                        <?php if ( hippo_option( 'page-comment', FALSE, TRUE ) ) : ?>
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;
                            ?>
                        <?php endif; ?>
                    <?php endwhile; // end of the loop. ?>
                </div>
            </div>
            <!-- .col -->

            <div class="col-md-3 col-sm-4">
                <div class="primary-sidebar widget-area right-sidebar page-right-sidebar" role="complementary">
                    <?php dynamic_sidebar( 'hippo-page-sidebar' ); ?>
                </div>
            </div>
        </div>
        <!-- .row -->
    </div> <!-- .page-template -->
<?php get_footer();