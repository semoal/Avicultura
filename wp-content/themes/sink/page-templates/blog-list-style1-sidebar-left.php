<?php
    /**
     * Template Name: Blog List Style 1 Left Sidebar
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header(); ?>
    <section class="blog-section" role="main">
        <div class="row">
            <div class="col-md-9 col-md-push-3 col-sm-8 col-sm-push-4">
                <div class="posts-content">
                    <?php
                        $args  = array(
                            'posts_per_page' => absint( get_option( 'posts_per_page' ) ),
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'paged'          => get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1
                        );
                        $query = new WP_Query( $args );
                        
                        $hippo_blog_list_post_count = 1; // set up a counter so we know which post we're currently showing
                    ?>
                    <?php if ( $query->have_posts() ) : ?>
                        <?php while ( $query->have_posts() ) :
                            $query->the_post();
                            get_template_part( 'post-contents/content-blog-list-one' );
                            $hippo_blog_list_post_count ++;
                        endwhile; ?>
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
            
            <?php get_sidebar( 'left' ) ?>
        </div>
        <!-- .row -->
    </section> <!-- .blog-section -->
<?php get_footer();