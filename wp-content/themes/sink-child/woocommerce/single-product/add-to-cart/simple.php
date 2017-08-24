<?php
    /**
     * Simple product add to cart
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
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
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    global $product;
    
    if ( ! $product->is_purchasable() ) :
        return;
    endif;
    
    echo wc_get_stock_html( $product );
?>

<?php if ( $product->is_in_stock() ) : ?>
    
    <?php do_action( 'woocommerce_before_add_to_cart_form' ); 
    
        // if(count($product->get_files())>0){
            
        //     foreach($product->get_files() as $key => $d){
        //         echo '<form class="cart" method="post" action="https://avicultura-kiatoski.c9users.io/pdf-viewer?product_id='.$product->id.'&key='.$key.'">'; 
        //     }
            
        // }else{
        //     echo '<form class="cart" method="post" enctype="multipart/form-data">';
        // }
        echo '<form class="cart" method="post" enctype="multipart/form-data">';
    ?>

    
        
        <?php
            
            do_action( 'woocommerce_before_add_to_cart_button' );
            
            do_action( 'woocommerce_before_add_to_cart_quantity' );
            
            woocommerce_quantity_input( array(
                                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                                            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                                            'input_value' => isset( $_POST[ 'quantity' ] ) ? wc_stock_amount( $_POST[ 'quantity' ] ) : $product->get_min_purchase_quantity(),
                                        ) );
            
            do_action( 'woocommerce_after_add_to_cart_quantity' );
        ?>

        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"/>
         <button type="submit" class="single_add_to_cart_button button alt buy_magazine">
                <i class="zmdi zmdi-shopping-cart-plus"></i>
                <?php echo esc_html("Añadir al carrito"); ?>
        </button>
        <?php
        $current_user = wp_get_current_user();
        if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->id)) {
            $icon = 'zmdi-eye';
            $text = 'Leer revista';
             foreach($product->get_files() as $key => $d){
                $productUrl = $_SERVER['HOST'].'/pdf-viewer?product_id='.$product->id.'&key='.$key;
            }
        ?>
        <a href="<?php echo $productUrl ?>">
            <button type="button" class="single_add_to_cart_button button alt read_magazine">
                <i class="zmdi <?php echo $icon?>"></i>
                <?php echo esc_html($text); ?>
            </button>
        </a>
        <?php
        }
        ?>
        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    </form>
    <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif;