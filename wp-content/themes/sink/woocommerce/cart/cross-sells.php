<?php
    /**
     * Cross-sells
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
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

    global $product, $woocommerce_loop;

    if ( ! $crosssells = WC()->cart->get_cross_sells() ) {
        return;
    }

    $args = array(
        'post_type'           => 'product',
        'ignore_sticky_posts' => 1,
        'no_found_rows'       => 1,
        'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', $posts_per_page ),
        'orderby'             => $orderby,
        'post__in'            => $crosssells,
        'meta_query'          => WC()->query->get_meta_query()
    );

    $products = new WP_Query( $args );

    $woocommerce_loop[ 'name' ] = 'cross-sells';

    $woocommerce_loop[ 'columns' ] = apply_filters( 'woocommerce_cross_sells_columns', $columns );

    if ( $products->have_posts() ) :

        $column_class = 'col-xs-12';

        if ( count( $crosssells ) <= 1 ) {
            $column_class = 'col-lg-4 col-md-4 col-xs-12';
        } elseif ( count( $crosssells ) <= 2 ) {
            $column_class = 'col-lg-6 col-xs-12';
        } elseif ( count( $crosssells ) <= 3 ) {
            $column_class = 'col-xs-12';
        }

        ?>

        <div class="<?php echo esc_attr( $column_class ) ?>">
            <div class="cross-sells products">

                <h2><?php esc_html_e( 'You may be interested in&hellip;', 'sink' ) ?></h2>

                <div class="row">
                    <?php
                        woocommerce_product_loop_start();
                        hippo_unset_woocommerce_single_product();
                    ?>

                    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop.
                    ?>

                    <?php woocommerce_product_loop_end(); ?>
                </div>
            </div>
        </div>

    <?php endif;

    wp_reset_query();