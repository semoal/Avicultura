<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    $attributes = shortcode_atts( array(
                                      'product_style'   => 'product-style-one',
                                      'product_post_id' => '',
                                      'el_class'        => ''
                                  ), $atts );
    
    ob_start(); ?>

    <div class="woocommerce">
        
        <?php
            // WP_Query arguments
            $args = array(
                'p'           => $attributes[ 'product_post_id' ],
                'post_type'   => 'product',
                'post_status' => 'publish',
            );
            
            $css_class = array();
            
            // The Query
            $query = new WP_Query( $args );
            
            if ( $query->have_posts() ) :
                
                woocommerce_product_loop_start();
                
                while ( $query->have_posts() ) :
                    
                    $query->the_post();
                    
                    global $post, $product, $woocommerce_loop;
                    
                    $img_id     = get_post_thumbnail_id( get_the_ID() );
                    $img_src    = wp_get_attachment_image_src( $img_id, "shop_catalog" );
                    $css_styles = sprintf( 'background-image:url(%s)', esc_url( $img_src[ 0 ] ) );
                    
                    if ( ! $img_src ):
                        $css_styles = sprintf( 'background-image:url(%s)', esc_url( wc_placeholder_img_src() ) );
                    endif;
                    
                    $css_class[] = esc_attr( $attributes[ 'product_style' ] );
                    $css_class[] = esc_attr( $attributes[ 'el_class' ] );
                    
                    ?>
                    <article class="flex-box product-block <?php echo implode( ' ', $css_class ) ?>"
                             style="<?php echo esc_attr( $css_styles ) ?>">
                        <?php
                            if ( $product->get_stock_status() == 'outofstock' ) : ?>
                                <span class="out-of-stock"><?php esc_html_e( 'Out of stock', 'sink' ); ?></span>
                            <?php endif; ?>
                        
                        <?php if ( $product->is_on_sale() ) : ?>
                            <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'sink' ) . '</span>', $post, $product ); ?>
                        <?php endif; ?>
                        
                        <?php if ( ! hippo_option( 'linkable-product-block', FALSE, FALSE ) ): ?>
                            <div class="bg-overlay"></div>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="bg-overlay"></a>
                        <?php endif; ?>

                        <div class="entry-header">
                            <span class="post-category">
                                <?php echo wc_get_product_category_list( $product->get_id() ); ?>
                            </span>

                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            
                            <?php do_action( 'hippo-product-block-after-title', $product ) ?>

                        </div>

                        <div class="product-price">
                            <span class="amount"><?php echo $product->get_price_html(); ?></span>
                        </div>

                        <div class="entry-footer">
                            <div class="action-button">
                                <ul>
                                    <?php if ( class_exists( 'YITH_WCWL_Shortcode' ) ) : ?>
                                        <li><?php echo YITH_WCWL_Shortcode::add_to_wishlist( array() ) ?></li>
                                    <?php endif; ?>

                                    <li>
                                        <?php
                                            woocommerce_template_loop_add_to_cart();
                                        ?>
                                    </li>
                                    <li>
                                        <a data-toggle="modal" class="modal-trigger" href="#quick-view-<?php the_id(); ?>"><i class="waves-effect waves-light zmdi zmdi-fullscreen-exit icon-circle"></i></a>
                                    </li>
                                    <li>
                                        <a href="<?php the_permalink(); ?>"><i class="waves-effect waves-light zmdi zmdi-long-arrow-right icon-circle brand"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </article> <!-- .product-block -->
                    
                    <?php get_template_part( 'template-parts/product', 'quickview' ) ?>
                    
                    <?php
                endwhile;
                
                woocommerce_product_loop_end();
            
            else:
                echo '<div class="col-md-12">' . esc_html__( 'No product post found.', 'sink' ) . '</div>';
            endif;
            
            wp_reset_postdata();
        ?>
    </div>
<?php
    
    echo $this->endBlockComment( $this->getShortcode() );
    echo ob_get_clean();