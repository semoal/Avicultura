<?php
    /**
     * The template for displaying product category thumbnails within loops
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
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
     * @version 2.6.1
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    $term    = get_term( $category, 'product_cat' );
    $term_id = $term->term_id;
    
    $img_id     = get_term_meta( $term_id, 'thumbnail_id', TRUE );
    $img_src    = wp_get_attachment_image_src( $img_id, 'shop_catalog' );
    $css_styles = sprintf( 'background-image:url(%s)', esc_url( $img_src[ 0 ] ) );
    
    if ( ! $img_src ) :
        $css_styles = sprintf( 'background-image:url(%s)', esc_url( wc_placeholder_img_src() ) );
    endif;

?>
<div class="<?php echo hippo_wc_product_sub_category_column_class() ?>">
    <article <?php wc_product_cat_class( 'flex-box product-category-block', $category ); ?> style="<?php echo esc_attr( $css_styles ) ?>">

        <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="bg-overlay"></a>

        <div class="entry-footer center-block">
            <h2><?php echo esc_html( $term->name ); ?></h2> <br>
            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo apply_filters( 'hippo-product-category-block-find-more-text', esc_html__( 'Find out More', 'sink' ) ); ?>
                <i class="zmdi zmdi-long-arrow-right"></i>
            </a>
        </div>
    </article>
</div>