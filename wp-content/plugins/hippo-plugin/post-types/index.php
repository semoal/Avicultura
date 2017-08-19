<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	/**
	 * AutoLoading Post Types
	 */
	if ( file_exists( HIPPO_PLUGIN_DIR . "/post-types/post-types/" ) ):
		foreach ( glob( HIPPO_PLUGIN_DIR . "/post-types/post-types/*.php" ) as $filename ) :
			include_once $filename;
		endforeach;
	endif;
