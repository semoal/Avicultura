<?php
	/**
	 * The Template for displaying product archives, including the main shop page which is a post type archive
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see           https://docs.woothemes.com/document/template-structure/
	 * @author        WooThemes
	 * @package       WooCommerce/Templates
	 * @version       2.0.0
	 */

	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	get_header( 'shop' );
?>

<?php
	/**
	 * woocommerce_before_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	do_action( 'woocommerce_before_main_content' );
?>

	<div class="product-category-wrap">
		<div class="product-category-list">
			<button class="btn pink accent-2 hippo-button-toggle waves-effect waves-light" type="button"
			        data-toggle="tooltip" title="<?php esc_html_e( 'More Categories', 'sink' ) ?>"
			        data-placement="left">
				<i class="zmdi zmdi-more-vert"></i>
				<i class="zmdi zmdi-close"></i>
			</button>
			<ul>
				<?php wp_list_categories( array(
					                          'title_li' => '',
					                          'taxonomy' => 'product_cat',

				                          ) ); ?>
			</ul>
		</div>
	</div>

<?php if ( is_active_sidebar( 'woosidebar' ) ) : ?>
	<div class="shop-sidebar-wrap">

		<div class="shop-filter-trigger text-center">
			<i class="zmdi zmdi-filter-list waves-effect waves-light" data-toggle="tooltip"
			   title="<?php esc_html_e( 'Product Filter', 'sink' ) ?>"></i>
		</div>
		<div class="shop-sidebar" style="display: none;">
			<div class="row">
				<?php dynamic_sidebar( 'woosidebar' ); ?>
			</div>
		</div>
	</div>
<?php endif; ?>


<?php
	/**
	 * woocommerce_archive_description hook
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );

	global $wp_query;

	$term_id = isset( $wp_query->get_queried_object()->term_id ) ? $wp_query->get_queried_object()->term_id : FALSE;

	if ( have_posts() ) :

		$post_count = 0;

		/**
		 * woocommerce_before_shop_loop hook
		 *
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );

		woocommerce_product_loop_start(); ?>

		<?php woocommerce_product_subcategories( array(
			                                         'before' => '<div class="row">',
			                                         'after'  => '</div>'
		                                         ) ); ?>

		<div class="row">

			<?php
				while ( have_posts() ) : the_post();


					$post_count ++;

					$catalogue_position        = (int) hippo_get_term_meta( $term_id, 'catalogue_position', TRUE );
					$catalogue_column          = hippo_get_term_meta( $term_id, 'catalogue_column', TRUE );
					$catalogue_grid_class_name = hippo_get_term_meta( $term_id, 'catalogue_grid', TRUE );

					$catalogue_slider = hippo_get_term_meta( $term_id, 'product_catalogue_slider', TRUE );

					// Load Contents if no position or slider choosed
					if ( ( $catalogue_position < 1 ) and ! empty( $catalogue_position ) ):
						wc_get_template_part( 'content', 'product' );
					endif;

					if ( $term_id and ( class_exists( 'RevSliderOutput' ) and ! empty( $catalogue_slider ) and ! empty( $catalogue_position ) ) and ( $post_count == $catalogue_position ) ) :

						if ( empty( $catalogue_grid_class_name ) ):
							$catalogue_grid_class_name = "col-md-{$catalogue_column}";
						endif;
						?>
						<div class="<?php echo esc_attr( $catalogue_grid_class_name ); ?>">
							<div class="catalogue-blocks catalogue-slider-wrapper">
								<?php RevSliderOutput::putSlider( $catalogue_slider ); ?>
							</div>
						</div>
						<?php

					endif; //  catalogue_position


					wc_get_template_part( 'content', 'product' );

					?>

				<?php endwhile; // end of the loop.
			?>
		</div>

		<?php woocommerce_product_loop_end(); ?>

		<?php
		/**
		 * woocommerce_after_shop_loop hook
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );
		?>

	<?php elseif ( ! woocommerce_product_subcategories( array(
		                                                    'before' => woocommerce_product_loop_start( FALSE ),
		                                                    'after'  => woocommerce_product_loop_end( FALSE )
	                                                    ) )
	) : ?>

		<?php wc_get_template( 'loop/no-products-found.php' ); ?>

	<?php endif; ?>


<?php
	/**
	 * woocommerce_after_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
?>

<?php
	/**
	 * woocommerce_sidebar hook.
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	do_action( 'woocommerce_sidebar' );
?>

<?php get_footer( 'shop' );