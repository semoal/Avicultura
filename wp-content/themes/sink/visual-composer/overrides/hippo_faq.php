<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$attributes = shortcode_atts( array(
		                              'icon_show'      => '',
		                              'icon'           => '',
		                              'icon_color'     => '#606676',
		                              'question_title' => '',
		                              'content'        => '',
		                              'el_class'       => ''
	                              ), $atts );

	ob_start();
?>
	<div class="faq-list <?php echo esc_attr( $attributes[ 'el_class' ] ); ?>">

		<div class="media">
			<?php if ( $attributes[ 'icon_show' ] == 'yes' ) : ?>
				<div class="media-left">
					<i class="fa <?php echo esc_attr( $attributes[ 'icon' ] ); ?>"
					   style="color: <?php echo esc_attr( $attributes[ 'icon_color' ] ); ?>"></i>
				</div>
			<?php endif; ?>

			<div class="media-body">
				<?php if ( $attributes[ 'question_title' ] ) : ?>
					<h3 class="media-heading"><?php echo esc_html( $attributes[ 'question_title' ] ); ?></h3>
				<?php endif; ?>

				<?php echo wpb_js_remove_wpautop( $content, TRUE ); ?>
			</div>
		</div>
	</div>
<?php
	echo $this->endBlockComment( $this->getShortcode() );
	echo ob_get_clean();