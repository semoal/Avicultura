<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
	
// ------------------
//Cambia el texto del botón del producto

function sv_wc_external_product_button($button_text) {
    return 'Leer revista';
}

// ------------------
// Funcion que elimina la cantidad 

function wc_remove_all_quantity_fields( $return, $product ) {
    return true;
}

// ------------------
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

// ------------------
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


// ------------------
// 1. Register new endpoint to use for My Account page
// Note: Resave Permalinks or it will give 404 error
 
function bbloomer_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'premium-support', EP_ROOT | EP_PAGES );
}
 
add_action( 'init', 'bbloomer_add_premium_support_endpoint' );
 
 
// ------------------
// 2. Add new query var
 
function bbloomer_premium_support_query_vars( $vars ) {
    $vars[] = 'premium-support';
    return $vars;
}
 
add_filter( 'query_vars', 'bbloomer_premium_support_query_vars', 0 );
 
 
// ------------------
// 3. Insert the new endpoint into the My Account menu
 
function bbloomer_add_premium_support_link_my_account( $items ) {
    $items['premium-support'] = 'Suscripciones';
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );
 
 
// ------------------
// 4. Add content to the new endpoint
 
function bbloomer_premium_support_content() {
    wc_get_template('myaccount/subscription-list.php');
}
 
add_action( 'woocommerce_account_premium-support_endpoint', 'bbloomer_premium_support_content' );

// ------------------
// 5. Reorder the items in the account nav
 
function wpb_woo_my_account_order() {
	$myorder = array(
		'dashboard'          => __( 'Dashboard', 'woocommerce' ),
		'orders'             => __( 'Orders', 'woocommerce' ),
		'downloads'          => __( 'Download', 'woocommerce' ),
		'edit-address'       => __( 'Addresses', 'woocommerce' ),
		'premium-support'    => __( 'Suscripciones', 'woocommerce' ),
		'customer-logout'    => __( 'Logout', 'woocommerce' ),
	);
	return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );