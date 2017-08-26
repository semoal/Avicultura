<?php
    /**
     * Single variation cart button
     *
     * @see     https://docs.woocommerce.com/document/template-structure/
     * @author  WooThemes
     * @package WooCommerce/Templates
     * @version 3.0.0
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
    <?php
        /**
         * @since 3.0.0.
         */
        do_action( 'woocommerce_before_add_to_cart_quantity' );
        woocommerce_quantity_input( array(
                                        'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                                        'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                                        'input_value' => isset( $_POST[ 'quantity' ] ) ? wc_stock_amount( $_POST[ 'quantity' ] ) : $product->get_min_purchase_quantity(),
                                    ) );
        /**
         * @since 3.0.0.
         */
        do_action( 'woocommerce_after_add_to_cart_quantity' );
    ?>
    <button type="submit" class="single_add_to_cart_button button alt buy_magazine"><i class="zmdi zmdi-shopping-cart-plus"></i> <?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
    <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>"/>
    <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>"/>
    <input type="hidden" name="variation_id" class="variation_id" value="0"/>
</div>
<?php
global $woocommerce, $product, $post;
$current_user = wp_get_current_user();
if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->id) || checkIfUserIsPremiumAndActive($current_user)) {
            $icon = 'zmdi-eye';
            $text = 'Leer revista';
            $variations = $product->get_available_variations();
             foreach($variations as $key => $variation){
                 if(strcasecmp($variation['attributes']['attribute_tipo'],'Online') == 0){
                     $productId = $variation['variation_id'];
                     foreach(get_product($variation['variation_id'])->get_files() as $key => $download){
                         $productKey = $key;
                         $productUrl = $_SERVER['HOST'].'/pdf-viewer?product_id='.$productId.'&key='.$productKey;
                        ?>
                            <a href="<?php echo $productUrl ?>">
                                <button type="button" class="single_add_to_cart_button button alt read_magazine">
                                    <i class="zmdi <?php echo $icon?>"></i>
                                    <?php echo esc_html($text); ?>
                                </button>
                            </a>
                        <?php
                         
                     }
                }
                
                
            }

 }else{
     //echo "No tienes ni premium ni has comprado este producto";
 }
?>