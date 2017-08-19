<?php

	if ( function_exists( 'vc_map' ) ) :

		//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
		add_filter( 'vc_autocomplete_hippo_products_product_post_id_callback', array(
			'Vc_Vendor_Woocommerce',
			'productIdAutocompleteSuggester'
		), 10, 1 );
		// For render : vc_autocomplete_[shortcode_name]_[param_name]_render

		add_filter( 'vc_autocomplete_hippo_products_product_post_id_render', array(
			'Vc_Vendor_Woocommerce',
			'productIdAutocompleteRender',
		), 10, 1 );


		$hippo_products_array = apply_filters( 'hippo-plugin-vc-hippo_products-map', array(
			"name"        => __( "Hippo Products", 'hippo-plugin' ),
			"base"        => "hippo_products",
			"icon"        => "fa fa-cart-plus",
			"class"       => "",
			'category'    => HIPPO_THEME_NAME . ' ' . __( 'Theme Elements', 'hippo-plugin' ),
			"description" => __( 'Display single product', 'hippo-plugin' ),
			"params"      => apply_filters( 'hippo-plugin-vc-hippo_products-params', array(
				array(
					"type"        => "dropdown",
					"heading"     => __( "Product Display style", 'hippo-plugin' ),
					"param_name"  => "product_style",
					"value"       => array(
						__( "Select a product display style", "hippo-plugin" ) => '',
						__( "Medium", "hippo-plugin" )                         => 'product-style-one',
						__( "Large", "hippo-plugin" )                          => 'product-style-two',
						__( "Medium Without Margin", "hippo-plugin" )          => 'product-style-three',
						__( "Large Without Margin", "hippo-plugin" )           => 'product-style-four',
					),
					"admin_label" => TRUE,
					"description" => __( "Select product display style", 'hippo-plugin' ),
					'std'         => 'product-style-one'
				),
				array(
					"type"        => "autocomplete",
					"admin_label" => TRUE,
					"class"       => "",
					"heading"     => __( "Select Product", 'hippo-plugin' ),
					"param_name"  => "product_post_id",
					"description" => __( "Select produtct that would you like to display", 'hippo-plugin' )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", 'hippo-plugin' ),
					"param_name"  => "el_class",
					"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'hippo-plugin' )
				)
			) )
		) );

		vc_map( $hippo_products_array );

		if ( class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode_Hippo_Products' ) ) :
			class WPBakeryShortCode_Hippo_Products extends WPBakeryShortCode {
			}
		endif; // class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode_Hippo_Products' )
	endif; // function_exists( 'vc_map' )

