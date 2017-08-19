<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$attributes = shortcode_atts( array(
		                              'el_class' => '',
	                              ), $atts );

	ob_start();

?>
	<div class="faqs-wrapper <?php echo esc_attr( $attributes[ 'el_class' ] ); ?>">
		<?php echo wpb_js_remove_wpautop( $content ); ?>
	</div>
<?php
	echo $this->endBlockComment( $this->getShortcode() );
	echo ob_get_clean();
