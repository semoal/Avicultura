<?php


	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	if ( ! class_exists( 'Hippo_Simple_Currency_Switcher' ) ) :

		final class Hippo_Simple_Currency_Switcher {

			public  $current_currency;
			public  $session   = 'hippo_current_currency';
			private $live_rate = FALSE; // Always Make false :)

			public function __construct() {

				$this->current_currency = get_option( 'woocommerce_currency' );

				add_action( 'template_redirect', array( $this, 'set_currency' ), 70 );
				add_action( 'template_redirect', array( $this, 'reset_currency' ), 80 );

				add_filter( 'wc_price_args', array( $this, 'wc_price_args' ), 90 );
				add_filter( 'raw_woocommerce_price', array( $this, 'raw_woocommerce_price' ), 90 );

				do_action( 'hippo_simple_currency_switcher_init', $this );
			}

			public function get_currency() {

				if ( hippo_plugin_get_wc_session( $this->session ) ) {
					return hippo_plugin_get_wc_session( $this->session );
				}

				return $this->current_currency;

			}

			public function set_currency() {

				if ( isset( $_GET[ 'hippo-switch-currency' ] ) && ! empty( $_GET[ 'hippo-switch-currency' ] ) ) {
					$required_currency = strtoupper( wp_kses( trim( $_GET[ 'hippo-switch-currency' ] ), array() ) );

					if ( in_array( $required_currency, $this->valid_currency() ) ) {
						hippo_plugin_set_wc_session( $this->session, $required_currency );
					} else {
						hippo_plugin_delete_wc_session( $this->session );
					}
				}
			}

			public function reset_currency() {

				// if current currency == required
				if ( $this->current_currency == hippo_plugin_get_wc_session( $this->session ) ) {
					hippo_plugin_delete_wc_session( $this->session );
				}

				// order review update
				if ( isset( $_REQUEST[ 'action' ] ) ) {
					if ( $_REQUEST[ 'action' ] == 'woocommerce_update_order_review' ) {
						hippo_plugin_delete_wc_session( $this->session );
					}
				}

				// Ajax
				if ( isset( $_GET[ 'wc-ajax' ] ) AND $_GET[ 'wc-ajax' ] == 'update_order_review' ) {
					hippo_plugin_delete_wc_session( $this->session );
				}

				// API and Paypal Actions
				if ( isset( $_GET[ 'wc-api' ] ) AND isset( $_GET[ 'pp_action' ] ) AND isset( $_GET[ 'use_paypal_credit' ] ) ) {
					if ( $_GET[ 'pp_action' ] == 'expresscheckout' ) {
						hippo_plugin_delete_wc_session( $this->session );
					}
				}

				// Reset on some page
				if ( class_exists( 'WooCommerce' ) and ( is_cart() OR is_checkout() OR is_checkout_pay_page() or is_admin() or is_account_page() ) ) {

					hippo_plugin_delete_wc_session( $this->session );
				}

				// reset if not activate
				if ( function_exists( 'hippo_option' ) and ( ! hippo_option( 'currency-switcher', FALSE, FALSE ) ) ) {
					hippo_plugin_delete_wc_session( $this->session );
				}

				// return true to reset session
				if ( apply_filters( 'hippo_currency_switcher_reset_condition', FALSE ) ) {
					hippo_plugin_delete_wc_session( $this->session );
				}

				do_action( 'hippo_currency_condition_check', $this );
			}

			private function valid_currency() {
				return hippo_option( 'woo_currency_switcher', 'currency', array() );
			}

			public function display_list() {

				if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {

					$currencies = $this->valid_currency();
					?>
					<select id="currency-switcher" class="hippo-currency-switcher">
						<?php foreach ( $currencies as $code ): ?>
							<option <?php selected( $code, $this->get_currency() ) ?>
								value="<?php echo $code ?>"><?php echo get_woocommerce_currency_symbol( $code ) ?></option>
						<?php endforeach; ?>
					</select>
					<?php
				}
			}

			private function get_selected_index() {
				$currencies = hippo_option( 'woo_currency_switcher', 'currency', array() );
				$currencies = array_flip( $currencies );

				if ( ! isset( $currencies[ $this->get_currency() ] ) ) {
					return NULL;
				}

				return $currencies[ $this->get_currency() ];
			}

			public function get_price_format() {

				$options = hippo_option( 'woo_currency_switcher', 'currency_position' );

				$selected_currency_index = $this->get_selected_index();

				if ( is_null( $selected_currency_index ) ) {
					$options[ $selected_currency_index ] = get_option( 'woocommerce_currency_pos' );
				}


				$format = '%1$s%2$s';

				switch ( $options[ $selected_currency_index ] ) {
					case 'left' :
						$format = '%1$s%2$s';
						break;
					case 'right' :
						$format = '%2$s%1$s';
						break;
					case 'left_space' :
						$format = '%1$s&nbsp;%2$s';
						break;
					case 'right_space' :
						$format = '%2$s&nbsp;%1$s';
						break;
				}

				return $format;
			}

			public function get_decimal_separator() {

				$options = hippo_option( 'woo_currency_switcher', 'decimal_separator' );

				$selected_currency_index = $this->get_selected_index();

				if ( is_null( $selected_currency_index ) ) {
					$options[ $selected_currency_index ] = get_option( 'woocommerce_price_decimal_sep' );
				}

				return $options[ $selected_currency_index ];

			}

			public function get_thousand_separator() {

				$options = hippo_option( 'woo_currency_switcher', 'thousand_separator' );

				$selected_currency_index = $this->get_selected_index();

				if ( is_null( $selected_currency_index ) ) {
					$options[ $selected_currency_index ] = get_option( 'woocommerce_price_thousand_sep' );
				}

				return $options[ $selected_currency_index ];

			}

			public function get_decimal_number() {

				$options = hippo_option( 'woo_currency_switcher', 'decimal_number' );

				$selected_currency_index = $this->get_selected_index();

				if ( is_null( $selected_currency_index ) ) {
					$options[ $selected_currency_index ] = get_option( 'woocommerce_price_num_decimals' );
				}

				return $options[ $selected_currency_index ];

			}


			public function get_exchange_api( $from, $to, $amount ) {
				return $this->google_api( $from, $to );
			}

			// Google API
			public function google_api( $from, $to ) {

				$amount = 1;

				$url = sprintf( "https://www.google.com/finance/converter?a=%s&from=%s&to=%s", $amount, $from, $to );

				$contents = file_get_contents( $url );

				if ( function_exists( 'libxml_use_internal_errors' ) ) {
					// memory intensive operation
					libxml_use_internal_errors( TRUE );
				}

				$doc = new DOMDocument( '1.0', 'UTF-8' );
				$doc->loadHTML( $contents );

				if ( function_exists( 'libxml_clear_errors' ) ) {
					// clear internal error buffer
					libxml_clear_errors();
				}

				$XPath    = new DOMXPath( $doc );
				$get_rate = $XPath->query( '//*[@id="currency_converter_result"]/span[@class="bld"]' );

				if ( $get_rate->length > 0 ) {
					$rate = floatval( $get_rate->item( 0 )->nodeValue );
				} else {
					$rate = floatval( $amount );
				}

				return $rate;
			}

			// Fixer API
			public function fixer_api( $from, $to ) {
				$url      = sprintf( "http://api.fixer.io/latest?base=%s&symbols=%s", $from, $to );
				$contents = file_get_contents( $url );
				$exchange = json_decode( $contents, TRUE );

				return $exchange[ 'rates' ][ $to ];
			}

			// Yahoo API
			public function yahoo_api( $from, $to ) {

				// Console: http://developer.yahoo.com/yql/console/?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22USDEUR%22)&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys

				// https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22USDEUR%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=

				$url      = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22{$from}{$to}%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
				$contents = file_get_contents( $url );
				$exchange = json_decode( $contents, TRUE );

				return $exchange[ 'query' ][ 'results' ][ 'rate' ][ 'Rate' ];
			}


			private function _get_exchange_rate( $from_currency, $to_currency, $amount = 1 ) {

				$transient_name = 'hippo_currency_exchange_from_' . strtolower( $from_currency ) . '_to_' . strtolower( $to_currency );

				if ( $this->live_rate ) {
					delete_transient( $transient_name );
				}

				$amount = urlencode( $amount );

				$from_currency = strtoupper( urlencode( $from_currency ) );
				$to_currency   = strtoupper( urlencode( $to_currency ) );

				//if ( WP_DEBUG or FALSE === ( $exchange_rate = get_transient( $transient_name ) ) ) {
				if ( FALSE === ( $exchange_rate = get_transient( $transient_name ) ) ) {

					$rate = floatval( $this->get_exchange_api( $from_currency, $to_currency, $amount ) );

					set_transient( $transient_name, $rate, 6 * HOUR_IN_SECONDS );

					return $rate;
				} else {
					return get_transient( $transient_name );
				}
			}

			public function raw_woocommerce_price( $price ) {

				$requested_currency = hippo_plugin_get_wc_session( $this->session );

				if ( $requested_currency ) {
					$exchange_rate = $this->_get_exchange_rate( $this->current_currency, $requested_currency );

					return $price * $exchange_rate;
				}

				return $price;
			}

			public function wc_price_args( $args ) {

				$requested_currency = hippo_plugin_get_wc_session( $this->session );

				if ( $requested_currency ) {
					$args[ 'currency' ]           = $requested_currency;
					$args[ 'decimal_separator' ]  = $this->get_decimal_separator();
					$args[ 'thousand_separator' ] = $this->get_thousand_separator();
					$args[ 'decimals' ]           = $this->get_decimal_number();
					$args[ 'price_format' ]       = $this->get_price_format();

					return $args;
				}

				return $args;
			}
		}

	endif; // class_exists( 'Hippo_Simple_Currency_Switcher' )


	if ( ! function_exists( 'Hippo_Simple_Currency_Switcher' ) ):

		function Hippo_Simple_Currency_Switcher() {
			$GLOBALS[ 'hippo_simple_currency_switcher' ] = new Hippo_Simple_Currency_Switcher();
		}

		add_action( 'plugins_loaded', 'Hippo_Simple_Currency_Switcher', 99 );

	endif; // function_exists( 'Hippo_Simple_Currency_Switcher' )




