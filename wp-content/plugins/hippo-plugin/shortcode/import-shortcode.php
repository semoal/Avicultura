<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	//----------------------------------------------------------------------
	// Autoloading shortcode files
	//----------------------------------------------------------------------

	if ( file_exists( EM_SHORTCODES_DIR . "/shortcodes" ) ):
		foreach ( glob( EM_SHORTCODES_DIR . "/shortcodes/*.php" ) as $filename ) :
			hippo_import_shortcode( basename( $filename ) );
		endforeach;
	endif;
