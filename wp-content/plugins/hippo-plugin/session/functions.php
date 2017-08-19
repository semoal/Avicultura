<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	//----------------------------------------------------------------------
	// Row Session
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_plugin_start_session' ) ):

		function hippo_plugin_start_session() {
			if ( function_exists( 'session_start' ) && function_exists( 'session_id' ) && ! session_id() ) {
				session_start();
			}
		}

		add_action( 'init', 'hippo_plugin_start_session', 1 );
	endif;

	if ( ! function_exists( 'hippo_plugin_set_session' ) ):
		function hippo_plugin_set_session( $name, $value ) {

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

	if ( ! function_exists( 'hippo_plugin_get_session' ) ):
		function hippo_plugin_get_session( $name ) {
			if ( ! isset( $_SESSION ) || ! isset( $_SESSION[ $name ] ) ) {
				return FALSE;
			}

			return ! empty( $_SESSION[ $name ] ) ? trim( $_SESSION[ $name ] ) : FALSE;
		}
	endif;

	if ( ! function_exists( 'hippo_plugin_delete_session' ) ):
		function hippo_plugin_delete_session( $name ) {
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

	if ( ! function_exists( 'hippo_plugin_set_cookie' ) ):
		function hippo_plugin_set_cookie( $name, $value, $expire = FALSE, $secure = FALSE ) {

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

	if ( ! function_exists( 'hippo_plugin_get_cookie' ) ):
		function hippo_plugin_get_cookie( $name ) {
			return ! empty( $_COOKIE[ $name ] ) ? trim( $_COOKIE[ $name ] ) : FALSE;
		}
	endif;

	if ( ! function_exists( 'hippo_plugin_delete_cookie' ) ):
		function hippo_plugin_delete_cookie( $name ) {
			unset( $_COOKIE[ $name ] );

			return hippo_plugin_set_cookie( $name, NULL, time() - YEAR_IN_SECONDS );
		}
	endif;

	//----------------------------------------------------------------------
	// WooCommerce Session, use init / wp_loaded hook
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_plugin_set_wc_session' ) ):
		function hippo_plugin_set_wc_session( $name, $value ) {

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

	if ( ! function_exists( 'hippo_plugin_get_wc_session' ) ):
		function hippo_plugin_get_wc_session( $name ) {
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

	if ( ! function_exists( 'hippo_plugin_delete_wc_session' ) ):
		function hippo_plugin_delete_wc_session( $name ) {
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