<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    global $product;
?>

<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) ) ?>" rel="nofollow" data-product-id="<?php echo esc_attr( $product_id ) ?>" data-product-type="<?php echo esc_attr( $product_type ) ?>" class="<?php echo esc_attr( $link_classes ) ?>">
    <i class="zmdi zmdi-favorite-outline icon-circle waves-effect waves-light"></i>
</a>
<div class="loader-wrapper ajax-loading single-product-wishlist-loader">
    <svg class="loader" viewBox="0 0 50 50">
        <circle class="path" cx="25" cy="25" r="20"></circle>
    </svg>
</div>