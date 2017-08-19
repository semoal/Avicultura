<?php
    /**
     * Template Name: Blog Default No Sidebar
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header();
    
    $args     = array(
        'posts_per_page' => absint( get_option( 'posts_per_page' ) ),
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'paged'          => get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1
    );
    $wp_query = new WP_Query( $args );
?>

    <section class="blog-section">
        <div class="row">
            <div class="col-xs-12">
                <div id="main" class="posts-content" role="main">
                    <?php if ( $wp_query->have_posts() ) : ?>
                        <?php /* Start the Loop */ ?>
                        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                            <?php
                            /* Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'post-contents/content', get_post_format() );
                            ?>
                        <?php endwhile; ?>
                        <div class="pagination-wrap clearfix">
                            <?php
                                // Posts Pagination
                                if ( hippo_option( 'blog-page-nav', FALSE, TRUE ) ) :
                                    hippo_posts_pagination();
                                else :
                                    hippo_posts_navigation();
                                endif; ?>
                        </div>
                    <?php else : ?>
                        <?php get_template_part( 'post-contents/content', 'none' ); ?>
                        <?php
                    endif;
                        wp_reset_postdata();
                    ?>
                </div>
                <!-- #main -->
            </div>
        </div>
        <!-- /.row -->
    </section><!-- .blog-section -->
<?php get_footer();