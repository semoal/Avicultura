<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header();
?>
    <section class="blog-section">
        <div class="row">
            <?php
                $layout = hippo_option( 'blog-layout', FALSE, 'sidebar-right' );
                
                $grid_class = 'col-md-12';
                
                if ( $layout == 'sidebar-right' ) :
                    $grid_class = ( is_active_sidebar( 'hippo-blog-sidebar' ) ) ? 'col-md-9 col-sm-8' : $grid_class;
                elseif ( $layout == 'sidebar-left' ) :
                    $grid_class = ( is_active_sidebar( 'hippo-blog-sidebar' ) ) ? 'col-md-9 col-md-push-3 col-sm-8 col-sm-push-4' : $grid_class;
                endif;
            ?>

            <div class="<?php echo esc_attr( $grid_class ); ?>">
                <div id="main" class="posts-content" role="main">
                    <?php if ( have_posts() ) :
                        while ( have_posts() ) : the_post();
                            get_template_part( 'post-contents/content', get_post_format() );
                        endwhile; ?>
                        <div class="pagination-wrap clearfix">
                            <?php
                                // Posts Pagination
                                if ( hippo_option( 'blog-page-nav', FALSE, TRUE ) ) :
                                    hippo_posts_pagination();
                                else :
                                    hippo_posts_navigation();
                                endif; ?>
                        </div>
                    <?php else :
                        get_template_part( 'post-contents/content', 'none' );
                    endif; ?>
                </div>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </section>
<?php get_footer();