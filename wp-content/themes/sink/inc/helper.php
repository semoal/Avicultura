<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	//----------------------------------------------------------------------
	// Row Session
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_start_session' ) ):
		function hippo_start_session() {

			if ( function_exists( 'hippo_plugin_start_session' ) ) {
				return;
			}

			if ( function_exists( 'session_start' ) && function_exists( 'session_id' ) && ! session_id() ) {
				session_start();
			}
		}

		add_action( 'init', 'hippo_start_session', 1 );
	endif;

	if ( ! function_exists( 'hippo_set_session' ) ):
		function hippo_set_session( $name, $value ) {

			if ( function_exists( 'hippo_plugin_set_session' ) ) {
				return hippo_plugin_set_session( $name, $value );
			}

			if ( ! function_exists( 'session_start' ) || ! function_exists( 'session_id' ) ) {
				return FALSE;
			}

			if ( ! isset( $_SESSION ) ) {
				return FALSE;
			}

			$_SESSION[ $name ] = $value;

			return TRUE;
		}
	endif;

	if ( ! function_exists( 'hippo_get_session' ) ):
		function hippo_get_session( $name ) {

			if ( function_exists( 'hippo_plugin_get_session' ) ) {
				return hippo_plugin_get_session( $name );
			}

			if ( ! isset( $_SESSION ) || ! isset( $_SESSION[ $name ] ) ) {
				return FALSE;
			}

			return ! empty( $_SESSION[ $name ] ) ? trim( $_SESSION[ $name ] ) : FALSE;
		}
	endif;

	if ( ! function_exists( 'hippo_delete_session' ) ):
		function hippo_delete_session( $name ) {

			if ( function_exists( 'hippo_plugin_delete_session' ) ) {
				return hippo_plugin_delete_session( $name );
			}

			if ( ! isset( $_SESSION ) ) {
				return TRUE;
			}

			unset( $_SESSION[ $name ] );

			return TRUE;
		}
	endif;

	//----------------------------------------------------------------------
	// Cookie
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_set_cookie' ) ):
		function hippo_set_cookie( $name, $value, $expire = FALSE, $secure = FALSE ) {

			if ( function_exists( 'hippo_plugin_set_cookie' ) ) {
				return hippo_plugin_set_cookie( $name, $value, $expire, $secure );
			}

			if ( empty( $expire ) ) {
				$expire = time() + HOUR_IN_SECONDS;
			}

			if ( ! headers_sent() ) {
				return setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure );

			} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				headers_sent( $file, $line );
				trigger_error( "{$name} session cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
			}

			return FALSE;
		}
	endif;

	if ( ! function_exists( 'hippo_get_cookie' ) ):
		function hippo_get_cookie( $name ) {

			if ( function_exists( 'hippo_plugin_get_cookie' ) ) {
				return hippo_plugin_get_cookie( $name );
			}

			return ! empty( $_COOKIE[ $name ] ) ? trim( $_COOKIE[ $name ] ) : FALSE;
		}
	endif;

	if ( ! function_exists( 'hippo_delete_cookie' ) ):
		function hippo_delete_cookie( $name ) {

			if ( function_exists( 'hippo_plugin_delete_cookie' ) ) {
				return hippo_plugin_delete_cookie( $name );
			}

			unset( $_COOKIE[ $name ] );
			hippo_set_cookie( $name, NULL, time() - YEAR_IN_SECONDS );

			return TRUE;
		}
	endif;

	//----------------------------------------------------------------------
	// WooCommerce Session, use init / wp_loaded hook
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_set_wc_session' ) ):
		function hippo_set_wc_session( $name, $value ) {

			if ( function_exists( 'hippo_plugin_set_wc_session' ) ) {
				return hippo_plugin_set_wc_session( $name, $value );
			}

			// Fallback

			if ( ! did_action( 'woocommerce_init' ) ) {
				_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before woocommerce_init.', 'woocommerce' ), '2.6' );

				return FALSE;
			}

			if ( ! isset( WC()->session ) ) {
				return FALSE;
			}

			WC()->session->set( $name, $value );

			return TRUE;

		}
	endif;

	if ( ! function_exists( 'hippo_get_wc_session' ) ):
		function hippo_get_wc_session( $name ) {

			if ( function_exists( 'hippo_plugin_get_wc_session' ) ) {
				return hippo_plugin_get_wc_session( $name );
			}

			// Fallback

			if ( ! did_action( 'woocommerce_init' ) ) {
				_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before woocommerce_init.', 'woocommerce' ), '2.3' );

				return FALSE;
			}

			if ( ! isset( WC()->session ) ) {
				return FALSE;
			}

			return WC()->session->get( $name );
		}
	endif;

	if ( ! function_exists( 'hippo_delete_wc_session' ) ):
		function hippo_delete_wc_session( $name ) {

			if ( function_exists( 'hippo_plugin_delete_wc_session' ) ) {
				return hippo_plugin_delete_wc_session( $name );
			}

			// Fallback

			if ( ! did_action( 'woocommerce_init' ) ) {
				_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before woocommerce_init.', 'woocommerce' ), '2.3' );

				return FALSE;
			}

			if ( ! isset( WC()->session ) ) {
				return FALSE;
			}

			WC()->session->set( $name, NULL );

			return TRUE;
		}
	endif;

	//----------------------------------------------------------------------
	// Theme Option Name
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_option_name' ) ):
		function hippo_option_name() {
			return apply_filters( 'hippo_theme_option_name', 'hippo_theme_option' );
		}
	endif;

	//----------------------------------------------------------------------
	// Getting Theme Option data
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_option' ) ):
		function hippo_option( $index = FALSE, $index2 = FALSE, $default = NULL ) {

			$hippo_theme_option_name = hippo_option_name();

			if ( ! isset( $GLOBALS[ $hippo_theme_option_name ] ) ) {
				return $default;
			}

			$hippo_theme_option = $GLOBALS[ $hippo_theme_option_name ];


			if ( empty( $index ) ) {
				return $hippo_theme_option;
			}

			if ( $index2 ) {
				$result = ( isset( $hippo_theme_option[ $index ] ) and isset( $hippo_theme_option[ $index ][ $index2 ] ) ) ? $hippo_theme_option[ $index ][ $index2 ] : $default;
			} else {
				$result = isset( $hippo_theme_option[ $index ] ) ? $hippo_theme_option[ $index ] : $default;
			}

			if ( $result == '1' or $result == '0' ) {
				return $result;
			}

			if ( is_string( $result ) and empty( $result ) ) {
				return $default;
			}

			return $result;
		}
	endif;

	//----------------------------------------------------------------------
	// Associative array to html attribute conversion
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_array2attr' ) ):
		function hippo_array2attr( $attr, $filter = '' ) {
			$attr = wp_parse_args( $attr, array() );
			if ( $filter ) {
				$attr = apply_filters( $filter, $attr );
			}
			$html = '';
			foreach ( $attr as $name => $value ) {
				$html .= " $name=" . '"' . $value . '"';
			}

			return $html;
		}
	endif;

	//----------------------------------------------------------------------
	// OffCanvas Inner Pusher Styles
	//----------------------------------------------------------------------

	if ( ! function_exists( 'offCanvas_On_InnerPusher' ) ):
		function offCanvas_On_InnerPusher( $animation_style ) {

			$inner_pusher_list = apply_filters( 'hippo_off_canvas_inner_pusher_animation_name', array(
				'push-down',
				'rotate-pusher',
				'three-d-rotate-in',
				'three-d-rotate-out',
				'delayed-three-d-rotate'
			) );

			return in_array( $animation_style, $inner_pusher_list );
		}
	endif;

	//----------------------------------------------------------------------
	// Convert hexdec color string to rgb(a) string
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_hex2rgba' ) ):
		function hippo_hex2rgba( $color, $opacity = FALSE ) {

			$default = 'rgb(0,0,0)';

			//Return default if no color provided
			if ( empty( $color ) ) {
				return $default;
			}

			//Sanitize $color if "#" is provided
			if ( $color[ 0 ] == '#' ) {
				$color = substr( $color, 1 );
			}

			//Check if color has 6 or 3 characters and get values
			if ( strlen( $color ) == 6 ) {
				$hex = array( $color[ 0 ] . $color[ 1 ], $color[ 2 ] . $color[ 3 ], $color[ 4 ] . $color[ 5 ] );
			} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[ 0 ] . $color[ 0 ], $color[ 1 ] . $color[ 1 ], $color[ 2 ] . $color[ 2 ] );
			} else {
				return $default;
			}

			//Convert hexadec to rgb
			$rgb = array_map( 'hexdec', $hex );

			//Check if opacity is set(rgba or rgb)
			if ( $opacity ) {
				if ( abs( $opacity ) > 1 ) {
					$opacity = 1.0;
				}
				$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
			} else {
				$output = 'rgb(' . implode( ",", $rgb ) . ')';
			}

			return $output;
		}
	endif;

	//----------------------------------------------------------------------
	// WPML language selector
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_wpml_language_selector' ) ):
		function hippo_wpml_language_selector() {

			if ( ! function_exists( 'wpml_get_active_languages_filter' ) ) :
				return FALSE;
			endif;

			get_template_part( 'template-parts/wpml-language-selector' );
		}
	endif;

	//----------------------------------------------------------------------
	// Check And return File URI
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_locate_template_uri' ) ):
		function hippo_locate_template_uri( $template_names ) {
			$located = '';
			foreach ( (array) $template_names as $template_name ) {
				if ( ! $template_name ) {
					continue;
				}
				if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $template_name ) ) {
					$located = trailingslashit( get_stylesheet_directory_uri() ) . $template_name;
					break;
				} elseif ( file_exists( trailingslashit( get_template_directory() ) . $template_name ) ) {
					$located = trailingslashit( get_template_directory_uri() ) . $template_name;
					break;
				}
			}

			return $located;
		}
	endif;

	//----------------------------------------------------------------------
	// Get Theme Preset
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_option_get_preset' ) ):
		function hippo_option_get_preset( $suffix = '' ) {

			$default_option = hippo_option( 'preset', FALSE, 'preset1' );

			if ( ! function_exists( 'hippo_get_session' ) ) {
				return apply_filters( 'hippo_preset', $default_option ) . $suffix;
			}

			if ( ! apply_filters( 'hippo_can_change_preset_on_fly', '__return_true' ) ) {
				return apply_filters( 'hippo_preset', $default_option ) . $suffix;
			}

			$required = hippo_get_session( '_hippo_preset' );

			if ( ! empty( $required ) ) {
				$current = $required;
			} else {
				$current = $default_option;
			}

			return apply_filters( 'hippo_preset', $current ) . $suffix;
		}
	endif;

	//----------------------------------------------------------------------
	// Get Header Style
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_option_get_header_style' ) ):
		function hippo_option_get_header_style() {

			$default_option = hippo_option( 'header-style', FALSE, 'header-style-one' );

			if ( ! function_exists( 'hippo_set_session' ) ) {
				return apply_filters( 'hippo_header_style', $default_option );
			}

			if ( ! apply_filters( 'hippo_can_change_header_style_on_fly', '__return_true' ) ) {
				return apply_filters( 'hippo_header_style', $default_option );
			}

			$required = hippo_get_session( '_hippo_header_style' );

			if ( ! empty( $required ) ) {
				$current = $required;
			} else {
				$current = $default_option;
			}


			return apply_filters( 'hippo_header_style', $current );
		}
	endif;

	//----------------------------------------------------------------------
	// Get Header Background Style
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_option_get_header_background_style' ) ):
		function hippo_option_get_header_background_style() {

			$default_option = hippo_option( 'header-background-style', FALSE, 'bg-style-five' );

			if ( ! function_exists( 'hippo_get_session' ) ) {
				return apply_filters( 'hippo_header_background_style', $default_option );
			}

			if ( ! apply_filters( 'hippo_can_change_header_background_style_on_fly', '__return_true' ) ) {
				return $default_option;
			}

			$required = hippo_get_session( '_hippo_header_bg_style' );

			if ( ! empty( $required ) ) {
				$current = $required;
			} else {
				$current = $default_option;
			}

			return apply_filters( 'hippo_header_background_style', $current );
		}
	endif;


	//----------------------------------------------------------------------
	// Remove Redux NewsFlash
	//----------------------------------------------------------------------

	if ( ! class_exists( 'reduxNewsflash' ) ):
		class reduxNewsflash {
			public function __construct( $parent, $params ) {

			}
		}
	endif;

	//----------------------------------------------------------------------
	// Remove Redux Ads
	//----------------------------------------------------------------------

	add_filter( 'redux/' . hippo_option_name() . '/aURL_filter', '__return_empty_string' );

	/**
	 * Get Hook Info, basically action hook, attached functions called etc.
	 * @example:
	 *         <code>
	 *         <?php
	 *
	 *         hippo_theme_hook_info('action_name');
	 *
	 *         do_action('action_name');
	 *         ?>
	 *         </code>
	 *
	 * @param $hook_name
	 *
	 * @return void
	 */

	function hippo_theme_hook_info( $hook_name ) {
		global $wp_filter;

		$docs = array();

		echo '<pre>';
		echo "\t# Hook Name \"" . $hook_name . "\"";
		echo "\n\n";
		if ( isset( $wp_filter[ $hook_name ] ) ) {
			foreach ( $wp_filter[ $hook_name ] as $pri => $fn ) {

				foreach ( $fn as $fnname => $fnargs ) {
					$reflFunc = new ReflectionFunction( $fnargs[ 'function' ] );
					echo "\t - " . 'Function "' . $fnargs[ 'function' ] . "\" priority " . $pri . ". \n\tin file " . str_ireplace( ABSPATH, '', $reflFunc->getFileName() ) . '#' . $reflFunc->getStartLine();
					echo "\n\n";
					$docs[] = array( $fnargs[ 'function' ], $pri );
				}
			}

			echo "\tAction Hook Commenting\n\t----------------------\n\n";
			echo "\t/**\n\t* " . $hook_name . " hook\n\t*\n";
			foreach ( $docs as $doc ) {
				echo "\t* @hooked " . $doc[ 0 ] . " - " . $doc[ 1 ] . "\n";
			}
			echo "\t*/";
			echo "\n\n";
			echo "\tdo_action( '" . $hook_name . "' );";

		}
		echo '</pre>';
	}