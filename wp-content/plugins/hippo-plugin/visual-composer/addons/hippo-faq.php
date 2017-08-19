<?php


	if ( function_exists( 'vc_map' ) ) :

		//---------------------------------------------------------------------
		// Hippo VC Faqs
		//---------------------------------------------------------------------

		$hippo_faqs_array = apply_filters( 'hippo-plugin-vc-hippo_faqs-map', array(

			"name"                    => __( "Hippo FAQ", 'hippo-plugin' ),
			"base"                    => "hippo_faqs",
			'controls'                => "full",
			"icon"                    => "fa fa-question-circle",
			"show_settings_on_create" => FALSE,
			"description"             => __( 'Show off FAQ', 'hippo-plugin' ),
			"as_parent"               => array( 'only' => 'hippo_faq' ),
			// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"content_element"         => TRUE,
			// "admin_enqueue_css" => get_template_directory_uri() . '/visual-composer/mappings/css/client-carousel.css',
			'category'                => HIPPO_THEME_NAME . ' ' . __( 'Theme Elements', 'hippo-plugin' ),
			'default_content'         => '
                [hippo_faq]Put the answer here...[/hippo_faq]
                ',
			"params"                  => apply_filters( 'hippo-plugin-vc-hippo_faqs-params', array(

				// add params same as with any other content element
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", 'hippo-plugin' ),
					"param_name"  => "el_class",
					"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'hippo-plugin' )
				)
			) ),
			"js_view"                 => 'VcColumnView',
		) );

		vc_map( $hippo_faqs_array );

		//---------------------------------------------------------------------
		// Hippo VC Faq
		//---------------------------------------------------------------------

		$hippo_faq_array = apply_filters( 'hippo-plugin-vc-hippo_faq-map', array(
			"name"                    => __( "FAQ list", 'hippo-plugin' ),
			"base"                    => "hippo_faq",
			"content_element"         => TRUE,
			"show_settings_on_create" => TRUE,
			"as_child"                => array( 'only' => 'hippo_faqs' ),
			'is_container'            => TRUE,
			// Use only|except attributes to limit parent (separate multiple values with comma)
			"params"                  => apply_filters( 'hippo-plugin-vc-hippo_faq-params', array(
				// add params same as with any other content element
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Show Icon', 'hippo-plugin' ),
					'param_name'  => 'icon_show',
					'description' => __( 'If you do not like to show icon then select no to hide', 'hippo-plugin' ),
					'value'       => array(
						__( 'Select option', 'hippo-plugin' ) => '',
						__( 'Yes', 'hippo-plugin' )           => 'yes',
						__( 'No', 'hippo-plugin' )            => 'no'
					)
				),
				array(
					'type'       => 'iconpicker',
					'heading'    => __( 'Icon', 'hippo-plugin' ),
					'param_name' => 'icon',
					'settings'   => array(
						'type' => 'fontawesome'
					),
					"dependency" => Array(
						'element' => "icon_show",
						'value'   => array( 'yes' )
					),
				),
				array(
					"type"        => "colorpicker",
					"heading"     => __( "Icon color", 'hippo-plugin' ),
					"param_name"  => "icon_color",
					"value"       => "#606676",
					"description" => __( "change icon color", 'hippo-plugin' ),
					"dependency"  => Array(
						'element' => "icon_show",
						'value'   => array( 'yes' )
					),
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Title", 'hippo-plugin' ),
					"param_name"  => "question_title",
					"admin_label" => TRUE,
					"description" => __( "Put the question", 'hippo-plugin' )
				),
				array(
					"type"        => "textarea_html",
					"heading"     => __( "Description", 'hippo-plugin' ),
					"param_name"  => "content",
					"description" => __( "Put the question answer", 'hippo-plugin' )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", 'hippo-plugin' ),
					"param_name"  => "el_class",
					"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'hippo-plugin' )
				)
			) )
		) );

		vc_map( $hippo_faq_array );

		// WPBakeryShortCode_Hippo_Faqs "container" content element extend WPBakeryShortCodesContainer class to inherit all required functionality

		if ( class_exists( 'WPBakeryShortCodesContainer' ) and ! class_exists( 'WPBakeryShortCode_Hippo_Faqs' ) ) :
			class WPBakeryShortCode_Hippo_Faqs extends WPBakeryShortCodesContainer {

			}
		endif;  // class_exists( 'WPBakeryShortCodesContainer' ) and ! class_exists( 'WPBakeryShortCode_Hippo_Faqs' )

		if ( class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode' ) ) :
			class WPBakeryShortCode_Hippo_Faq extends WPBakeryShortCode {

			}
		endif; // class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode' )

	endif; // function_exists( 'vc_map' )