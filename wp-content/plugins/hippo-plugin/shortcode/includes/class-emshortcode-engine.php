<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! class_exists( 'em_Shortcodes_Engine' ) ):

		class em_Shortcodes_Engine {

			private static $instance         = NULL;
			private        $shortcodes       = array();
			private        $shortcode_string = '';

			private function __construct() {

				// init process for registering our button
				add_action( 'admin_init', array( $this, 'shortcode_button_init' ) );

				add_action( 'admin_enqueue_scripts', array( $this, 'on_admin_script_load' ) );

				$this->attr = new em_Shortcode_Attr();

				add_action( 'wp_ajax_build_shortcode', array( $this, 'shortcode_build_callback' ) );
				add_action( 'wp_ajax_save_shortcode', array( $this, 'generate_shortcode' ) );
			}

			public static function getInstance() {

				if ( is_null( self::$instance ) ) {
					self::$instance = new em_Shortcodes_Engine();
				}

				return self::$instance;
			}

			public function on_admin_script_load() {
				add_thickbox();
				wp_enqueue_media();

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );

				//wp_enqueue_script('wpdialogs');
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-widget' );
				wp_enqueue_script( 'jquery-ui-mouse' );
				wp_enqueue_script( 'jquery-ui-sortable' );

				//wp_enqueue_script('select2', HIPPO_PLUGIN_DIR . 'js/select2.min.js');
				wp_enqueue_style( 'em-shortcode-style', EM_SHORTCODES_URL . '/css/shortcode-style.css' );

			}

			public function register( $tag_name, $info = array() ) {

				// $info['name'] = $tag_name;

				// $info = apply_filters("hippo_shortcode_{$tag_name}", $info, $this);

				// add_filter("hippo_shortcode_{$tag_name}", function($info, $this){},10,2);


				$parents_tag = isset( $info[ 'child_of' ] ) ? $info[ 'child_of' ] : FALSE;

				if ( isset( $info[ 'editor_contents' ] ) and $info[ 'editor_contents' ] == TRUE ) {
					$info[ 'attributes' ][ 'editor_contents' ] = array(
						'type'        => 'editor_contents',
						// text, textarea, color, select, select2, image, font, editor_contents
						'label'       => __( 'Contents', 'hippo-plugin' ),
						'description' => __( 'Selected text from editor contents', 'hippo-plugin' )
					);
				}

				$shortcode_ref = &$this->shortcodes;

				if ( is_array( $parents_tag ) ) {
					while ( $key = array_shift( $parents_tag ) ) {
						$shortcode_ref = &$shortcode_ref[ $key ][ 'child' ];
					}
					$shortcode_ref[ $tag_name ] = apply_filters( "hippo_register_shortcode_{$tag_name}", $info, $this );

				} elseif ( is_string( $parents_tag ) ) {
					$shortcode_ref              = &$shortcode_ref[ $parents_tag ][ 'child' ];
					$shortcode_ref[ $tag_name ] = apply_filters( "hippo_register_shortcode_{$tag_name}", $info, $this );
				} else {
					$this->shortcodes[ $tag_name ] = apply_filters( "hippo_register_shortcode_{$tag_name}", $info, $this );
				}

				return $this;
			}

			public function get_shortcodes() {
				return $this->shortcodes;
			}

			public function shortcode_button_init() {
				//Abort early if the user will never see TinyMCE
				if ( ! current_user_can( 'edit_posts' )
				     and ! current_user_can( 'edit_pages' )
				         and get_user_option( 'rich_editing' ) == 'true'
				) {
					return;
				}

				//Add a callback to regiser our tinymce plugin
				add_filter( "mce_external_plugins", array( $this, "register_tinymce_plugin" ) );

				// Add a callback to add our button to the TinyMCE toolbar
				add_filter( 'mce_buttons', array( $this, 'add_tinymce_button' ) );

				add_action( 'admin_enqueue_scripts', array( $this, 'add_script_objects' ) );
			}

			public function add_script_objects() {

				wp_localize_script( 'jquery', 'em_shortcode_obj', apply_filters( 'em_shortcode_obj', array(
					'button_title'   => EM_SHORTCODE_BUTTON_TITLE,
					'shortcode_popup_url' => esc_url( add_query_arg( 'action', 'hippo_shortcode_generator_popup', admin_url( 'admin-ajax.php' ) ) ),
					'window_title'   => EM_SHORTCODE_POPUP_TITLE,
					'width'          => EM_SHORTCODE_POPUP_WIDTH,
					'height'         => EM_SHORTCODE_POPUP_HEIGHT,
					'wp_content_dir' => basename( WP_CONTENT_URL ),
					'abspath'        => ABSPATH
				) ) );
			}

			//This callback registers our plug-in

			public function register_tinymce_plugin( $plugin_array ) {
				$plugin_array[ 'em_button' ] = EM_SHORTCODES_URL . '/js/tinymce.js';

				return $plugin_array;
			}

			//This callback adds our button to the toolbar

			public function add_tinymce_button( $buttons ) {
				//Add the button ID to the $button array
				$buttons[] = "em_button";

				return $buttons;
			}

			public function init() {
				add_action( 'wp_ajax_build_shortcode', array( $this, 'shortcode_build_callback' ) );
				add_action( 'wp_ajax_save_shortcode', array( $this, 'generate_shortcode' ) );
			}

			public function shortcode_build_callback() {
				$name      = esc_html( $_POST[ 'name' ] );
				$parent    = esc_html( $_POST[ 'parent' ] );
				$shortcode = $this->get_shortcode( $name, $parent );
				echo $this->attr->set_attributes( $name, $shortcode );
				die;
			}

			private function get_shortcode( $name, $parent = '' ) {

				if ( $parent ) {
					return $this->shortcodes[ $parent ][ 'child' ][ $name ];
				}

				return $this->shortcodes[ $name ];
			}

			public function generate_shortcode() {
				$data = rawurldecode( $_POST[ 'data' ] );
				parse_str( $data, $values );

				// print_r($values); die;
				$shortcode_name = $values[ 'shortcode' ];

				$this->build( $shortcode_name, $values );
				echo $this->shortcode_string;
				die;
			}

			private function build( $shortcode_name, $data ) {

				if ( ! isset( $data[ $shortcode_name ] ) ) {
					$this->shortcode_string .= '<p>[' . $shortcode_name . ']</p>';

				} else {

					foreach ( $data[ $shortcode_name ] as $inc => $shortcode ) {

						$this->shortcode_string .= '<p>[' . $shortcode_name;

						if ( $this->has_attributes( $shortcode ) ) {
							$this->shortcode_string .= ' ' . $this->build_attributes( $shortcode );
						}
						$this->shortcode_string .= ']</p>';

						//  if we want to use content before child shortcode
						if ( $this->has_editor_contents( $shortcode ) ) {
							//  $this->shortcode_string .= $this->editor_contents($shortcode);
						}

						if ( $this->has_child( $shortcode ) ) {

							foreach ( $shortcode[ 'child' ] as $child_shortcode_name => $child_shortcodes ) {
								$this->build( $child_shortcode_name, $shortcode[ 'child' ] );
							}
						}

						if ( $this->has_editor_contents( $shortcode ) or $this->has_child( $shortcode ) ) {

							//  if we want to use content after child shortcode
							$this->shortcode_string .= $this->editor_contents( $shortcode );
							$this->shortcode_string .= '<p>[/' . $shortcode_name . ']</p>';
						}
					}
				}
			}

			private function has_attributes( $data ) {
				return isset( $data[ 'attributes' ] );
			}

			private function build_attributes( $data = array() ) {
				$_arr = array();
				foreach ( $data[ 'attributes' ] as $index => $value ) {

					if ( $index == 'editor_contents' ) {
						continue;
					}

					if ( $value == '' ) {
						continue;
					}

					if ( is_array( $value ) ) {

						if ( empty( $value ) ) {
							continue;
						}

						$value = implode( ',', $value );
					}

					$_arr[] = $index . '="' . $value . '"';
				}

				return implode( ' ', $_arr );
			}

			private function has_editor_contents( $data ) {
				if ( $this->has_attributes( $data ) and isset( $data[ 'attributes' ][ 'editor_contents' ] ) ) {
					return TRUE;
				}

				return FALSE;
			}

			private function has_child( $data ) {
				return isset( $data[ 'child' ] );
			}

			private function editor_contents( $data ) {
				if ( $this->has_attributes( $data ) and isset( $data[ 'attributes' ][ 'editor_contents' ] ) ) {
					return $data[ 'attributes' ][ 'editor_contents' ];
				}

				return '';
			}
		}

	endif; // class_exists( 'em_Shortcodes_Engine' )