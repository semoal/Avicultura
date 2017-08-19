<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_home_style_three' );
	function hippo_vc_template_home_style_three( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Home Style Three', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/home-style-three.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_home_style_three';

		ob_start();
		?>[vc_row][vc_column][rev_slider_vc alias="Sink-1-Hero"][/vc_column][/vc_row][vc_row][vc_column width="1/2" custom_columns="col-ms-6"][vc_single_image image="837" img_size="full" onclick="custom_link" link="#"][/vc_column][vc_column width="1/2" custom_columns="col-ms-6"][vc_single_image image="833" img_size="full" onclick="custom_link" link="#"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="New Arrival Furniture" show_category_link="yes" product_category_id="28"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="76"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="93"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="221"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="218"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Trendy Bags &amp; Bagpacks" show_category_link="yes" product_category_id="77"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="556"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="521"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="678"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="542"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Limited Edition Light" show_category_link="yes" product_category_id="42"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="453"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="451"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="443"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="447"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Bath &amp; Kitchen Accessories" show_category_link="yes" product_category_id="29"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="433"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="434"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="436"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="441"][/vc_column][/vc_row][vc_row css=".vc_custom_1445208695168{margin-top: 50px !important;}"][vc_column width="1/3"][vc_column_text]
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