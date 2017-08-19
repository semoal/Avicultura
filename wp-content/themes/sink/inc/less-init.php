<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	/**
	 * Adding Less
	 */
	// add_action( 'wp_enqueue_scripts', function () {
	// 	wp_enqueue_style( 'style-less', get_stylesheet_directory_uri() . '/less/style.less' );
	// } );

	//----------------------------------------------------------------------
	// Less CSS Variables
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_less_variables' ) ) :

		function hippo_less_variables( $arr ) {

			$preset = hippo_option_get_preset( '-' );

			$layout_type    = hippo_option( 'layout-type', FALSE, 'full-width' );
			$theme_color    = hippo_option( $preset . 'theme-color', FALSE, '#512da8' );
			$bgcolor        = hippo_option( $preset . 'body-background-color', FALSE, '#ffffff' );
			$contents_color = hippo_option( $preset . 'contents-color', FALSE, '#7f7f7f' );
			$menu_color     = hippo_option( $preset . 'menu-color', FALSE, '#313131' );
			$headings_color = hippo_option( $preset . 'headings-color', FALSE, '#313131' );
			$links_color    = hippo_option( $preset . 'links-color', FALSE, '#ff4081' );
			$hover_color    = hippo_option( $preset . 'hover-color', FALSE, '#c0c0c0' );

			// body typography
			$font_family = hippo_option( 'body-typography', 'font-family' );
			$font_weight = hippo_option( 'body-typography', 'font-weight' );

			// headinng typography
			$hfont_family = hippo_option( 'heading-typography', 'font-family' );
			$hfont_weight = hippo_option( 'heading-typography', 'font-weight' );

			$arr[ 'theme-color' ]   = $theme_color;
			$arr[ 'bg-color' ]      = $bgcolor;
			$arr[ 'content-color' ] = $contents_color;
			$arr[ 'menu-color' ]    = $menu_color;
			$arr[ 'heading-color' ] = $headings_color;
			$arr[ 'link-color' ]    = $links_color;
			$arr[ 'hover-color' ]   = $hover_color;

			// body typography
			$arr[ 'font-family' ] = $font_family;
			$arr[ 'font-weight' ] = $font_weight;

			// heading typography
			$arr[ 'heading-font-family' ] = $hfont_family;
			$arr[ 'heading-font-weight' ] = $hfont_weight;

			return apply_filters( 'hippo_less_variables', $arr );
		}

		add_filter( 'hippo_set_less_variables', 'hippo_less_variables' );

	endif;