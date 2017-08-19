<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	//----------------------------------------------------------------------
	// Load the TGM Plugin Installation
	//----------------------------------------------------------------------

	require get_template_directory() . '/required-plugins/index.php';

	//----------------------------------------------------------------------
	// Load Setup Option
	//----------------------------------------------------------------------

	require get_template_directory() . "/admin/setup/index.php";

	//----------------------------------------------------------------------
	// Load Redux Extensions - MUST be loaded before your options are set
	//----------------------------------------------------------------------

	require get_template_directory() . '/admin/redux-extensions/extensions-init.php';

	//----------------------------------------------------------------------
	// Load the preset mapper
	//----------------------------------------------------------------------

	require get_template_directory() . '/admin/presets-mapper.php';

	//----------------------------------------------------------------------
	// Load the theme/plugin options
	//----------------------------------------------------------------------

	if ( ! function_exists( 'sink_theme_option_panels' ) ) :
		function sink_theme_option_panels() {
			if ( class_exists( 'Redux' ) ):
				require get_template_directory() . '/admin/options-init.php';
			endif;
		}

		add_action( 'after_setup_theme', 'sink_theme_option_panels', 99 );
	endif;