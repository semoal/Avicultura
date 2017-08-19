<?php
    /**
     * The template for displaying product content within loops
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @see     https://docs.woocommerce.com/document/template-structure/
     * @author  WooThemes
     * @package WooCommerce/Templates
     * @version 3.0.0
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    global $post, $product, $woocommerce_loop;
    
    // Store loop count we're currently on
    if ( empty( $woocommerce_loop[ 'loop' ] ) ) :
        $woocommerce_loop[ 'loop' ] = 0;
    endif;
    
    // Store column count for displaying the grid
    if ( empty( $woocommerce_loop[ 'columns' ] ) ) :
        $woocommerce_loop[ 'columns' ] = apply_filters( 'loop_shop_columns', 4 );
    endif;
    
    // Ensure visibility
    if ( ! $product || ! $product->is_visible() ) :
        return;
    endif;
    
    // Increase loop count
    $woocommerce_loop[ 'loop' ] ++;
    
    // Extra post classes
    $classes = array();
    if ( 0 == ( $woocommerce_loop[ 'loop' ] - 1 ) % $woocommerce_loop[ 'columns' ] || 1 == $woocommerce_loop[ 'columns' ] ) :
        $classes[] = 'first';
    endif;
    if ( 0 == $woocommerce_loop[ 'loop' ] % $woocommerce_loop[ 'columns' ] ) :
        $classes[] = 'last';
    endif;
    
    $classes[] = 'flex-box product-block';
    
    $img_id     = get_post_thumbnail_id( get_the_ID() );
    $img_src    = wp_get_attachment_image_src( $img_id, "shop_catalog" );
    $css_styles = sprintf( 'background-image:url(%s)', esc_url( $img_src[ 0 ] ) );
    
    if ( ! $img_src ):
        $css_styles = sprintf( 'background-image:url(%s)', esc_url( wc_placeholder_img_src() ) );
    endif;
?>

<div class="<?php echo esc_attr( hippo_wc_product_column_class() ) ?>">
    <article <?php post_class( $classes ); ?> style="<?php echo esc_attr( $css_styles ) ?>">
        
        <?php if ( $product->get_stock_status() == 'outofstock' ) : ?>
            <span class="out-of-stock"><?php esc_html_e( 'Out of stock', 'sink' ); ?></span>
        <?php endif; ?>
        
        <?php if ( $product->is_on_sale() ) : ?>
            <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'sink' ) . '</span>', $post, $product ); ?>
        <?php endif; ?>
        
        <?php if ( ! hippo_option( 'linkable-product-block', FALSE, FALSE ) ): ?>
            <div class="bg-overlay"></div>
        <?php else: ?>
            <a href="<?php the_permalink(); ?>" class="bg-overlay"></a>
        <?php endif; ?>

        <div class="entry-header">
            <span class="post-category">
              <?php echo wc_get_product_category_list( $product->get_id() ); ?>
            </span>

            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            
            <?php do_action( 'hippo-product-block-after-title', $product ) ?>

        </div>

        <div class="product-price">
            <span class="amount"><?php echo $product->get_price_html(); ?></span>
        </div>

        <div class="entry-footer">
            <div class="action-button">
                <ul>
                    <?php if ( class_exists( 'YITH_WCWL_Shortcode' ) ): ?>
                        <li><?php echo YITH_WCWL_Shortcode::add_to_wishlist( array() ); ?></li>
                    <?php endif; ?>
                    <li>
                        <?php
                            woocommerce_template_loop_add_to_cart();
                        ?>
                    </li>
                    <li>
                        <a data-toggle="modal" class="modal-trigger" href="#quick-view-<?php the_id(); ?>"><i class="waves-effect waves-light zmdi zmdi-fullscreen-exit icon-circle"></i></a>
                    </li>
                    <li>
                        <a href="<?php the_permalink(); ?>"><i class="waves-effect zmdi zmdi-long-arrow-right zmdi-hc-fw icon-circle brand"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </article>
    <!-- .product-block -->
    
    <?php get_template_part( 'template-parts/product', 'quickview' ) ?>
</div>