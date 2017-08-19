<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	//----------------------------------------------------------------------
	// Post Views
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_get_post_views' ) ) :

		function hippo_get_post_views() {
			global $post;
			$count_key = 'hippo_post_views_count';
			$count     = get_post_meta( $post->ID, $count_key, TRUE );
			if ( $count == '' ) {
				delete_post_meta( $post->ID, $count_key );
				add_post_meta( $post->ID, $count_key, '0' );

				return number_format_i18n( "0" );
			}

			return number_format_i18n( $count );
		}
	endif; // function_exists( 'hippo_get_post_views' )

	if ( ! function_exists( 'hippo_set_post_views' ) ) :
		function hippo_set_post_views() {
			global $post;

			$count_key = 'hippo_post_views_count';
			$count     = get_post_meta( $post->ID, $count_key, TRUE );
			if ( $count == '' ) {
				delete_post_meta( $post->ID, $count_key );
				add_post_meta( $post->ID, $count_key, '0' );
			} else {
				$count ++;
				update_post_meta( $post->ID, $count_key, $count );
			}
		}
	endif; // function_exists( 'hippo_set_post_views' )
