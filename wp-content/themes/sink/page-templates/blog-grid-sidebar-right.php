<?php
    /**
     * Template Name: Blog Grid Right Sidebar
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header(); ?>
    <section class="blog-section">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="row posts-content grid-post-content" role="main">
                    <?php
                        $args  = array(
                            'posts_per_page' => absint( get_option( 'posts_per_page' ) ),
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'paged'          => get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1
                        );
                        $query = new WP_Query( $args );
                    ?>
                    
                    <?php if ( $query->have_posts() ) : ?>
                        
                        <?php while ( $query->have_posts() ) :
                            $query->the_post(); ?>

                            <div class="col-md-6 masonry-grid">
                                <?php get_template_part( 'post-contents/content-masonry' ); ?>
                            </div>
                        
                        <?php endwhile; ?>

                        <div class="col-md-12 pagination-wrap text-center">
                            <?php hippo_grid_posts_pagination(); ?>
                        </div>
                        <?php
                    else :
                        get_template_part( 'post-contents/content', 'none' );
                    endif;
                        wp_reset_postdata();
                    ?>
                </div>
                <!-- .posts-content -->
            </div>
            <!-- .col -->
            <?php get_sidebar( 'right' ) ?>
        </div>
        <!-- .row -->
    </section> <!-- .blog-section -->
<?php get_footer();