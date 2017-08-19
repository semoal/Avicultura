<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_home_style_five' );
	function hippo_vc_template_home_style_five( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Home Style Five', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/home-style-five.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_home_style_five';

		ob_start();
		?>[vc_row][vc_column offset="vc_hidden-lg vc_hidden-md vc_col-xs-12" custom_columns="col-ms-12"][vc_column_text css=".vc_custom_1445335418700{margin-top: 0px !important;margin-bottom: 0px !important;}"][rev_slider alias="banner-1-585x360"][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="76"][/vc_column][vc_column width="1/2" offset="vc_col-lg-6 vc_hidden-sm vc_col-xs-12 vc_hidden-xs"][vc_column_text css=".vc_custom_1445335426903{margin-top: 0px !important;margin-bottom: 0px !important;}"][rev_slider alias="banner-1-585x360"][/vc_column_text][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="196"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="219"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="93"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="221"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="218"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="216"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="123"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="106"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns=" col-ms-6"][hippo_products product_style="product-style-three" product_post_id="220"][/vc_column][/vc_row][vc_row css=".vc_custom_1445208695168{margin-top: 50px !important;}"][vc_column width="1/3"][vc_column_text]
		<h5 style="text-align: center;">WORLDWIDE EXPRESS SHIPPING
			<small>Delivery within 7 days</small></h5>
		[/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text]
		<h5 style="text-align: center;">FREE DOMESTIC DELIVERY
			<small>On orders over $ 50</small></h5>
		[/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text]
		<h5 style="text-align: center;">GIFT CARDS &amp; WRAPPING
			<small>The perfect way to bring a smile</small></h5>
		[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_separator css=".vc_custom_1445210222357{margin-top: 0px !important;margin-bottom: 35px !important;}"][/vc_column][/vc_row][vc_row][vc_column][vc_single_image image="867" img_size="full" alignment="center"][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}