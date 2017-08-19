<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! class_exists( 'Hippo_Less_Css_Init' ) ):

		class Hippo_Less_Css_Init {

			private $less_object;
			private $less_variables = array();

			public function __construct() {

				if ( ! function_exists( 'hippo_option' ) ) {
					return FALSE;
				}

				add_filter( 'style_loader_src', array( $this, 'less_loader' ), 999, 2 );
			}

			public function get_file_path( $template_name, $http_path = FALSE ) {
				if ( file_exists( get_stylesheet_directory() . '/' . $template_name ) ) {
					if ( $http_path ) {
						return trailingslashit( get_stylesheet_directory_uri() ) . $template_name;
					} else {
						return trailingslashit( get_stylesheet_directory() ) . $template_name;
					}
				} elseif ( file_exists( get_template_directory() . '/' . $template_name ) ) {
					if ( $http_path ) {
						return trailingslashit( get_template_directory_uri() ) . $template_name;
					} else {
						return trailingslashit( get_template_directory() ) . $template_name;
					}
				}
			}

			public function less_loader( $src, $handle ) {
				$src_path  = parse_url( $src, PHP_URL_PATH );
				$split_dot = explode( '.', $src_path );
				$extension = strtolower( end( $split_dot ) );

				if ( $extension == 'less' ) {

					/**
					 * Filter Hook For Change CSS Compile Directory,
					 */
					$css_compiled_directory = apply_filters( 'hippo_css_compile_dir', 'css-compiled' );

					$less_file_name = basename( $src_path );

					$current_preset = '-' . hippo_option_get_preset();

					$current_blog_path      = '';
					$src_data               = explode( '?', str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $src ) );
					$less_file_abs_path     = $src_data[ 0 ];

					if ( is_multisite() ) {

						$current_blog_path = trim( get_blog_details()->path );
						$current_site_path = trim( get_current_site()->path );

						if ( $current_site_path !== '/' ) {
							$current_blog_path = str_ireplace( $current_site_path, '', $current_blog_path );
						}
					}

					$current_blog_path_slug = trim( $current_blog_path, '/\\' );

					if ( ! empty( $current_blog_path_slug ) ) {
						$current_blog_path_slug = '-' . $current_blog_path_slug;
					}

					$less_file_rel_path = str_ireplace( $this->get_load_uri( $less_file_abs_path ), '', $less_file_abs_path );

					$load_path     = $this->get_load_uri( $less_file_abs_path );
					$load_http_uri = $this->get_load_uri( $less_file_abs_path, TRUE );


					// Joining MultiSite Name and preset
					$css_file_name = str_ireplace( '.less', $current_blog_path_slug . $current_preset . '.css', $less_file_name );

					$css_file_rel_path          = trailingslashit( $css_compiled_directory ) . $css_file_name;
					$css_file_abs_path          = trailingslashit( $load_path ) . $css_file_rel_path;
					$css_file_compiled_dir_path = trailingslashit( $load_path ) . $css_compiled_directory;

					$css_file_http_path = trailingslashit( $load_http_uri ) . $css_file_rel_path;

					$source_map_basepath = trailingslashit( $load_path );
					$source_map_write_to = trailingslashit( $load_path ) . $css_file_name . '.source.map';
					$source_map_url      = wp_make_link_relative( trailingslashit( $load_http_uri ) ) . $css_file_name . '.source.map';

					if ( ! hippo_option( 'less-compiler', FALSE, FALSE ) ) {
						return $css_file_http_path;
					}

					try {

						$this->less_init( $source_map_basepath, $source_map_write_to, $source_map_url );

						/**
						 * FOR BUG FIX AND 3RD PARTY PLUGIN
						 */
						$less_file_abs_path = apply_filters( 'hippo_less_file_abs_path', $less_file_abs_path, $src, $src_path, $handle );

						$this->less_object->parseFile( $less_file_abs_path, '' );
						$css_output = $this->less_object->getCss();

						if ( ! file_exists( $css_file_compiled_dir_path ) ) {
							if ( ! mkdir( $css_file_compiled_dir_path ) ) {
								wp_die( sprintf( __( "Cann't create directory <strong>%s</strong>, Please check permission to create directory.", 'hippo-plugin' ), basename( $css_file_compiled_dir_path ) ) );
							}
						}

						if ( ! file_put_contents( $css_file_abs_path, $css_output ) ) {
							wp_die( sprintf( __( "Cann't put generated css file on <strong>%s</strong>, Please check file permission to write.", 'hippo-plugin' ), $css_file_rel_path ) );
						}

					} catch ( exception $e ) {
						wp_die( sprintf( __( '%s', 'hippo-plugin' ), nl2br( $e->getMessage() ) ) );
					}

					return $css_file_http_path;
				}

				return $src;
			}

			public function get_load_uri( $file, $get_http = FALSE ) {
				$load_from_child_theme = str_ireplace( $file, '', get_stylesheet_directory() );

				if ( strpos( $file, $load_from_child_theme ) !== FALSE ) {

					if ( $get_http ) {
						return get_stylesheet_directory_uri();
					} else {
						return get_stylesheet_directory();
					}
				} else {
					if ( $get_http ) {
						return get_template_directory_uri();
					} else {
						return get_template_directory();
					}
				}
			}

			public function less_init( $source_map_basepath, $source_map_write_to, $source_map_url ) {

				/**
				 * Filter Hook For adding Less variable from theme option
				 */
				$this->less_variables = apply_filters( 'hippo_set_less_variables', $this->less_variables );

				require_once dirname( __FILE__ ) . "/Less.php";
				$options = array(
					'compress' => hippo_option( 'compress-less-output', FALSE, FALSE ),
				);


				if ( defined( 'WP_DEBUG' ) and WP_DEBUG ) {
					$options = array_merge( $options, apply_filters( 'hippo-plugin-less-options', array(
						'sourceMap'         => TRUE,
						'sourceMapBasepath' => $source_map_basepath,
						'sourceMapWriteTo'  => $source_map_write_to,
						'sourceMapURL'      => $source_map_url,
					) ) );
				}


				$this->less_object = new Less_Parser( $options );

				if ( is_array( $this->less_variables ) ) {
					$this->less_object->ModifyVars( $this->less_variables );
				}
			}
		}
	endif;

	if ( ! function_exists( 'Hippo_Less_Css_Init' ) ):
		function Hippo_Less_Css_Init() {
			new Hippo_Less_Css_Init();
		}

		add_action( 'wp_loaded', 'Hippo_Less_Css_Init' );
	endif;