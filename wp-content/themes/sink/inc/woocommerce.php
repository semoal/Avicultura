<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    
    //----------------------------------------------------------------------
    // WooCommerce product image sizes on theme activation
    // woocommerce/woocommerce.php line #390-#392
    //----------------------------------------------------------------------
    if ( ! function_exists( 'sink_wc_image_dimensions_on_theme_active' ) ):
        function sink_wc_image_dimensions_on_theme_active() {
            // WOO-COMMERCE
            // Image sizes
            update_option( 'shop_catalog_image_size', array(
                'width'  => '500',    // px
                'height' => '745',    // px
                'crop'   => 1        // true
            ) );
            // Product category thumbs
            update_option( 'shop_single_image_size', array(
                'width'  => '500',    // px
                'height' => '550',    // px
                'crop'   => 1        // true
            ) );
            // Single product image
            update_option( 'shop_thumbnail_image_size', array(
                'width'  => '120',    // px
                'height' => '120',    // px
                'crop'   => 1        // false
            ) ); // Image gallery thumbs
        }
        
        add_action( 'after_switch_theme', 'sink_wc_image_dimensions_on_theme_active', 20 );
    endif;
    
    //-------------------------------------------------------------------------------
    //  WooCommerce Recommended Image Size Suggestion
    //-------------------------------------------------------------------------------
    if ( ! function_exists( 'sink_wc_recommended_image_sizes' ) ):
        function sink_wc_recommended_image_sizes( $settings ) {
            foreach ( $settings as $key => $setting ) {
                if ( $setting[ 'id' ] == 'image_options' ) {
                    if ( isset( $settings[ $key ][ 'desc' ] ) ):
                        $settings[ $key ][ 'desc' ] .= __( '
<h3>Sink - Recommended Image sizes:</h3>
<p><strong>Catalog Images:</strong> 500 &times; 745 - Crop. </p>
<p><strong>Single Product Image:</strong> 500 &times; 550 - Crop</p>
<p><strong>Product Thumbnails:</strong> 150 &times; 150 - Crop.</p>', 'sink' );
                    endif;
                }
            }
            
            return $settings;
        }
        
        add_filter( 'woocommerce_product_settings', 'sink_wc_recommended_image_sizes' );
    endif;
    
    // Remove woocommerce class from admin body class
    function sink_remove_admin_body_class( $admin_body_classes ) {
        
        if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'yith_wcwl_panel' ) {
            return $admin_body_classes;
        }
        
        return str_ireplace( 'woocommerce', '', $admin_body_classes );
    }
    
    add_filter( 'admin_body_class', 'sink_remove_admin_body_class', 30 );
    
    if ( ! function_exists( 'hippo_woocommerce_theme_setup' ) ):
        function hippo_woocommerce_theme_setup() {
            
            // WooCommerce Support
            add_theme_support( 'woocommerce' );
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
    endif;
    
    add_action( 'after_setup_theme', 'hippo_woocommerce_theme_setup' );
    
    
    if ( ! function_exists( 'hippo_add_wc_extra_variation' ) ):
        function hippo_add_wc_extra_variation( $current, $product, $variation ) {
            
            
            $attachment_id = get_post_thumbnail_id( $variation->get_variation_id() );
            $attachment    = wp_get_attachment_image_src( $attachment_id, 'hippo-single-product-thumbnail-mini' );
            
            $current[ 'thumb' ] = $attachment ? current( $attachment ) : '';
            
            return $current;
            
        }
        
        // add_filter( 'woocommerce_available_variation', 'hippo_add_wc_extra_variation', 10, 3 );
    endif;
    
    //----------------------------------------------------------------------
    // WooCommerce set post per page
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_set_products_per_page' ) ) :
        function hippo_set_products_per_page() {
            if ( is_shop() ) {
                return intval( hippo_option( 'shop-perpage', FALSE, '12' ) );
            }
            elseif ( is_product_category() ) {
                return intval( hippo_option( 'shop-cat-perpage', FALSE, '12' ) );
            }
        }
        
        add_filter( 'loop_shop_per_page', 'hippo_set_products_per_page', 20 );
    endif;
    
    //----------------------------------------------------------------------
    // WooCommerce addToCart Ajax Response
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woo_cart_count' ) ) :
        function hippo_woo_cart_count() {
            global $woocommerce;
            $cart_total = ( $woocommerce->cart->cart_contents_count ) ? $woocommerce->cart->cart_contents_count : '0';
            echo number_format_i18n( intval( $cart_total ) );
            die;
        }
        
        add_action( 'wp_ajax_hippo_cart_count', 'hippo_woo_cart_count' );
        add_action( 'wp_ajax_nopriv_hippo_cart_count', 'hippo_woo_cart_count' );
    endif;
    
    //----------------------------------------------------------------------
    // WooCommerce remove from MiniCart ajax response
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woo_mini_cart_remove' ) ) :
        function hippo_woo_mini_cart_remove() {
            global $woocommerce;
            $cart_item_key = $cart_item_key = sanitize_text_field( $_GET[ 'remove_item' ] );
            $woocommerce->cart->remove_cart_item( $cart_item_key );
            die;
        }
        
        add_action( 'wp_ajax_hippo_remove_from_mini_cart', 'hippo_woo_mini_cart_remove' );
        add_action( 'wp_ajax_nopriv_hippo_remove_from_mini_cart', 'hippo_woo_mini_cart_remove' );
    endif;
    
    //----------------------------------------------------------------------
    // WooCommerce WishList
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woo_wishlist_count' ) ) :
        function hippo_woo_wishlist_count() {
            
            if ( function_exists( 'yith_wcwl_count_products' ) ) {
                echo number_format_i18n( yith_wcwl_count_products() );
            }
            die;
        }
        
        add_action( 'wp_ajax_hippo_wishlist_total_count', 'hippo_woo_wishlist_count' );
        add_action( 'wp_ajax_nopriv_hippo_wishlist_total_count', 'hippo_woo_wishlist_count' );
    endif;
    
    //----------------------------------------------------------------------
    // Add new tab on product single page
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_new_product_policy_tab' ) ) :
        
        function hippo_new_product_policy_tab( $tabs ) {
            // Adds the new tab
            
            $data = trim( get_post_meta( get_the_ID(), 'product_policy', TRUE ) );
            
            if ( ! empty( $data ) ) {
                $tabs[ 'single_policy_tab' ] = array(
                    'title'    => esc_html__( 'Policy', 'sink' ),
                    'priority' => 11,
                    'callback' => 'hippo_new_product_tab_content'
                );
            }
            
            return $tabs;
        }
        
        function hippo_new_product_tab_content() {
            // The new tab content
            echo wp_kses_post( get_post_meta( get_the_ID(), 'product_policy', TRUE ) );
        }
        
        add_filter( 'woocommerce_product_tabs', 'hippo_new_product_policy_tab' );
    endif;
    
    //-------------------------------------------------------------------------------
    // Single product organize and remove woocommerce default breadcrumb
    //-------------------------------------------------------------------------------
    
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    remove_action( 'woocommerce_review_before', 'woocommerce_review_display_gravatar', 10 );
    remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta', 10 );
    
    
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );
    
    
    //-------------------------------------------------------------------------------
    // Add to cart Script Param
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woo_add_to_cart_script_handler' ) ):
        function hippo_woo_add_to_cart_script_handler() {
            return array(
                'ajax_url'                => WC()->ajax_url(),
                'wc_ajax_url'             => WC_AJAX::get_endpoint( "%%endpoint%%" ),
                'i18n_view_cart'          => '<i class="waves-effect waves-light zmdi zmdi-shopping-basket zmdi-hc-fw icon-circle black"></i>',
                'i18n_view_cart_title'    => esc_html__( 'View Cart', 'sink' ),
                'cart_url'                => apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url() ),
                'is_cart'                 => is_cart(),
                'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' )
            );
        }
        
        // File: /woocommerce/includes/class-wc-frontend-scripts.php#519
        add_filter( 'wc_add_to_cart_params', 'hippo_woo_add_to_cart_script_handler' );
    
    endif;
    
    //-------------------------------------------------------------------------------
    // Override WooCommerce Frontend Javascript
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woo_scripts' ) ) {
        
        function hippo_woo_scripts() {
            
            // Add to cart
            wp_deregister_script( 'wc-add-to-cart' );
            wp_register_script( 'wc-add-to-cart', get_template_directory_uri() . '/js/add-to-cart.js', array( 'jquery' ), FALSE, TRUE );
            
            hippo_enqueue_select2();
            
            if ( is_product() ) {
                hippo_enqueue_owl_carousel();
            }
        }
        
        add_action( 'wp_enqueue_scripts', 'hippo_woo_scripts' );
    }
    
    //-------------------------------------------------------------------------------
    // WooCommerce gallery thumb column
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woo_gallery_thumb_column' ) ) {
        function hippo_woo_gallery_thumb_column() {
            return 4; // .last class applied to every 4th thumbnail
        }
        
        add_filter( 'woocommerce_product_thumbnails_columns', 'hippo_woo_gallery_thumb_column' );
    }
    
    
    //-------------------------------------------------------------------------------
    // Hide Product Attribute From Single Product
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_product_attributs' ) ) :
        function hippo_product_attributs() {
            //global $product;
            //$product->list_attributes();
        }
        
        add_action( 'woocommerce_single_product_summary', 'hippo_product_attributs', 35 );
    endif;
    
    //-------------------------------------------------------------------------------
    // Show Share Buttons Product
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_share_button' ) ) :
        function hippo_share_button() {
            ob_start();
            
            if ( hippo_option( 'show-shop-share-button', FALSE, TRUE ) ) : ?>
                <div class="hippo-share-button">
                    <div class="social">
                        <ul class="list-inline">
                            <?php if ( hippo_option( 'shop-share-button', 'facebook', FALSE ) ) : ?>
                                <!--Facebook-->
                                <li><a class="btn-social facebook"
                                       href="//www.facebook.com/sharer.php?u=<?php echo rawurlencode( get_the_permalink() ) ?>&amp;t=<?php echo rawurlencode( get_the_title() ) ?>"
                                       title="<?php esc_html_e( 'Share this post on Facebook!', 'sink' ); ?>"
                                       target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <?php endif; ?>
                            
                            <?php if ( hippo_option( 'shop-share-button', 'twitter', FALSE ) ) : ?>
                                <!--Twitter-->
                                <li><a class="btn-social twitter"
                                       href="//twitter.com/home?status=<?php echo rawurlencode( sprintf( esc_html__( 'Reading: %s', 'sink' ), get_the_permalink() ) ) ?>"
                                       title="<?php esc_html_e( 'Share this post on Twitter!', 'sink' ); ?>"
                                       target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <?php endif; ?>
                            
                            <?php if ( hippo_option( 'shop-share-button', 'google', FALSE ) ) : ?>
                                <!--Google Plus-->
                                <li><a class="btn-social google-plus"
                                       href="//plus.google.com/share?url=<?php echo rawurlencode( get_the_permalink() ) ?>"
                                       title="<?php esc_html_e( 'Share this post on Google+!', 'sink' ); ?>"
                                       target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <?php endif; ?>
                            
                            <?php if ( hippo_option( 'shop-share-button', 'linkedin', FALSE ) ) : ?>
                                <!--Linkedin-->
                                <li><a class="btn-social linkedin"
                                       href="//www.linkedin.com/shareArticle?url=<?php echo rawurlencode( get_the_permalink() ) ?>&amp;mini=true&amp;title=<?php echo rawurlencode( get_the_title() ) ?>"
                                       title="<?php esc_html_e( 'Share this post on Linkedin!', 'sink' ); ?>"
                                       target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endif;
            
            echo ob_get_clean();
        }
        
        add_action( 'woocommerce_single_product_summary', 'hippo_share_button', 30 );
    endif;
    
    //-------------------------------------------------------------------------------
    // wishlist on single product page
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_single_add_to_wishlist' ) ) :
        function hippo_single_add_to_wishlist() {
            
            if ( class_exists( 'YITH_WCWL_Shortcode' ) ):
                
                ob_start();
                ?>
                <div class="single-product-wishlist"><?php
                        echo YITH_WCWL_Shortcode::add_to_wishlist( array() )
                    ?>
                </div>
                <?php echo ob_get_clean();
            endif;
            
        }
        
        add_action( 'woocommerce_after_add_to_cart_button', 'hippo_single_add_to_wishlist' );
    
    endif;
    
    //-------------------------------------------------------------------------------
    // remove woocommerce default pretty photo
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_remove_woo_lightbox' ) ):
        function hippo_remove_woo_lightbox() {
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
        }
        
        add_action( 'wp_enqueue_scripts', 'hippo_remove_woo_lightbox', 99 );
    
    endif;
    
    //===============================================================================
    // Add style attribute types on woocommerce taxonomy
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_admin_style_attributes_types' ) ) :
        
        function hippo_admin_style_attributes_types( $current ) {
            
            $current[ 'color' ] = esc_html__( 'Color', 'sink' );
            $current[ 'image' ] = esc_html__( 'Image', 'sink' );
            
            return $current;
        }
        
        add_filter( 'product_attributes_type_selector', 'hippo_admin_style_attributes_types' );
    endif;
    
    //-------------------------------------------------------------------------------
    // Get a Attribute taxonomy values
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_get_wc_attribute_taxonomy' ) ):
        
        function hippo_get_wc_attribute_taxonomy( $attribute_name ) {
            
            $transient = sprintf( 'hippo_get_wc_attribute_taxonomy_%s', $attribute_name );
            
            if ( FALSE === ( $attribute_taxonomy = get_transient( $transient ) ) ) {
                
                global $wpdb;
                
                $attribute_name     = str_replace( 'pa_', '', wc_sanitize_taxonomy_name( $attribute_name ) );
                $attribute_taxonomy = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name='{$attribute_name}'" );
                set_transient( $transient, $attribute_taxonomy );
            }
            
            return $attribute_taxonomy;
        }
    endif;
    
    // Clean transient
    add_action( 'woocommerce_attribute_updated', function ( $attribute_id, $attribute, $old_attribute_name ) {
        
        $transient     = sprintf( 'hippo_get_wc_attribute_taxonomy_%s', wc_attribute_taxonomy_name( $attribute[ 'attribute_name' ] ) );
        $old_transient = sprintf( 'hippo_get_wc_attribute_taxonomy_%s', wc_attribute_taxonomy_name( $old_attribute_name ) );
        delete_transient( $transient );
        delete_transient( $old_transient );
    }, 20, 3 );
    
    // Clean transient
    add_action( 'woocommerce_attribute_deleted', function ( $attribute_id, $attribute_name, $taxonomy ) {
        $transient = sprintf( 'hippo_get_wc_attribute_taxonomy_%s', $taxonomy );
        delete_transient( $transient );
    }, 20, 3 );
    
    //===============================================================================
    
    //-------------------------------------------------------------------------------
    // Set style attribute on product admin page
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_admin_style_attributes_values' ) ) :
        
        function hippo_admin_style_attributes_values( $tax, $i ) {
            
            global $woocommerce, $thepostid;
            if ( in_array( $tax->attribute_type, array( 'color', 'image' ) ) ) {
                
                $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name );
                
                $args = array(
                    'orderby'    => 'name',
                    'hide_empty' => 0,
                );
                ?>
                <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'sink' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo $i; ?>][]">
                    <?php
                        $all_terms = get_terms( $taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
                        if ( $all_terms ) :
                            foreach ( $all_terms as $term ) :
                                echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy, $thepostid ), TRUE, FALSE ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
                            endforeach;
                        endif;
                    ?>
                </select>
                <button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'sink' ); ?></button>
                <button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'sink' ); ?></button>
                <button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'sink' ); ?></button>
                <?php
            }
        }
        
        add_action( 'woocommerce_product_option_terms', 'hippo_admin_style_attributes_values', 10, 2 );
    
    endif;
    
    //-------------------------------------------------------------------------------
    // Color Variation Attribute Options
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_wc_color_variation_attribute_options' ) ) :
        
        /**
         * Output a list of variation attributes for use in the cart forms.
         *
         * @param array $args
         *
         * @since 2.4.0
         */
        function hippo_wc_color_variation_attribute_options( $args = array() ) {
            
            $args = wp_parse_args( $args, array(
                'options'          => FALSE,
                'attribute'        => FALSE,
                'product'          => FALSE,
                'selected'         => FALSE,
                'name'             => '',
                'id'               => '',
                'class'            => '',
                'show_option_none' => esc_html__( 'Choose an option', 'sink' )
            ) );
            
            $options   = $args[ 'options' ];
            $product   = $args[ 'product' ];
            $attribute = $args[ 'attribute' ];
            $name      = $args[ 'name' ] ? $args[ 'name' ] : wc_variation_attribute_name( $attribute );
            $id        = $args[ 'id' ] ? $args[ 'id' ] : sanitize_title( $attribute ) . $product->get_id();
            $class     = $args[ 'class' ];
            
            if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
                $attributes = $product->get_variation_attributes();
                $options    = $attributes[ $attribute ];
            }
            
            echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '">';
            
            if ( $args[ 'show_option_none' ] ) {
                echo '<option value="">' . esc_html( $args[ 'show_option_none' ] ) . '</option>';
            }
            
            if ( ! empty( $options ) ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
                    
                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options ) ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args[ 'selected' ] ), $term->slug, FALSE ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                        }
                    }
                }
            }
            
            echo '</select>';
            
            echo '<ul class="list-inline variable-items-wrapper color-variable-wrapper">';
            if ( ! empty( $options ) ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
                    
                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options ) ) {
                            $get_term_meta  = hippo_get_term_meta( $term->term_id, 'product_attribute_color', TRUE );
                            $selected_class = ( sanitize_title( $args[ 'selected' ] ) == $term->slug ) ? 'selected' : '';
                            ?>
                            <li data-toggle="tooltip" data-placement="top" class="variable-item color-variable-item color-variable-item-<?php echo $term->slug ?> <?php echo $selected_class ?>" title="<?php echo esc_html( $term->name ) ?>" style="background-color:<?php echo esc_attr( $get_term_meta ) ?>;" data-value="<?php echo esc_attr( $term->slug ) ?>"></li>
                            <?php
                        }
                    }
                }
            }
            echo '</ul>';
        }
    
    endif;
    
    //-------------------------------------------------------------------------------
    // Image Variation Attribute Options
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_wc_image_variation_attribute_options' ) ) :
        
        /**
         * Output a list of variation attributes for use in the cart forms.
         *
         * @param array $args
         *
         * @since 2.4.0
         */
        function hippo_wc_image_variation_attribute_options( $args = array() ) {
            
            $args = wp_parse_args( $args, array(
                'options'          => FALSE,
                'attribute'        => FALSE,
                'product'          => FALSE,
                'selected'         => FALSE,
                'name'             => '',
                'id'               => '',
                'class'            => '',
                'show_option_none' => esc_html__( 'Choose an option', 'sink' )
            ) );
            
            $options   = $args[ 'options' ];
            $product   = $args[ 'product' ];
            $attribute = $args[ 'attribute' ];
            $name      = $args[ 'name' ] ? $args[ 'name' ] : wc_variation_attribute_name( $attribute );
            $id        = $args[ 'id' ] ? $args[ 'id' ] : sanitize_title( $attribute ) . $product->get_id();
            $class     = $args[ 'class' ];
            
            if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
                $attributes = $product->get_variation_attributes();
                $options    = $attributes[ $attribute ];
            }
            
            echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '">';
            
            if ( $args[ 'show_option_none' ] ) {
                echo '<option value="">' . esc_html( $args[ 'show_option_none' ] ) . '</option>';
            }
            
            if ( ! empty( $options ) ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
                    
                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options ) ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args[ 'selected' ] ), $term->slug, FALSE ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                        }
                    }
                }
            }
            
            echo '</select>';
            
            echo '<ul class="list-inline variable-items-wrapper image-variable-wrapper">';
            if ( ! empty( $options ) ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
                    
                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options ) ) {
                            $get_term_meta  = hippo_get_term_meta( $term->term_id, 'product_attribute_image', TRUE );
                            $image          = wp_get_attachment_image_src( $get_term_meta, 'full' );
                            $selected_class = ( sanitize_title( $args[ 'selected' ] ) == $term->slug ) ? 'selected' : '';
                            ?>
                            <li data-toggle="tooltip" data-placement="top"
                                class="variable-item image-variable-item image-variable-item-<?php echo $term->slug ?> <?php echo $selected_class ?>"
                                title="<?php echo esc_html( $term->name ) ?>"
                                data-value="<?php echo esc_attr( $term->slug ) ?>"><img
                                        alt="<?php echo esc_html( $term->name ) ?>"
                                        src="<?php echo esc_url( $image[ 0 ] ) ?>"></li>
                            <?php
                        }
                    }
                }
            }
            echo '</ul>';
        }
    endif;
    
    //-------------------------------------------------------------------------------
    // Remove WooCommerce Responsive Css
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_remove_woocommerce_enqueue_styles' ) ) :
        function hippo_remove_woocommerce_enqueue_styles( $styles ) {
            
            unset( $styles[ 'woocommerce-layout' ] );
            unset( $styles[ 'woocommerce-smallscreen' ] );
            
            return $styles;
        }
        
        add_filter( 'woocommerce_enqueue_styles', 'hippo_remove_woocommerce_enqueue_styles' );
    endif;
    
    //-------------------------------------------------------------------------------
    //  WooCommerce Get Currency list
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_wc_get_currencies' ) and function_exists( 'get_woocommerce_currencies' ) ) :
        
        function hippo_wc_get_currencies() {
            
            $currency_code_options = (array) get_woocommerce_currencies();
            
            foreach ( $currency_code_options as $code => $name ) {
                $currency_code_options[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
            }
            
            return $currency_code_options;
        }
    endif;
    
    //-------------------------------------------------------------------------------
    //  WooCommerce Get Currency icon position
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_wc_get_currency_position' ) and function_exists( 'get_woocommerce_currency_symbol' ) ) :
        function hippo_wc_get_currency_position() {
            
            return array(
                'left'        => esc_html__( 'Left', 'sink' ) . ' (' . get_woocommerce_currency_symbol() . '99.99)',
                'right'       => esc_html__( 'Right', 'sink' ) . ' (99.99' . get_woocommerce_currency_symbol() . ')',
                'left_space'  => esc_html__( 'Left with space', 'sink' ) . ' (' . get_woocommerce_currency_symbol() . ' 99.99)',
                'right_space' => esc_html__( 'Right with space', 'sink' ) . ' (99.99 ' . get_woocommerce_currency_symbol() . ')'
            );
            
        }
    endif;
    
    
    //-------------------------------------------------------------------------------
    //  WooCommerce Change Shop Thumbnail Image Size
    //-------------------------------------------------------------------------------
    
    // admin.php?page=wc-settings&tab=products&section=display
    
    if ( ! function_exists( 'hippo_woocommerce_shop_thumbnail_image_size' ) ):
        
        function hippo_woocommerce_shop_thumbnail_image_size( $size ) {
            $size[ 'width' ]  = 120;
            $size[ 'height' ] = 80;
            $size[ 'crop' ]   = 1;
            
            return $size;
        }
        
        // add_filter( 'woocommerce_get_image_size_shop_thumbnail', 'hippo_woocommerce_shop_thumbnail_image_size' );
    
    endif;
    
    //-------------------------------------------------------------------------------
    //  WooCommerce Change Shop Catalog Image Size
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woocommerce_shop_catalog_image_size' ) ):
        
        function hippo_woocommerce_shop_catalog_image_size( $size ) {
            $size[ 'width' ]  = 255;
            $size[ 'height' ] = 295;
            $size[ 'crop' ]   = 1;
            
            return $size;
        }
        
        // add_filter( 'woocommerce_get_image_size_shop_catalog', 'hippo_woocommerce_shop_catalog_image_size' );
    endif;
    
    //-------------------------------------------------------------------------------
    //  WooCommerce Change Shop Subcategory Catalog Image Size
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woocommerce_shop_subcategory_catalog_image_size' ) ):
        
        function hippo_woocommerce_shop_subcategory_catalog_image_size() {
            return 'hippo-single-product-thumbnail';
        }
        
        add_filter( 'subcategory_archive_thumbnail_size', 'hippo_woocommerce_shop_subcategory_catalog_image_size' );
    endif;
    
    //-------------------------------------------------------------------------------
    //  WooCommerce Change Shop Single Image Size
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_woocommerce_shop_single_image_size' ) ):
        
        function hippo_woocommerce_shop_single_image_size( $size ) {
            $size[ 'width' ]  = 500;
            $size[ 'height' ] = 550;
            $size[ 'crop' ]   = 1;
            
            return $size;
        }
        
        // add_filter( 'woocommerce_get_image_size_shop_single', 'hippo_woocommerce_shop_single_image_size' );
    
    endif;
    
    //-------------------------------------------------------------------------------
    //  Check Is we are on single product page? really?
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_is_woocommerce_single_product' ) ):
        
        function hippo_is_woocommerce_single_product() {
            
            global $product, $hippo_is_main_product;
            
            if ( isset( $hippo_is_main_product ) and $hippo_is_main_product ) {
                return TRUE;
            }
            
            return FALSE;
        }
    
    endif;
    
    //-------------------------------------------------------------------------------
    //  Truly Set that we are on single product page
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_set_woocommerce_single_product' ) ):
        
        function hippo_set_woocommerce_single_product() {
            
            global $product, $hippo_is_main_product;
            
            if ( is_product() ) {
                $hippo_is_main_product = TRUE;
            }
        }
        
        add_action( 'woocommerce_before_main_content', 'hippo_set_woocommerce_single_product' );
    
    endif;
    
    //-------------------------------------------------------------------------------
    //  Unset Single product page because we are now on related product loop
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_unset_woocommerce_single_product' ) ):
        
        function hippo_unset_woocommerce_single_product() {
            
            global $product, $hippo_is_main_product;
            
            if ( is_product() ) {
                $hippo_is_main_product = FALSE;
            }
        }
    
    endif;
    
    //-------------------------------------------------------------------------------
    //  Change Popup attribute image
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_change_woocommerce_popup_product_attribute_image' ) ):
        
        function hippo_change_woocommerce_popup_product_attribute_image( $options, $variable_object, $variation ) {
            
            if ( ! hippo_is_woocommerce_single_product() ) {
                if ( has_post_thumbnail( $variation->get_variation_id() ) ) {
                    $attachment_id = get_post_thumbnail_id( $variation->get_variation_id() );
                    $attachment    = wp_get_attachment_image_src( $attachment_id, 'shop_single' );
                    $image         = $attachment ? current( $attachment ) : '';
                }
                else {
                    $image = '';
                }
                
                $options[ 'image_src' ] = $image;
            }
            
            return $options;
            
        }
        
        
        // add_filter( 'woocommerce_available_variation', 'hippo_change_woocommerce_popup_product_attribute_image', 9999, 3 );
    endif;
    
    //-------------------------------------------------------------------------------
    //  Check shipping calc enabled on cart page
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_has_shipping_calc_on_cart_page' ) ):
        
        function hippo_has_shipping_calc_on_cart_page() {
            if ( is_cart() and ( get_option( 'woocommerce_enable_shipping_calc' ) === 'yes' ) and ( get_option( 'woocommerce_calc_shipping' ) === 'yes' ) ) {
                return TRUE;
            }
            
            return FALSE;
        }
    endif;
    
    //-------------------------------------------------------------------------------
    //  Product Column Class
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_wc_product_column_class' ) ):
        
        function hippo_wc_product_column_class( $class = array() ) {
            
            if ( ! empty( $class ) ) {
                if ( ! is_array( $class ) ) {
                    $class = preg_split( '#\s+#', $class );
                }
                $class = array_map( 'esc_attr', $class );
            }
            
            
            $classes = apply_filters( 'hippo_wc_product_column_class', array_merge( array(
                                                                                        'col-lg-3',
                                                                                        'col-md-4',
                                                                                        'col-sm-6',
                                                                                        'col-ms-6',
                                                                                        'col-xs-12'
                                                                                    ), (array) $class ) );
            
            return implode( ' ', array_unique( $classes ) );
        }
    endif;
    
    //-------------------------------------------------------------------------------
    //  Product Category Column Class
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_wc_product_sub_category_column_class' ) ):
        
        function hippo_wc_product_sub_category_column_class( $class = array() ) {
            
            $default_grid = hippo_option( 'shop-sub-category-column', FALSE, 'col-md-6' );
            $grid_classes = hippo_option( 'shop-sub-category-grid-class', FALSE, '' );
            
            $grid_classes = $default_grid . ' ' . $grid_classes;
            $grid_classes = array_unique( explode( ' ', $grid_classes ) );
            
            return apply_filters( 'hippo_wc_product_sub_category_column_class', trim( implode( ' ', $grid_classes ) ) );
        }
    endif;
    
    
    if ( ! function_exists( 'hippo_wc_cross_sell_product_column_class' ) ):
        
        function hippo_wc_cross_sell_product_column_class( $class ) {
            
            if ( is_cart() ) {
                $crosssells = WC()->cart->get_cross_sells();
                $class      = array();
                
                if ( count( $crosssells ) <= 1 ) {
                    $class[] = 'col-xs-12';
                }
                elseif ( count( $crosssells ) <= 2 ) {
                    $class[] = 'col-lg-6';
                    $class[] = 'col-md-6';
                    $class[] = 'col-sm-6';
                    $class[] = 'col-ms-6';
                    $class[] = 'col-xs-12';
                }
                elseif ( count( $crosssells ) <= 3 ) {
                    $class[] = 'col-md-4';
                    $class[] = 'col-sm-6';
                    $class[] = 'col-xs-12';
                }
            }
            
            return $class;
        }
        
        add_filter( 'hippo_wc_product_column_class', 'hippo_wc_cross_sell_product_column_class', 9999 );
    endif;