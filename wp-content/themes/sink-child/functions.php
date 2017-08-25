<?php

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
        if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->id )){
            //Comprobamos si el producto es una suscripción:
            //Si lo es quitamos los botones de comprar y le decimos que ya la ha comprado
            if(checkIfUserIsPremiumAndActive($current_user)){
                //ocultamos los dos botones ya que no deberias poder comprar dos subscripciones
                // remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
                // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
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


function user_bought_product() {
    if (is_user_logged_in()) {
        $_pf = new WC_Product_Factory();  
        $product = $_pf->get_product($_POST['id_product']);
        $current_user = wp_get_current_user();
        if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->id )){
            echo "hola";
        }else{
            echo "no has comprado este producto";
        }
    }else{
        echo "no hay nada";
        return "tus muelas";
    }
}
add_action('wp_ajax_user_bought_product', 'user_bought_product');
add_action('wp_ajax_nopriv_user_bought_product', 'user_bought_product');
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


//-----------------
// Muestra la página - Mis revistas - 
// Solo muestra las revistas compradas por el usuario
// Para meterlo en las páginas es un shortcode [mis-revistas]

add_shortcode( 'mis-revistas', 'bbloomer_user_products_bought' );
function bbloomer_user_products_bought() {
    global $product, $woocommerce, $woocommerce_loop;
    $columns = 3;
    $current_user = wp_get_current_user();
    $args = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'meta_query'            => array(
            array(
                'key'           => '_visibility',
                'value'         => array('catalog', 'visible'),
                'compare'       => 'IN'
            )
        )
    );
    $loop = new WP_Query($args);
     
    ob_start();
     
    woocommerce_product_loop_start();
     
    while ( $loop->have_posts() ) : $loop->the_post();
        $theid = get_the_ID();
        if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $theid ) ) {
            wc_get_template_part( 'content', 'product' ); 
        } 
    endwhile; 
     
    woocommerce_product_loop_end();
    woocommerce_reset_loop();
    wp_reset_postdata();
     
    return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
}
//-----------------
// Funcion que analiza un usuario y detecta si tiene un plan de suscripcion y este esta activo

function checkIfUserIsPremiumAndActive($user){
    $user_capabilities = WooCommerce_Membership_User::get_user_capabilities($user);
    $user_capabilities = WooCommerce_Membership_Plan::enabled_keys_only($user_capabilities);
    if (count($user_capabilities) > 0) {
        return true;
    }else{
        return false;
    }
}

//-----------------
// Funcion que devuelve la categoria de un producto

function getCategory($product){
     $categoria = $product->get_categories( ', ', ' ' . _n( ' ', '  ', $cat_count, 'woocommerce' ) . ' ', ' ' );
     return $categoria;
}

// Funcion que comprueba si la categoria que le pasamos es la misma que tiene el producto

function checkCategory($product,$category){
    $categoria = $product->get_categories( ', ', ' ' . _n( ' ', '  ', $cat_count, 'woocommerce' ) . ' ', ' ' );
    if(strcasecmp($categoria,$category) == 0){
        return true;
    }
    return false;
}



// 2. Asociamos una función a la acción
add_action( 'wp_ajax_is_product_bought', 'is_product_bought' ); // Para usuarios logeados
add_action( 'wp_ajax_nopriv_is_product_bought', 'is_product_bought' ); // Para usuarios no logeados
// 3. Escribimos la función de callback
function is_product_bought() {
    $status;
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        if (wc_customer_bought_product( $current_user->user_email, $current_user->ID, $_POST['id_product'] )){
            $status = true;
            echo json_encode($status);
        }else{
            $status = false;
            echo json_encode($status);
        }
    }else{
        $status = false;
        echo json_encode($status);
    }
   die(); // Importante finalizar el script
}