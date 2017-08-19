<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	// Add Toolbar Menus
	function hippo_demodata_toolbar() {
		global $wp_admin_bar;

		$show_demo_data_bar = TRUE;

		if ( function_exists( 'hippo_option' ) ) {
			$show_demo_data_bar = hippo_option( 'demo-data-installer', FALSE, TRUE );
		}

		$args = array(
			'id'    => 'hippo-installer',
			'title' => sprintf( esc_html__( '%s Theme Setup Wizard', 'hippo-plugin' ), HIPPO_THEME_NAME ),
			'href'  => admin_url( sprintf( 'themes.php?page=%s-setup', strtolower( HIPPO_THEME_NAME ) ) ),
		);

		if ( ! empty( $show_demo_data_bar ) ) {
			$wp_admin_bar->add_menu( $args );
		}
	}

	// Hook into the 'wp_before_admin_bar_render' action
	add_action( 'wp_before_admin_bar_render', 'hippo_demodata_toolbar', 999 );


	//include_once dirname( __FILE__ ) . '/theme-options.php'; // smof
	include_once dirname( __FILE__ ) . '/redux-theme-option.php';
	include_once dirname( __FILE__ ) . '/widgets.php';
	include_once dirname( __FILE__ ) . '/contents.php';
	include_once dirname( __FILE__ ) . '/rev-slider.php';