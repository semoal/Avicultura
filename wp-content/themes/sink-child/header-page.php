<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$current_page_template = basename( get_page_template_slug() );
	
	if ( is_page() and ! in_array( $current_page_template, hippo_home_page_templates() ) ) : ?>
		<div class="page-title-section">
			<div class="row">
				<div class="col-md-12">
				</div>
				<!-- /.col-## -->
			</div>
			<!-- /.row -->
		</div> <!-- .page-title-section -->
	<?php endif;

	if ( ! is_page() && ! is_404() && ! is_singular( 'product' ) ) : ?>
		<div class="page-title-section">
			<div class="row">
				<div class="col-md-12">
				</div>
				<!-- /.col-## -->
			</div>
			<!-- /.row -->
		</div> <!-- .page-title-section -->
	<?php endif;