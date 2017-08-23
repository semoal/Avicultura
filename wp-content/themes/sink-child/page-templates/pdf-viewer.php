<?php
    /**
     * Template Name: PDF viewer 
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    get_header(); 
?>
<ul class="products">
    <?php
        $user_id = get_current_user_id();
        $current_user= wp_get_current_user();
        $customer_email = $current_user->email;
        $product_id = $_REQUEST['product_id'];
        $product = get_product($product_id);
        
        if(wc_customer_bought_product($customer_email, $user_id, $product_id)){
            $downloads = $product->get_files();
            echo do_shortcode('[pdfviewer width="100%" height="849px"]'.$downloads[$_REQUEST['key']]['file'].'[/pdfviewer]');
        }else{
            echo 'No tienes ninguna revista comprada';
        }
        wp_reset_postdata();
    ?>
</ul><!--/.products-->
    
        <?php
    get_footer(); 
    
?>
    
    