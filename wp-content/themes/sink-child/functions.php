<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

//Cambia el texto del botón del producto
function sv_wc_external_product_button($button_text) {
    return 'Leer revista';
}

/*
* Funcion que elimina la cantidad 
*/
function wc_remove_all_quantity_fields( $return, $product ) {
    return true;
}

//Comprobamos que producto ha comprado el usuario
//Si lo ha comprado -> cambiamos el texto del botón
add_action ( 'woocommerce_before_single_product', 'user_logged_in_product_already_bought', 30);
function user_logged_in_product_already_bought() {
    if ( is_user_logged_in() ) {
        global $product;
        $current_user = wp_get_current_user();
        if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->id )){
            //Cambia el texto del botón 
            add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );
            //Quita el select de cantidad
            add_filter( 'woocommerce_product_single_add_to_cart_text', 'sv_wc_external_product_button' );
        } 
    }
}

//Comprobamos si el producto lo ha comprado
//Si lo ha comprado no le permitimos volverlo a comprar
add_filter('woocommerce_add_to_cart_validation','rei_woocommerce_add_to_cart_validation',20, 2);
function rei_woocommerce_add_to_cart_validation($valid, $product_id){
    $current_user = wp_get_current_user();
    if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id)) {
        wc_add_notice( __( 'Ya has comprado este producto', 'woocommerce' ), 'error' );
        $valid = false;
        //Aqui deberia abrir el pdf que sea de ese producto
    }
    return $valid;
}

