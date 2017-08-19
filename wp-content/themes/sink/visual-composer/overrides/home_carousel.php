<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$attributes = shortcode_atts( array(
		                              'active_item'           => '',
		                              'images'                => '',
		                              'title'                 => '',
		                              'title_animation'       => '',
		                              'title_animation_delay' => '',
		                              'link_visibility'       => '',
		                              'link_text'             => 'View Details',
		                              'link'                  => '',
		                              'link_animation'        => '',
		                              'link_animation_delay'  => '',
		                              'el_class'              => ''
	                              ), $atts );

	ob_start();

	$link     = vc_build_link( $attributes[ 'link' ] );
	$a_href   = $link[ 'url' ];
	$a_title  = $link[ 'title' ];
	$a_target = trim( $link[ 'target' ] );


?>
	<div class="item <?php echo esc_attr( $attributes[ 'active_item' ] ); ?>">
		<?php if ( $attributes[ 'images' ] ) : ?>
			<?php $image_attributes = wp_get_attachment_image_src( $attributes[ 'images' ], 'hippo-home-carousel' ); ?>
			<img src="<?php echo esc_url( $image_attributes[ 0 ] ); ?>"
			     alt="<?php echo esc_attr( $attributes[ 'title' ] ); ?>"/>
		<?php endif; ?>

		<div class="carousel-text">
			<?php if ( $attributes[ 'title' ] ) : ?>
				<h2 class="animated <?php echo esc_attr( $attributes[ 'title_animation' ] . ' ' . $attributes[ 'title_animation_delay' ] ); ?> carousel-subtitle"><?php echo esc_html( $attributes[ 'title' ] ); ?></h2>
			<?php endif; ?>

			<?php if ( $attributes[ 'link_visibility' ] == 'visible' ) : ?>
				<a class="animated <?php echo esc_attr( $attributes[ 'link_animation' ] . ' ' . $attributes[ 'link_animation_delay' ] ); ?>"
				   href="<?php echo esc_url( $a_href ); ?>"><?php echo esc_html( $attributes[ 'link_text' ] ); ?> <i
						class="zmdi zmdi-long-arrow-right"></i></a>
			<?php endif; ?>
		</div>
		<!-- /.carousel-text -->
	</div><!-- .item -->
<?php
	echo $this->endBlockComment( $this->getShortcode() );
	echo ob_get_clean();