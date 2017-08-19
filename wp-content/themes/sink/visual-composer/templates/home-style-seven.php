<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_home_style_seven' );
	function hippo_vc_template_home_style_seven( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Home Style Three', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/home-style-seven.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_home_style_seven';

		ob_start();
		?>[vc_row][vc_column][vc_column_text css=".vc_custom_1445391141999{margin-bottom: 0px !important;}"][rev_slider alias="Sink-1-Hero"][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column offset="vc_col-lg-8 vc_col-md-12 vc_col-xs-12" custom_columns="col-ms-12"][vc_single_image image="1175" img_size="full" alignment="center" onclick="custom_link" css=".vc_custom_1445394576726{margin-bottom: 0px !important;}" link="#"][/vc_column][vc_column offset="vc_col-lg-4 vc_col-md-12 vc_col-xs-12" custom_columns="col-ms-12"][vc_column_text css=".vc_custom_1445394367898{margin-bottom: 0px !important;padding-top: 70px !important;padding-right: 40px !important;padding-bottom: 30px !important;padding-left: 40px !important;}"]
		<h4 style="text-align: center;"><strong>GET A NEW BAG!</strong></h4>
		<p style="text-align: center;">Globally monetize unique collaboration and idea-sharing vis-a-vis fully researched deliverables. Synergistically.</p>
		&nbsp;
		<p style="text-align: center;"><a class="btn btn-primary" href="#">View More Collection</a></p>
		[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column offset="vc_col-lg-4 vc_col-md-12 vc_col-xs-12" custom_columns="col-ms-12"][vc_column_text css=".vc_custom_1445395775092{margin-bottom: 0px !important;padding-top: 70px !important;padding-right: 40px !important;padding-bottom: 30px !important;padding-left: 40px !important;}"]
		<h4 style="text-align: center;"><strong>OUR MISSION</strong></h4>
		<p style="text-align: center;">Uniquely e-enable user friendly e-tailers before enabled supply chains. Interactively formulate.</p>
		&nbsp;
		<p style="text-align: center;"><a class="btn btn-primary" href="#">Read More</a></p>
		[/vc_column_text][/vc_column][vc_column offset="vc_col-lg-8 vc_col-md-12 vc_col-xs-12" custom_columns="col-ms-12"][vc_single_image image="1180" img_size="full" alignment="center" onclick="custom_link" css=".vc_custom_1445395559768{margin-bottom: 0px !important;}" link="#"][/vc_column][/vc_row][vc_row][vc_column css=".vc_custom_1445397052808{padding-top: 50px !important;padding-bottom: 30px !important;background-color: #f9f9f9 !important;}"][vc_column_text css=".vc_custom_1445397314683{margin-bottom: 0px !important;}"]
		<h2 style="text-align: center;">FALL 2015</h2>
		[/vc_column_text][vc_column_text css=".vc_custom_1445397468413{margin-top: 0px !important;margin-bottom: 0px !important;}"]
		<p style="text-align: center;">Available Now</p>
		[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="679"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="640"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="638"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns=" col-ms-6"][hippo_products product_style="product-style-three" product_post_id="521"][/vc_column][/vc_row][vc_row][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="556"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="520"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns="col-ms-6"][hippo_products product_style="product-style-three" product_post_id="678"][/vc_column][vc_column width="1/2" offset="vc_col-lg-3 vc_col-md-3 vc_col-xs-12" custom_columns=" col-ms-6"][hippo_products product_style="product-style-three" product_post_id="542"][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}