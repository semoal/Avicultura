<?php

	//----------------------------------------------------------------------
	// Add theme support for Infinite Scroll.
	// See: http://jetpack.me/support/infinite-scroll/
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_jetpack_setup' ) ) :
		function hippo_jetpack_setup() {
			add_theme_support( 'infinite-scroll', apply_filters( 'hippo_jetpack_infinite_scroll_options', array(
				'container'      => 'main',
				'posts_per_page' => get_option( 'posts_per_page' ),
			) ) );
		}

		add_action( 'after_setup_theme', 'hippo_jetpack_setup' );
	endif;