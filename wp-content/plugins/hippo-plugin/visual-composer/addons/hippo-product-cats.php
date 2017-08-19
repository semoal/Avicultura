<?php


	if ( function_exists( 'vc_map' ) ) :

		$category_list = array();

		$category_list[ __( 'Select a product category', 'hippo-plugin' ) ] = '';

		foreach ( get_terms( 'product_cat' ) as $term ) :
			$category_list [ $term->name ] = $term->term_id;
		endforeach;

		$hippo_product_category_array = apply_filters( 'hippo-plugin-vc-hippo_product_cats-map', array(
			"name"        => __( "Hippo Product Category", 'hippo-plugin' ),
			"base"        => "hippo_product_cats",
			"icon"        => "fa fa-folder",
			'category'    => HIPPO_THEME_NAME . ' ' . __( 'Theme Elements', 'hippo-plugin' ),
			"description" => __( 'For showing product category', 'hippo-plugin' ),
			"params"      => apply_filters( 'hippo-plugin-vc-hippo_product_cats-params', array(
				array(
					"type"        => "dropdown",
					"value"       => $category_list,
					"heading"     => __( "Select Category", 'hippo-plugin' ),
					"param_name"  => "category_id",
					"admin_label" => TRUE,
					"description" => __( "Select category to show category post", 'hippo-plugin' )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", 'hippo-plugin' ),
					"param_name"  => "el_class",
					"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'hippo-plugin' )
				)
			) )
		) );

		vc_map( $hippo_product_category_array );


		if ( class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode_Hippo_Product_Cats' ) ) :
			class WPBakeryShortCode_Hippo_Product_Cats extends WPBakeryShortCode {
			}
		endif; // class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode_Hippo_Product_Cats' )

	endif; // function_exists( 'vc_map' )