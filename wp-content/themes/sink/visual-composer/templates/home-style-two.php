<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_home_style_two' );
	function hippo_vc_template_home_style_two( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Home Style Two', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/home-style-two.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_home_style_two';

		ob_start();
		?>[vc_row][vc_column][rev_slider_vc alias="Sink-1-Hero"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-6 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][vc_single_image image="833" img_size="full"][/vc_column][vc_column width="1/2" offset="vc_col-lg-6 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][vc_single_image image="837" img_size="full"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-xs-12"][hippo_products product_style="product-style-two" product_post_id="1212"][/vc_column][vc_column width="1/2"][hippo_products product_style="product-style-two" product_post_id="678"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-xs-12"][hippo_products product_style="product-style-two" product_post_id="521"][/vc_column][vc_column width="1/2"][hippo_products product_style="product-style-two" product_post_id="520"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-xs-12"][hippo_products product_style="product-style-two" product_post_id="555"][/vc_column][vc_column width="1/2"][hippo_products product_style="product-style-two" product_post_id="542"][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}