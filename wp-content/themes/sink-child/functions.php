<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

// ------------------
// Funcion que elimina la cantidad 

function wc_remove_all_quantity_fields( $return, $product ) {
    return true;
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );

// ------------------
//Comprobamos que producto ha comprado el usuario
//Si lo ha comprado -> cambiamos el texto del botón

function user_logged_in_product_already_bought() {
    if (is_user_logged_in()) {
        global $product;
        $current_user = wp_get_current_user();
        $categoria = $product->get_categories( ', ', ' ' . _n( ' ', '  ', $cat_count, 'woocommerce' ) . ' ', ' ' );
        if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->id )){
            //Comprobamos si el producto es una suscripción:
            //Si lo es quitamos los botones de comprar y le decimos que ya la ha comprado
            if($categoria != null & strcmp($categoria,'Suscripciones')){
                //ocultamos los dos botones ya que no deberias poder comprar dos subscripciones
                remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
                remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                echo ("Ya has comprado esta suscripción");
            }
            //El producto no es una suscripcion por lo tanto se puede comprar más de una vez
            else{
                
            } 
        }
        //No ha comprado el producto lo dejamos con el botón de añadir el carrito solamente
        else{
        }
    }
}
add_action ( 'woocommerce_before_single_product', 'user_logged_in_product_already_bought', 30);


// ------------------
// 1. Change language in the website -- BETA

function my_theme_setup(){
    
    function mytheme_localised( $locale ) {
        if ( isset( $_GET['l'] ) ) {
            return sanitize_key( $_GET['l'] );
        }
        return $locale;
    }
    add_filter( 'locale', 'mytheme_localised' );
    
    load_theme_textdomain( 'es_ES', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'my_theme_setup' );


//-----------------
// Don't display products in the suscripcions category on the shop page.
// Doesn't show the suscription on shop
// Shows the suscription on suscription page

function custom_pre_get_posts_query( $q ){
if (!$q->is_main_query() || !is_shop()) return;
    $q->set( 'tax_query', array(array(
    'taxonomy' => 'product_cat',
    'field' => 'slug',
    'terms' => array('suscripciones'),
    'operator' => 'NOT IN'
    )));
}
add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );


