<?php
    /**
     * Loop Add to Cart
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @see           https://docs.woocommerce.com/document/template-structure/
     * @author        WooThemes
     * @package       WooCommerce/Templates
     * @version       3.0.0
     */
    
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    global $product;
    
    if ( isset( $class ) ) {
        $class = explode( ' ', $class );
        $class = array_flip( $class );
        unset( $class[ 'button' ] );
        $class = implode( ' ', array_flip( $class ) );
    } else {
        $class = '';
    }

    echo apply_filters( 'woocommerce_loop_add_to_cart_link',
    sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
             //Url a cambiar cuando el producto está comprado
             esc_url( $product->add_to_cart_url() ),
             esc_attr( isset( $quantity ) ? $quantity : 1 ),
             esc_attr( $product->get_id() ),
             esc_attr( $product->get_sku() ),
             esc_attr( $class ),
             '<i class="waves-effect zmdi zmdi-shopping-cart-plus zmdi-hc-fw icon-circle black"></i><div class="loader-wrapper ajax-loading"><svg class="loader" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20"></circle></svg></div>'
              ),     
    $product
    );
