<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_home_shadow_less' );
	function hippo_vc_template_home_shadow_less( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Home Shadow Less', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/home-shadow-less.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_home_shadow_less';

		ob_start();
		?>[vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="76"][/vc_column][vc_column offset="vc_col-lg-6 vc_col-md-4 vc_hidden-sm vc_hidden-xs"][hippo_product_cats category_id="28"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="1288"][/vc_column][/vc_row][vc_row][vc_column offset="vc_hidden-lg vc_hidden-md"][hippo_product_cats category_id="28"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="341"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="342"][/vc_column][vc_column offset="vc_col-lg-6 vc_col-md-4 vc_hidden-sm vc_hidden-xs"][hippo_product_cats category_id="29"][/vc_column][/vc_row][vc_row][vc_column offset="vc_hidden-lg vc_hidden-md"][hippo_product_cats category_id="29"][/vc_column][/vc_row][vc_row][vc_column offset="vc_col-lg-6 vc_col-md-4 vc_hidden-sm vc_hidden-xs"][hippo_product_cats category_id="42"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="453"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="443"][/vc_column][/vc_row][vc_row][vc_column offset="vc_hidden-lg vc_hidden-md"][hippo_product_cats category_id="42"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="1233"][/vc_column][vc_column offset="vc_col-lg-6 vc_col-md-4 vc_hidden-sm vc_hidden-xs"][hippo_product_cats category_id="77"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-4 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_post_id="542"][/vc_column][/vc_row][vc_row][vc_column offset="vc_hidden-lg vc_hidden-md"][hippo_product_cats category_id="29"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-6 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][vc_single_image image="755" img_size="full" alignment="center"][/vc_column][vc_column width="1/2" offset="vc_col-lg-6 vc_col-md-6 vc_col-xs-12" custom_columns="col-ms-6"][rev_slider_vc alias="banner-1-500x300"][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}