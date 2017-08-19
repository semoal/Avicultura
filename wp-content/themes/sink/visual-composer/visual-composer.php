<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$_hippo_shortcode_tabs    = array();
	$_hippo_testimonials_attr = array();


	// =================================================================
	// Add Template Overwrite Path
	// =================================================================

	vc_set_shortcodes_templates_dir( locate_template( 'visual-composer/overrides' ) );

	// =================================================================
	// Visual Composer Admin element stylesheet
	// =================================================================

	if ( ! function_exists( 'hippo_vc_admin_styles' ) ) :
		function hippo_vc_admin_styles() {
			wp_enqueue_style( 'hippo_vc_admin_style', hippo_locate_template_uri( 'visual-composer/assets/css/vc-admin-element-style.css' ), array(), time(), 'all' );
		}

		add_action( 'admin_enqueue_scripts', 'hippo_vc_admin_styles' );
	endif;

	// =================================================================
	// Visual Composer Load Default Templates
	// =================================================================

	if ( ! function_exists( 'hippo_load_vc_default_templates' ) ):

		function hippo_load_vc_default_templates() {

			function hippo_remove_all_vc_default_templates( $data ) {
				return array(); // This will remove all default templates
			}

			add_filter( 'vc_load_default_templates', 'hippo_remove_all_vc_default_templates' );

			$template_dir = locate_template( 'visual-composer/templates' );

			foreach ( glob( $template_dir . "/*.php" ) as $filename ) :

				$template_name = sprintf( "visual-composer/templates/%s", basename( $filename ) );
				locate_template( $template_name, TRUE );
			endforeach;
		}

		add_action( 'wp_loaded', 'hippo_load_vc_default_templates', 9 );
	endif;

	// =================================================================
	// Change Visual Composer Shortcode Class
	// =================================================================

	if ( ! function_exists( 'hippo_change_vc_class' ) ) :

		function hippo_change_vc_class( $class_string, $tag, $attr ) {
			if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {

				//$class_string = str_ireplace( 'vc_row-fluid', '', $class_string );
				//$class_string = str_ireplace( 'vc_row', ' row vc_row', $class_string );
				//$class_string = str_ireplace( 'wpb_row', '', $class_string );
			}

			if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
				//	$class_string = str_ireplace( 'vc_col-', 'col-', $class_string );
			}

			return $class_string;
		}

		add_filter( 'vc_shortcodes_css_class', 'hippo_change_vc_class', 10, 3 );
	endif;

	// =================================================================
	// Fix for twitter bootstrap support remove some param
	// =================================================================

	vc_remove_param( "vc_row", "full_width" );
	vc_remove_param( "vc_row", "full_height" );
	vc_remove_param( "vc_row", "content_placement" );

	// =================================================================
	// Add bootstrap array
	// =================================================================

	$row_attribute = array(
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Row Style', 'sink' ),
			'param_name'  => 'row_width',
			'value'       => array(
				esc_html__( 'Fixed Width', 'sink' ) => 'container',
				//__( 'Fluid Width', 'sink' ) => 'container-fluid',
				esc_html__( 'Full Width', 'sink' )  => 'container-full'
			),
			'description' => esc_html__( 'Container width', 'sink' ),
			'std'         => 'container-full'
		)
	);

	vc_add_params( 'vc_row', apply_filters( 'hippo-vc_row-attr', $row_attribute ) );


	$column_attributes = array(

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Custom column class?', 'sink' ),
			'param_name'  => 'custom_columns',
			'description' => esc_html__( 'Add class on column like: col-ms-6, hidden-ms', 'sink' ),
			'group'       => esc_html__( 'Custom column', 'sink' )
		),
		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Add Clear fix after this column?', 'sink' ),
			'param_name'  => 'active_clearfix',
			'description' => esc_html__( 'If checked, a div appended after this column with clearfix class', 'sink' ),
			'value'       => array( esc_html__( 'Yes', 'sink' ) => 'yes' ),
			'group'       => esc_html__( 'Clear Columns', 'sink' )
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Clear fix visibility class(es):', 'sink' ),
			'param_name'  => 'clear_fix_classes',
			'description' => wp_kses( __( 'Clearfix div activated on this class like: <code>visible-sm-block</code> or <code>visible-xs-inline</code>. <br>Available <code>visible-*-block</code> <code>visible-*-inline-block</code> <code>visible-*-inline</code>). Use multiple with space.', 'sink' ), array(
				'code' => array(),
				'br'   => array()
			) ),
			'dependency'  => array(
				'element' => 'active_clearfix',
				'value'   => 'yes',
			),
			'group'       => esc_html__( 'Clear Columns', 'sink' )
		)
	);

	vc_add_params( 'vc_column', apply_filters( 'hippo-vc_column-attr', $column_attributes ) );
	vc_add_params( 'vc_column_inner', apply_filters( 'hippo-vc_column-attr', apply_filters( 'hippo-vc_column_inner-attr', $column_attributes ) ) );

	// =================================================================
	// Add Text Separator Params
	// =================================================================

	if ( ! function_exists( 'hippo_vc_text_separator_params' ) ) :


		function hippo_vc_text_separator_params() {

			$category_list = array();

			$category_list[ __( 'Select a product category', 'sink' ) ] = '';

			foreach ( get_terms( 'product_cat' ) as $term ) :
				$category_list [ $term->name ] = $term->term_id;
			endforeach;

			$text_separator_attributes = array(

				array(
					'type'        => 'checkbox',
					'heading'     => esc_html__( 'Add Product Category Link', 'sink' ),
					'param_name'  => 'show_category_link',
					'description' => esc_html__( 'If checked, you can choose a product category to show as link.', 'sink' ),
					'value'       => array( esc_html__( 'Yes', 'sink' ) => 'yes' ),
					'group'       => esc_html__( 'Product Category Link', 'sink' )
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Choose a product category', 'sink' ),
					'param_name'  => 'product_category_id',
					'description' => esc_html__( 'Choose a product category to display category link.', 'sink' ),
					'dependency'  => array(
						'element' => 'show_category_link',
						'value'   => 'yes',
					),
					'value'       => $category_list,
					'group'       => esc_html__( 'Product Category Link', 'sink' )
				)
			);

			vc_add_params( 'vc_text_separator', apply_filters( 'hippo-vc_text_separator-attr', $text_separator_attributes ) );

		}

		add_action( 'wp_loaded', 'hippo_vc_text_separator_params' );

	endif;

	// =================================================================
	//  Visual Composer Frontend CSS Override
	// =================================================================

	if ( ! function_exists( 'visual_composer_css_override' ) ):

		function visual_composer_css_override() {
			wp_enqueue_style( 'js_composer_front-override', hippo_locate_template_uri( 'css/js_composer-override.css' ), FALSE, '', 'all' );
		}
	endif;

	if ( ! function_exists( 'visual_composer_register_front_css' ) ):

		function visual_composer_register_front_css() {
			add_action( 'wp_enqueue_scripts', 'visual_composer_css_override' );
		}

		add_action( 'vc_base_register_front_css', 'visual_composer_register_front_css' );
	endif;