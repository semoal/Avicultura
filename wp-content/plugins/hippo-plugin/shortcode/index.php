<?php


	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	define( 'EM_SHORTCODES_DIR', dirname( __FILE__ ) );
	define( 'EM_SHORTCODES_RELATIVE_PATH', dirname( plugin_basename( __FILE__ ) ) );
	define( 'EM_SHORTCODES_URL', plugins_url( basename( dirname( __FILE__ ) ), dirname( __FILE__ ) ) );
	define( 'EM_SHORTCODES_PREFIX', 'em_Shortcode_' );


	define( 'EM_SHORTCODE_BUTTON_TITLE', __( 'Insert Shortcode', 'hippo-plugin' ) );
	define( 'EM_SHORTCODE_POPUP_TITLE', __( 'Shortcodes', 'hippo-plugin' ) );
	define( 'EM_SHORTCODE_POPUP_WIDTH', 655 );
	define( 'EM_SHORTCODE_POPUP_HEIGHT', 400 );

	define( 'EM_THEME_STYLESHEET_DIR', get_stylesheet_directory() );
	define( 'EM_THEME_TEMPLATE_DIR', get_template_directory() );


	define( 'EM_SHORTCODE_FILES_DIR', 'shortcodes' );


	require_once EM_SHORTCODES_DIR . '/includes/class-emshortcode-engine.php';
	require_once EM_SHORTCODES_DIR . '/includes/class-emshortcode-attr.php';


	if ( ! function_exists( 'hippo_register_shortcode_init' ) ):

		function hippo_register_shortcode_init() {
			do_action( 'hippo_register_shortcode', em_Shortcodes_Engine::getInstance() );
		}

		add_action( 'init', 'hippo_register_shortcode_init' );

	endif;

	//$shortcode = em_Shortcodes_Engine::getInstance();


	//----------------------------------------------------------------------
	// Shortcode file importing, this function will search on child theme directory then template directory then plugin directory :)
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_import_shortcode' ) ):
		function hippo_import_shortcode( $file_name ) {

			$on_stylesheet_dir      = EM_THEME_STYLESHEET_DIR . '/' . EM_SHORTCODE_FILES_DIR;
			$file_on_stylesheet_dir = EM_THEME_STYLESHEET_DIR . '/' . EM_SHORTCODE_FILES_DIR . '/' . $file_name;

			$on_template_dir      = EM_THEME_TEMPLATE_DIR . '/' . EM_SHORTCODE_FILES_DIR;
			$file_on_template_dir = EM_THEME_TEMPLATE_DIR . '/' . EM_SHORTCODE_FILES_DIR . '/' . $file_name;

			$file_on_plugin_dir = EM_SHORTCODES_DIR . '/' . EM_SHORTCODE_FILES_DIR . '/' . $file_name;


			if ( file_exists( $on_stylesheet_dir ) and file_exists( $file_on_stylesheet_dir ) ) {
				include_once $file_on_stylesheet_dir;
			} elseif ( file_exists( $on_template_dir ) and file_exists( $file_on_template_dir ) ) {
				include_once $file_on_template_dir;
			} else {
				include_once $file_on_plugin_dir;
			}
		}
	endif;

	//----------------------------------------------------------------------
	// Get previous content of <!--more--> tag
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_before_more_content' ) ):
		function hippo_before_more_content( $contents ) {
			$contents = get_extended( $contents );

			return apply_filters( 'the_content', wpautop( do_shortcode( $contents[ 'main' ] ) ) );
		}
	endif;

	//----------------------------------------------------------------------
	// Check that content has <!--more--> tag or not :)
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_has_more_content' ) ):
		function hippo_has_more_content( $contents ) {

			$contents = get_extended( $contents );

			return ! empty( $contents[ 'extended' ] );
		}
	endif;


	//----------------------------------------------------------------------
	// Removing p tag which wrapping shortCodes
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_shortcode_empty_paragraph_fix' ) ):

		function hippo_shortcode_empty_paragraph_fix( $content ) {
			$array = array(
				'<p>['    => '[',
				']</p>'   => ']',
				']<br />' => ']'
			);

			return strtr( $content, $array );
		}

		add_filter( 'the_content', 'hippo_shortcode_empty_paragraph_fix' );
		add_filter( 'widget_text', 'hippo_shortcode_empty_paragraph_fix' );
		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'widget_title', 'do_shortcode' );
	endif;

	//----------------------------------------------------------------------
	// Import Shortcodes
	//----------------------------------------------------------------------

	require_once EM_SHORTCODES_DIR . '/import-shortcode.php';


	if ( ! function_exists( 'hippo_shortcode_generator_popup' ) ):

		function hippo_shortcode_generator_popup() {
			require_once EM_SHORTCODES_DIR . '/includes/em-mce-popup.php';
			wp_die();
		}

		add_action( 'wp_ajax_hippo_shortcode_generator_popup', 'hippo_shortcode_generator_popup' );

	endif;


