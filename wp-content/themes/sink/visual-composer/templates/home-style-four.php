<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_home_style_four' );
	function hippo_vc_template_home_style_four( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Home Style Four', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/home-style-four.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_home_style_four';

		ob_start();
		?>[vc_row][vc_column][rev_slider_vc alias="sink-hero-2"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][hippo_product_cats category_id="77"][/vc_column][vc_column width="1/2"][hippo_product_cats category_id="29"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][hippo_product_cats category_id="42"][/vc_column][vc_column width="1/2"][hippo_product_cats category_id="28"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Our Latest Collection" css=".vc_custom_1445249056763{margin-top: 80px !important;margin-bottom: 30px !important;}"][/vc_column][/vc_row][vc_row][vc_column width="1/3" offset="vc_col-xs-12"][hippo_products product_post_id="678"][/vc_column][vc_column width="1/3" offset="vc_col-xs-12"][hippo_products product_post_id="216"][/vc_column][vc_column width="1/3" offset="vc_col-xs-12"][hippo_products product_post_id="447"][/vc_column][/vc_row][vc_row][vc_column width="1/3" offset="vc_col-xs-12"][hippo_products product_post_id="441"][/vc_column][vc_column width="1/3" offset="vc_col-xs-12"][hippo_products product_post_id="542"][/vc_column][vc_column width="1/3" offset="vc_col-xs-12"][hippo_products product_post_id="196"][/vc_column][/vc_row][vc_row][vc_column][vc_separator border_width="2"][/vc_column][/vc_row][vc_row][vc_column width="1/3"][vc_column_text]
		<h6 style="text-align: center;">[ma-icon icon="zmdi zmdi-phone-in-talk"] 8 (800) 230 4890 DAILY 10.00 TO 18.00</h6>
		[/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text]
		<h6 style="text-align: center;">[ma-icon icon="zmdi zmdi-globe"] FAST DELIVERY ACROSS WORLD</h6>
		[/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text]
		<h6 style="text-align: center;">[ma-icon icon="zmdi zmdi-assignment-return"] 15-DAY RETURN POLICY</h6>
		[/vc_column_text][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}