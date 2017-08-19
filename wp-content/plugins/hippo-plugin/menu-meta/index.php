<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	define( 'EM_MENU_META_DIR', dirname( __FILE__ ) );
	define( 'EM_MENU_META_RELATIVE_PATH', dirname( plugin_basename( __FILE__ ) ) );
	define( 'EM_MENU_META_URL', plugins_url( basename( dirname( __FILE__ ) ), dirname( __FILE__ ) ) );


	if ( ! defined( 'DOING_AJAX' ) or ! DOING_AJAX ) {
		require_once dirname( __FILE__ ) . '/class-navmenu-item-edit-walker.php';
		require_once dirname( __FILE__ ) . '/class-navmenu-item-engine.php';
	}

	require_once dirname( __FILE__ ) . '/class-navmenu-meta.php';