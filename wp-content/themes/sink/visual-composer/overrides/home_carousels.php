<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$attributes = shortcode_atts( array(
		                              'el_class' => '',
	                              ), $atts );

	ob_start();

?>
	<div class="carousel-wrapper">
		<div class="carousel hippo-carousel slide" data-ride="carousel" data-interval="5000">
			<div class="carousel-inner">
				<?php echo wpb_js_remove_wpautop( $content ); ?>
			</div>
			<!-- .carousel-inner -->

			<div class="carousel-control-wrapper">

				<a class="left carousel-control" href=".hippo-carousel" data-slide="prev">
					<i class="zmdi zmdi-chevron-up"></i>
				</a>

				<a class="right carousel-control" href=".hippo-carousel" data-slide="next">
					<i class="zmdi zmdi-chevron-down"></i>
				</a>
			</div>
			<!-- .carousel-control-wrapper -->

		</div>
	</div> <!-- .carousel-wrapper -->

<?php
	echo $this->endBlockComment( $this->getShortcode() );
	echo ob_get_clean();
