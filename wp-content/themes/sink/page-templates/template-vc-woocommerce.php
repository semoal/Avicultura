<?php
    /**
     * Template Name: Visual Composer WooCommerce Page
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    get_header(); ?>


    <div class="product-category-wrap">
        <div class="product-category-list">
            <button class="btn pink accent-2 hippo-button-toggle waves-effect waves-light" type="button" data-toggle="tooltip" title="<?php esc_html_e( 'More Categories', 'sink' ) ?>" data-placement="left">
                <i class="zmdi zmdi-more-vert"></i>
                <i class="zmdi zmdi-close"></i>
            </button>
            <ul>
                <?php wp_list_categories( array( 'title_li' => '', 'taxonomy' => 'product_cat' ) ); ?>
            </ul>
        </div>
    </div>

<?php if ( is_active_sidebar( 'woosidebar' ) ) : ?>
    <div class="shop-sidebar-wrap">

        <div class="shop-filter-trigger text-center">
            <i class="zmdi zmdi-filter-list waves-effect waves-light" data-toggle="tooltip"
               title="<?php esc_html_e( 'Product Filter', 'sink' ) ?>"></i>
        </div>
        <div class="shop-sidebar" style="display: none;">
            <div class="row">
                <?php dynamic_sidebar( 'woosidebar' ); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
        
        <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'post-contents/content', 'page' ); ?>
        <?php endwhile; // end of the loop. ?>

    </div>
<?php get_footer();