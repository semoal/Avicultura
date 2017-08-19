<?php


	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	$attributes = shortcode_atts( array(
		                              'category_id' => '',
		                              'el_class'    => ''
	                              ), $atts );

	$term_ids = explode( ',', $attributes[ 'category_id' ] );

	ob_start();

	foreach ( $term_ids as $term_id ) :

		$term = get_term( $term_id, 'product_cat' );

		if ( is_null( $term ) ) {
			continue;
		}

		$img_id     = get_term_meta( $term_id, 'thumbnail_id', TRUE );
		$img_src    = wp_get_attachment_image_src( $img_id, 'shop_catalog' );
		$css_styles = sprintf( 'background-image:url(%s)', esc_url( $img_src[ 0 ] ) );

		if ( ! $img_src ) :
			$css_styles = sprintf( 'background-image:url(%s)', esc_url( wc_placeholder_img_src() ) );
		endif;
		?>
		<article class="flex-box product-category-block <?php echo esc_attr( $attributes[ 'el_class' ] ); ?>"
		         style="<?php echo esc_attr( $css_styles ) ?>">

			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="bg-overlay"></a>

			<div class="entry-footer center-block">
				<h2><?php echo esc_html( $term->name ); ?></h2> <br>
				<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo apply_filters( 'hippo-product-category-block-find-more-text', esc_html__( 'Find out More', 'sink' ) ); ?>
					<i class="zmdi zmdi-long-arrow-right"></i>
				</a>
			</div>
		</article> <!-- .product-category-block -->

	<?php endforeach;

	echo $this->endBlockComment( $this->getShortcode() );

	echo ob_get_clean();