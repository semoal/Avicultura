<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	define( 'HIPPO_CURRENT_IMPORT_URL', untrailingslashit( esc_url( site_url( '/' ) ) ) );
	define( 'HIPPO_DEVELOPMENT_URL', 'http://sink.demo' );
	define( 'HIPPO_IMPORTABLE_ATTACHMENT_URL', 'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink' );

	$upload_dir = wp_upload_dir();
	define( 'HIPPO_CURRENT_ATTACHMENT_URL', $upload_dir[ 'baseurl' ] );

	//----------------------------------------------------------------------
	// Show instructions after dummy data imported
	//----------------------------------------------------------------------

	function hippo_import_rev_slider_slides() {
		return array(
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/hero-video.zip',
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/sink-hero-2.zip',
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/sink-2-hero.zip',
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/banner-1-585x360.zip',
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/Sink-1-Hero.zip',
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/banner-1-500x300.zip',
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/pro-cat-furniture-sl1.zip',
			'http://www.cloudsoftwaresolution.com/imgstore/repository/products-attachments/wordpress/sink/sliders/Pro-bathroom-sl1.zip',
		);
	}

	add_filter( 'hippo_import_rev_slider_slides', 'hippo_import_rev_slider_slides' );

	function hippo_envato_setup_customize() {
		?>
		<p>Create Form <strong>MailChimp for WP &rightarrow; Forms</strong> and Use this form code: </p>
		<textarea class="code" readonly="readonly" cols="100" rows="5"><input type="email" name="EMAIL"
		                                                                      placeholder="Subscribe our news letter"
		                                                                      required="required"><button type="submit">
				<i class="fa fa-envelope"></i></button></textarea>
		<?php
	}

	//add_action( 'hippo_envato_setup_customize', 'hippo_envato_setup_customize' );

	function hippo_envato_setup_customize_features() {
		?>
		<ul>
			<li>Header Option: Enable/Disable Login popup, Multilingual language list display option.</li>
			<li>Typography: Font Weight, Style, Font Family for your site.</li>
			<li>Color Schemes: Choose or customize website colors.</li>
			<li>Mobile Menu: Left/Right display position, showing effect.</li>
			<li>Blog Layout: Left/Right/None Blog sidebar display options, post navigation display style.</li>
			<li>Page Layout: Left/Right/None Page sidebar display options.</li>
			<li>Shop Layout: Shopping layout display control.</li>
		</ul>
		<?php
	}

	add_action( 'hippo_envato_setup_customize_features', 'hippo_envato_setup_customize_features' );


	function hippo_setup_imported_menus() {
		$registared_menus = get_registered_nav_menus();
		$menu_locations   = get_theme_mod( 'nav_menu_locations' );

		foreach ( $registared_menus as $location => $title ) {
			$get_menus                   = wp_get_nav_menu_object( $title );
			$menu_locations[ $location ] = $get_menus->term_id;
		}
		set_theme_mod( 'nav_menu_locations', $menu_locations );

		// Set Home Page
		$home = get_page_by_title( 'Home' );
		update_option( 'page_on_front', $home->ID );

		// Set the blog page
		$blog = get_page_by_title( 'Blog' );
		update_option( 'page_for_posts', $blog->ID );

		update_option( 'show_on_front', 'page' );

		update_option( 'yith_wcwl_button_position', 'shortcode' );


	}

	// hippo_import_end
	add_action( 'hippo_import_end', 'hippo_setup_imported_menus' );


	function hippo_on_import_process_start_message() {
		echo '<div class="updated notice below-h2">';
		echo '<p><strong>After finished importing please done this checklist.</strong></p>';
		echo '</div>';
		echo '<ol>';
		echo '<li><p>Import <a target="_blank" href="' . esc_url( admin_url( "admin.php?page=revslider" ) ) . '"><strong>Revolution slider</strong></a> Slide.</p></li>';
		echo '</ol>';
		echo '<div class="clear"></div>';
	}

	//add_action( 'hippo_on_import_process_start_message', 'hippo_on_import_process_start_message' );

	//----------------------------------------------------------------------
	// Filter Applied on Theme Options data importing
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_import_process_theme_option_data' ) ) {

		function hippo_import_process_theme_option_data( $data ) {
			$find    = addcslashes( HIPPO_DEVELOPMENT_URL, '/' );
			$replace = addcslashes( HIPPO_CURRENT_IMPORT_URL, '/' );

			return str_ireplace( $find, $replace, $data );
		}

		add_filter( 'hippo_import_process_theme_option_data', 'hippo_import_process_theme_option_data' );
	}

	//----------------------------------------------------------------------
	// Filter Applied on Widget data importing
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_import_process_widget_data' ) ) {
		function hippo_import_process_widget_data( $data ) {
			$find    = addcslashes( HIPPO_DEVELOPMENT_URL, '/' );
			$replace = addcslashes( HIPPO_CURRENT_IMPORT_URL, '/' );

			return str_ireplace( $find, $replace, $data );
		}

		add_filter( 'hippo_import_process_widget_data', 'hippo_import_process_widget_data' );
	}

	//----------------------------------------------------------------------
	// Filter Applied on Sample XML data attachment importing
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_import_process_attachment_remote_url' ) ) {

		function hippo_import_process_attachment_remote_url( $data ) {
			$find    = HIPPO_DEVELOPMENT_URL;
			$replace = HIPPO_IMPORTABLE_ATTACHMENT_URL;

			return str_ireplace( $find, $replace, $data );
		}

		add_filter( 'hippo_import_process_attachment_remote_url', 'hippo_import_process_attachment_remote_url' );
	}

	//----------------------------------------------------------------------
	// Filter Applied on Sample XML data content importing
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_import_process_post_content' ) ) {

		function hippo_import_process_post_content( $data ) {
			$find    = HIPPO_DEVELOPMENT_URL;
			$replace = HIPPO_CURRENT_IMPORT_URL;

			return str_ireplace( $find, $replace, $data );
		}

		add_filter( 'hippo_import_process_post_content', 'hippo_import_process_post_content' );
	}
