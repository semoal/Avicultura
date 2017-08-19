<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( class_exists( 'Vc_Manager' ) ) :

		//---------------------------------------------------------------------
		// Load Material Icon Assets
		//---------------------------------------------------------------------

		if ( ! function_exists( 'hippo_vc_iconpicker_editor_customicon_jscss' ) ):

			function hippo_vc_iconpicker_editor_customicon_jscss() {
				wp_enqueue_style( 'hippo-material-design-icons' );
			}

			add_action( 'vc_backend_editor_enqueue_js_css', 'hippo_vc_iconpicker_editor_customicon_jscss' );
			add_action( 'vc_frontend_editor_enqueue_js_css', 'hippo_vc_iconpicker_editor_customicon_jscss' );

		endif; // function_exists( 'hippo_vc_iconpicker_editor_customicon_jscss' )

		if ( ! function_exists( 'hippo_vc_iconpicker_type_custom_icon' ) ):
			function hippo_vc_iconpicker_type_custom_icon( $icons ) {

				$material_icon     = hippo_material_icons();
				$material_icon_arr = array();

				foreach ( $material_icon as $key => $name ) {
					$material_icon_arr[] = array( $key => $name );
				}

				return array_merge( $icons, $material_icon_arr );
			}

			add_filter( 'vc_iconpicker-type-material-icon', 'hippo_vc_iconpicker_type_custom_icon' );
		endif; // function_exists( 'hippo_vc_iconpicker_type_custom_icon' )

		if ( ! function_exists( 'hippo_vc_iconpicker_base_register_customicon_css' ) ):

			function hippo_vc_iconpicker_base_register_customicon_css() {
				wp_register_style( 'hippo-material-design-icons', HIPPO_PLUGIN_URL . 'css/material-design-iconic-font.min.css', FALSE, WPB_VC_VERSION, 'screen' );
			}

			add_action( 'vc_base_register_front_css', 'hippo_vc_iconpicker_base_register_customicon_css' );
			add_action( 'vc_base_register_admin_css', 'hippo_vc_iconpicker_base_register_customicon_css' );
		endif; // function_exists( 'hippo_vc_iconpicker_base_register_customicon_css' )

		//---------------------------------------------------------------------
		// Load AddOns Files
		//---------------------------------------------------------------------

		if ( ! function_exists( 'hippo_visual_composer_addons' ) ):

			function hippo_visual_composer_addons() {

				// Load AddOns helper files
				if ( file_exists( HIPPO_PLUGIN_DIR . "/visual-composer/includes" ) ):
					foreach ( glob( HIPPO_PLUGIN_DIR . "/visual-composer/includes/*.php" ) as $filename ) :
						include_once $filename;
					endforeach;
				endif;

				// Load AddOns files
				if ( function_exists( 'vc_map' ) ) :
					if ( file_exists( HIPPO_PLUGIN_DIR . "/visual-composer/addons" ) ):
						foreach ( glob( HIPPO_PLUGIN_DIR . "/visual-composer/addons/*.php" ) as $filename ) :
							include_once $filename;
						endforeach;
					endif;
				endif;
			}

			add_action( 'wp_loaded', 'hippo_visual_composer_addons', 10 ); //Show our custom shortcodes first in the list
		endif; // function_exists( 'hippo_visual_composer_addons' )

	endif;  // class_exists( 'Vc_Manager' )