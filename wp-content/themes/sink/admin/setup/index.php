<?php
	/**
	 * Envato Theme Setup Wizard Class
	 * Based off the WooThemes installer.
	 *
	 */

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	/**
	 * Hippo_Envato_Theme_Setup_Wizard class
	 */
	class Hippo_Envato_Theme_Setup_Wizard {

		private $portfolio_link   = 'http://themeforest.net/user/themehippo';
		private $support_link     = 'https://themehippo.com/tickets/';
		private $docs_link        = 'https://themehippo.com/documentation/sink/';
		private $qs_pack_link     = 'https://themehippo.com/wordpress-demo-like-theme-installation/';
		private $download_link    = 'http://themeforest.net/downloads/';
		private $twitter_username = 'themehippo';
		private $step             = '';
		private $steps            = array(); // set in construct
		private $public_base_url  = ''; // set in construct
		private $theme_name       = '';
		private $theme_nice_name  = '';

		/**
		 * Hook in tabs.
		 */
		public function __construct() {

			$this->theme_name      = strtolower( wp_get_theme()->get( 'Name' ) );
			$this->theme_nice_name = wp_get_theme()->get( 'Name' );
			if ( apply_filters( $this->theme_name . '_theme_enable_setup_wizard', TRUE ) and current_user_can( 'manage_options' ) ) {
				$this->public_base_url = get_template_directory_uri() . '/admin/setup';
				add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
				add_action( 'admin_menu', array( $this, 'admin_menus' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				//add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
				add_action( 'admin_notices', array( $this, 'admin_notices' ), 30 );
				add_action( 'admin_init', array( $this, 'skip_setup_wizard' ), 20 );
				add_action( 'admin_init', array( $this, 'setup_wizard' ), 30 );
				add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
				add_action( 'wp_ajax_envato_setup_plugins', array( $this, 'ajax_plugins' ) );
				add_action( 'wp_ajax_envato_setup_content', array( $this, 'ajax_content' ) );
			}
		}

		public function skip_setup_wizard() {
			if ( isset( $_GET[ 'hide-theme-setup-wizard' ] ) && $_GET[ 'hide-theme-setup-wizard' ] == 1 ) {
				delete_transient( '_' . $this->theme_name . '_run_setup_wizard' );
			}
		}

		public function admin_notices() {

			if ( ! get_transient( '_' . $this->theme_name . '_run_setup_wizard' ) ) {
				return;
			}
			?>
			<div class="updated">
				<p><?php printf( __( '<strong>Thank you for choosing the %1$s theme</strong> - You\'re almost ready to configure your site with %1$s theme :)', 'sink' ), $this->theme_nice_name ) ?></p>
				<p class="submit">
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=' . $this->theme_name . '-setup' ) ) ?>"
					   class="button-primary"><?php printf( __( 'Run the %s Theme Setup Wizard', 'sink' ), $this->theme_nice_name ) ?></a>
					<a class="button-secondary skip"
					   href="<?php echo esc_url( wp_nonce_url( admin_url( '?hide-theme-setup-wizard=1' ), 'skip-theme-setup' ) ) ?>"><?php printf( 'Skip Setup', 'sink' ) ?></a>
				</p>
			</div>
			<?php
		}

		public function enqueue_scripts() {
		}

		public function tgmpa_load( $status ) {
			return is_admin() or current_user_can( 'install_themes' );
		}

		public function switch_theme() {
			//set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );
			set_transient( '_' . $this->theme_name . '_run_setup_wizard', 1 );
		}

		public function admin_redirects() {
			ob_start();
			if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) ) {
				return;
			}
			delete_transient( '_' . $this->theme_name . '_activation_redirect' );
			//wp_safe_redirect( admin_url( 'themes.php?page=' . $this->theme_name . '-setup' ) );
			exit;
		}

		/**
		 * Add admin menus/screens.
		 */
		public function admin_menus() {
			add_theme_page(
				sprintf( esc_html__( '%s Setup Wizard', 'sink' ), $this->theme_nice_name ),
				sprintf( esc_html__( '%s Setup Wizard', 'sink' ), $this->theme_nice_name ),
				'manage_options',
				$this->theme_name . '-setup', array(
					$this,
					'setup_wizard'
				)
			);
		}

		/**
		 * Show the setup wizard
		 */
		public function setup_wizard() {
			if ( empty( $_GET[ 'page' ] ) || $this->theme_name . '-setup' !== $_GET[ 'page' ] ) {
				return;
			}
			@ob_end_clean();

			delete_transient( '_' . $this->theme_name . '_run_setup_wizard' );

			$this->steps = array(
				'introduction' => array(
					'name'    => esc_html__( 'Introduction', 'sink' ),
					'view'    => array( $this, 'envato_setup_introduction' ),
					'handler' => ''
				),
			);

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS[ 'tgmpa' ] ) ) {
				$this->steps[ 'default_plugins' ] = array(
					'name'    => esc_html__( 'Plugins', 'sink' ),
					'view'    => array( $this, 'envato_setup_default_plugins' ),
					'handler' => ''
				);
			}

			$this->steps[ 'default_content' ] = array(
				'name'    => esc_html__( 'Content', 'sink' ),
				'view'    => array( $this, 'envato_setup_default_content' ),
				'handler' => ''
			);
			$this->steps[ 'design' ]          = array(
				'name'    => esc_html__( 'Logo', 'sink' ),
				'view'    => array( $this, 'envato_setup_logo_design' ),
				'handler' => array( $this, 'envato_setup_logo_design_save' ),
			);

			$this->steps[ 'customize' ]    = array(
				'name'    => esc_html__( 'Customize', 'sink' ),
				'view'    => array( $this, 'envato_setup_customize' ),
				'handler' => '',
			);
			$this->steps[ 'help_support' ] = array(
				'name'    => esc_html__( 'Support', 'sink' ),
				'view'    => array( $this, 'envato_setup_help_support' ),
				'handler' => '',
			);
			$this->steps[ 'next_steps' ]   = array(
				'name'    => esc_html__( 'Ready!', 'sink' ),
				'view'    => array( $this, 'envato_setup_ready' ),
				'handler' => ''
			);
			$this->step                    = isset( $_GET[ 'step' ] ) ? sanitize_key( $_GET[ 'step' ] ) : current( array_keys( $this->steps ) );

			wp_register_script( 'jquery-blockui', $this->public_base_url . '/js/jquery.blockUI.js', array( 'jquery' ), '2.70', TRUE );
			wp_register_script( 'envato-setup', $this->public_base_url . '/js/envato-setup.js', array(
				'jquery',
				'jquery-blockui'
			), '' );
			wp_localize_script( 'envato-setup', 'envato_setup_params', array(
				'tgm_plugin_nonce' => array(
					'update'  => wp_create_nonce( 'tgmpa-update' ),
					'install' => wp_create_nonce( 'tgmpa-install' ),
				),
				'tgm_bulk_url'     => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'wpnonce'          => wp_create_nonce( 'envato_setup_nonce' ),
				'verify_text'      => esc_html__( '...verifying', 'sink' ),
			) );

			//wp_enqueue_style( 'envato_wizard_admin_styles', $this->public_base_url . '/css/admin.css', array(), ENVATO_THEME_VERSION );
			wp_enqueue_style( 'envato-setup', $this->public_base_url . '/css/envato-setup.css', array(
				'dashicons',
				'install'
			), '' );

			wp_enqueue_media();
			wp_enqueue_script( 'media' );

			ob_start();
			$this->setup_wizard_header();
			$this->setup_wizard_steps();
			$show_content = TRUE;
			echo '<div class="envato-setup-content">';
			if ( ! empty( $_REQUEST[ 'save_step' ] ) && isset( $this->steps[ $this->step ][ 'handler' ] ) ) {
				$show_content = call_user_func( $this->steps[ $this->step ][ 'handler' ] );
			}
			if ( $show_content ) {
				$this->setup_wizard_content();
			}
			echo '</div>';
			$this->setup_wizard_footer();
			exit;
		}

		/**
		 * Setup Wizard Header
		 */
	public function setup_wizard_header() {

		$tag = 'title';
		?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<<?php echo esc_attr( $tag ) ?>><?php esc_html_e( 'Theme &rsaquo; Setup Wizard', 'sink' ); ?>
		</<?php echo esc_attr( $tag ) ?>>
		<?php wp_print_scripts( 'envato-setup' ); ?>
		<?php do_action( 'admin_print_styles' ); ?>
		<?php do_action( 'admin_print_scripts' ); ?>
		<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="envato-setup wp-core-ui">
		<h1 id="wc-logo">
			<?php
				printf( '<a href="%s" target="_blank">%s</a>',
				        $this->portfolio_link,
				        sprintf(
					        '<img class="site-logo" src="%s" alt="%s" style="max-width:400px;height:auto" />',
					        esc_url( hippo_option( 'logo', 'url', get_template_directory_uri() . '/img/logo.png' ) ),
					        get_bloginfo( 'name' )
				        )
				);
			?>
		</h1>
		<?php
			}

			/**
			 * Output the steps
			 */
			public function setup_wizard_steps() {
				$ouput_steps = $this->steps;
				array_shift( $ouput_steps );
				?>
				<ol class="envato-setup-steps">
					<?php foreach ( $ouput_steps as $step_key => $step ) : ?>
						<li class="<?php
							$show_link = FALSE;
							if ( $step_key === $this->step ) {
								echo 'active';
							} elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
								echo 'done';
								$show_link = TRUE;
							}
						?>"><?php
								if ( $show_link ) {
									?>
									<a href="<?php echo esc_url( $this->get_step_link( $step_key ) ); ?>"><?php echo esc_html( $step[ 'name' ] ); ?></a>
									<?php
								} else {
									echo esc_html( $step[ 'name' ] );
								}
							?></li>
					<?php endforeach; ?>
				</ol>
				<?php
			}

			public function get_step_link( $step ) {
				return add_query_arg( 'step', $step, admin_url( 'admin.php?page=' . $this->theme_name . '-setup' ) );
			}

			/**
			 * Output the content for the current step
			 */
			public function setup_wizard_content() {
				isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ][ 'view' ] ) : FALSE;
			}

			/**
			 * Setup Wizard Footer
			 */
			public function setup_wizard_footer() {
		?>
		<?php if ( 'next_steps' === $this->step ) : ?>
			<a class="wc-return-to-dashboard"
			   href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Return to the WordPress Dashboard', 'sink' ); ?></a>
		<?php endif; ?>
		</body>
		<?php
			@do_action( 'admin_footer' ); // this was spitting out some errors in some admin templates. quick @ fix until I have time to find out what's causing errors.
			do_action( 'admin_print_footer_scripts' );
		?>
		</html>
		<?php
	}

		/**
		 * Introduction step
		 */
		public function envato_setup_introduction() {
			if ( isset( $_REQUEST[ 'export' ] ) ) {

				// find the ID of our menu names so we can import them into default menu locations and also the widget positions below.
				$menus          = get_terms( 'nav_menu' );
				$menu_locations = get_theme_mod( 'nav_menu_locations' );
				$menu_settings  = array();


				foreach ( $menu_locations as $id => $term_id ) {
					foreach ( $menus as $menu ) {
						if ( $term_id == $menu->term_id ) {
							$menu_settings[ $id ] = $menu->name;
						}
					}
				}

				// ThemeOptions

				$ReduxFramework = ReduxFrameworkInstances::get_instance( hippo_option_name() );
				$ReduxFramework->get_options();
				$redux_options                   = $ReduxFramework->options;
				$redux_options[ 'redux-backup' ] = '1';


				// choose which custom options to load into defaults
				$reading_options                     = array();
				$reading_options[ 'page_on_front' ]  = esc_html( get_the_title( get_option( 'page_on_front' ) ) );
				$reading_options[ 'page_for_posts' ] = esc_html( get_the_title( get_option( 'page_for_posts' ) ) );
				$reading_options[ 'show_on_front' ]  = get_option( 'show_on_front' );
				$reading_options[ 'posts_per_page' ] = get_option( 'posts_per_page' );

				?>
				<h1>Current Settings:</h1>

				<p><a target="_blank" href="<?php echo esc_url( admin_url( 'export.php' ) ) ?>">Export Contents</a></p>
				<p>Choose <strong>All contents</strong> and save it as <code>data.xml</code> file.</p>

				<p>Widget Settings:</p>
				<textarea
					style="width:100%; height:80px;"><?php echo json_encode( $this->_generate_widget_export_data() ); ?></textarea>
				<p>Save it as <code>widgets.json</code> file.</p>

				<p>Menu Settings:</p>
				<textarea style="width:100%; height:80px;"><?php echo json_encode( $menu_settings ); ?></textarea>
				<p>Save it as <code>menu.json</code> file.</p>

				<p>Redux Theme Options:</p>
				<textarea style="width:100%; height:80px;"><?php echo json_encode( $redux_options ); ?></textarea>
				<p>Save it as <code>options.json</code> file.</p>

				<p>Global Reading Settings:</p>
				<textarea style="width:100%; height:80px;"><?php echo json_encode( $reading_options ); ?></textarea>
				<p>Save it as <code>settings.json</code> file.</p>

				<?php
			} else {
				?>
				<h1><?php printf( esc_html__( 'Welcome to the setup wizard for %s', 'sink' ), $this->theme_nice_name ); ?></h1>
				<p><?php printf( __( 'Thank you for choosing the %s theme from ThemeForest. This quick setup wizard will help you configure your new website. This wizard will install the required WordPress plugins, default content, logo and tell you a little about Help &amp; Support options. <br/><strong>It should only take 5 minutes.</strong>', 'sink' ), $this->theme_nice_name ); ?></p>
				<p><?php esc_html_e( "No time right now? If you don't want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!", 'sink' ); ?></p>
				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
					   class="button-primary button button-large button-next"><?php esc_html_e( 'Let\'s Go!', 'sink' ); ?></a>
					<a href="<?php echo esc_url( wp_get_referer() ? wp_get_referer() : admin_url( '' ) ); ?>"
					   class="button button-large"><?php esc_html_e( 'Not right now', 'sink' ); ?></a>
				</p>
				<?php
			}
		}

		private function _generate_widget_export_data() {
			// Get all available widgets site supports
			$available_widgets = $this->_get_available_widgets();

			// Get all widget instances for each widget
			$widget_instances = array();
			foreach ( $available_widgets as $widget_data ) {
				// Get all instances for this ID base
				$instances = get_option( 'widget_' . $widget_data[ 'id_base' ] );
				// Have instances
				if ( ! empty( $instances ) ) {
					// Loop instances
					foreach ( $instances as $instance_id => $instance_data ) {
						// Key is ID (not _multiwidget)
						if ( is_numeric( $instance_id ) ) {
							$unique_instance_id                      = $widget_data[ 'id_base' ] . '-' . $instance_id;
							$widget_instances[ $unique_instance_id ] = $instance_data;
						}
					}
				}
			}

			// Gather sidebars with their widget instances
			$sidebars_widgets          = get_option( 'sidebars_widgets' ); // get sidebars and their unique widgets IDs
			$sidebars_widget_instances = array();
			foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

				// Skip inactive widgets
				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}

				// Skip if no data or not an array (array_version)
				if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
					continue;
				}
				// Loop widget IDs for this sidebar
				foreach ( $widget_ids as $widget_id ) {

					// Is there an instance for this widget ID?
					if ( isset( $widget_instances[ $widget_id ] ) ) {

						// Add to array
						$wdata = $widget_instances[ $widget_id ];
						if ( isset( $wdata[ 'nav_menu' ] ) ) {
							$t                   = get_term_by( 'id', $wdata[ 'nav_menu' ], 'nav_menu' );
							$wdata[ 'nav_menu' ] = $t->name;
						}

						//$sidebars_widget_instances[ $sidebar_id ][ $widget_id ] = $widget_instances[ $widget_id ];
						$sidebars_widget_instances[ $sidebar_id ][ $widget_id ] = $wdata;

					}
				}
			}

			// Filter pre-encoded data
			return $sidebars_widget_instances;
		}

		private function _get_available_widgets() {
			global $wp_registered_widget_controls;

			$widget_controls = $wp_registered_widget_controls;

			$available_widgets = array();

			foreach ( $widget_controls as $widget ) {

				if ( ! empty( $widget[ 'id_base' ] ) && ! isset( $available_widgets[ $widget[ 'id_base' ] ] ) ) { // no dupes

					$available_widgets[ $widget[ 'id_base' ] ][ 'id_base' ] = $widget[ 'id_base' ];
					$available_widgets[ $widget[ 'id_base' ] ][ 'name' ]    = $widget[ 'name' ];
				}
			}

			return $available_widgets;
		}

		public function get_next_step_link() {
			$keys = array_keys( $this->steps );

			return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ], remove_query_arg( 'translation_updated' ) );
		}

		/**
		 * Plugin setup
		 */
		public function envato_setup_default_plugins() {

			tgmpa_load_bulk_installer();
			// install plugins with TGM.
			if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS[ 'tgmpa' ] ) ) {
				die( 'Failed to find TGM' );
			}
			$url     = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'envato-setup' );
			$plugins = $this->_get_plugins();

			// copied from TGM

			$method = 'direct'; // Leave blank so WP_Filesystem can populate it as necessary.
			$fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.

			if ( FALSE === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, FALSE, FALSE, $fields ) ) ) {
				return TRUE; // Stop the normal page form from displaying, credential request form will be shown.
			}

			// Now we have some credentials, setup WP_Filesystem.
			if ( ! WP_Filesystem( $creds ) ) {
				// Our credentials were no good, ask the user for them again.
				request_filesystem_credentials( esc_url_raw( $url ), $method, TRUE, FALSE, $fields );

				return TRUE;
			}

			/* If we arrive here, we have the filesystem */

			?>
			<h1><?php esc_html_e( 'Required Plugins', 'sink' ); ?></h1>
			<form method="post">

				<?php
					$plugins = $this->_get_plugins();
					if ( count( $plugins[ 'all' ] ) ) {
						?>
						<p><?php esc_html_e( 'Your website needs a few essential plugins. The following plugins will be installed:', 'sink' ); ?></p>
						<ul class="envato-wizard-plugins">
							<?php foreach ( $plugins[ 'all' ] as $slug => $plugin ) { ?>
								<li data-slug="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $plugin[ 'name' ] ); ?>
									<span>
								<?php
									$keys = array();
									if ( isset( $plugins[ 'install' ][ $slug ] ) ) {
										$keys[] = 'Installation';
									}
									if ( isset( $plugins[ 'update' ][ $slug ] ) ) {
										$keys[] = 'Update';
									}
									if ( isset( $plugins[ 'activate' ][ $slug ] ) ) {
										$keys[] = 'Activation';
									}
									echo implode( ' and ', $keys ) . ' required';
								?>
							</span>

									<div class="spinner"></div>
								</li>
							<?php } ?>
						</ul>
						<?php
					} else {
						echo '<p><strong>' . esc_html_e( 'Good news! All plugins are already installed and up to date. Please continue.', 'sink' ) . '</strong></p>';
					} ?>

				<p><?php esc_html_e( 'You can add and remove plugins later on from within WordPress.', 'sink' ); ?></p>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
					   class="button-primary button button-large button-next"
					   data-callback="install_plugins"><?php esc_html_e( 'Continue', 'sink' ); ?></a>
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
					   class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'sink' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}

		private function _get_plugins() {
			$instance = call_user_func( array( get_class( $GLOBALS[ 'tgmpa' ] ), 'get_instance' ) );
			$plugins  = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( $instance->is_plugin_active( $slug ) && FALSE === $instance->does_plugin_have_update( $slug ) ) {
					// No need to display plugins if they are installed, up-to-date and active.
					continue;
				} else {
					$plugins[ 'all' ][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins[ 'install' ][ $slug ] = $plugin;
					} else {
						if ( FALSE !== $instance->does_plugin_have_update( $slug ) ) {
							$plugins[ 'update' ][ $slug ] = $plugin;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins[ 'activate' ][ $slug ] = $plugin;
						}
					}
				}
			}

			return $plugins;
		}

		public function ajax_plugins() {
			if ( ! check_ajax_referer( 'envato_setup_nonce', 'wpnonce' ) || empty( $_POST[ 'slug' ] ) ) {
				wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found', 'sink' ) ) );
			}
			$json = array();
			// send back some json we use to hit up TGM
			$plugins = $this->_get_plugins();
			// what are we doing with this plugin?
			foreach ( $plugins[ 'activate' ] as $slug => $plugin ) {
				if ( $_POST[ 'slug' ] == $slug ) {
					$json = array(
						'url'           => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => 'tgmpa-install-plugins',
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => - 1,
						'message'       => esc_html__( 'Activating Plugin', 'sink' ),
					);
					break;
				}
			}
			foreach ( $plugins[ 'update' ] as $slug => $plugin ) {
				if ( $_POST[ 'slug' ] == $slug ) {
					$json = array(
						'url'           => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => 'tgmpa-install-plugins',
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-update',
						'action2'       => - 1,
						'message'       => esc_html__( 'Updating Plugin', 'sink' ),
					);
					break;
				}
			}
			foreach ( $plugins[ 'install' ] as $slug => $plugin ) {
				if ( $_POST[ 'slug' ] == $slug ) {
					$json = array(
						'url'           => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => 'tgmpa-install-plugins',
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-install',
						'action2'       => - 1,
						'message'       => esc_html__( 'Installing Plugin', 'sink' ),
					);
					break;
				}
			}

			if ( $json ) {
				$json[ 'hash' ] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
				wp_send_json( $json );
			} else {
				wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success', 'sink' ) ) );
			}
			exit;

		}

		/**
		 * Contents setup
		 */
		public function envato_setup_default_content() {
			?>
			<h1><?php esc_html_e( 'Demo Contents', 'sink' ); ?></h1>
			<form method="post">
				<p><?php esc_html_e( 'It\'s time to insert some default content for your new WordPress website. Choose what you would like inserted below and click Continue.', 'sink' ); ?></p>
				<table class="envato-setup-pages" cellspacing="0">
					<thead>
					<tr>
						<td class="check"></td>
						<th class="item"><?php esc_html_e( 'Item', 'sink' ); ?></th>
						<th class="description"><?php esc_html_e( 'Description', 'sink' ); ?></th>
						<th class="status"><?php esc_html_e( 'Status', 'sink' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ( $this->_content_default_get() as $slug => $default ) { ?>
						<tr class="envato_default_content" data-content="<?php echo esc_attr( $slug ); ?>">
							<td>
								<input type="checkbox" name="default_content[pages]" class="envato_default_content"
								       id="default_content_<?php echo esc_attr( $slug ); ?>" value="1" checked>
							</td>
							<td><label
									for="default_content_<?php echo esc_attr( $slug ); ?>"><?php echo $default[ 'title' ]; ?></label>
							</td>
							<td class="description"><?php echo $default[ 'description' ]; ?></td>
							<td class="status"><span><?php echo $default[ 'pending' ]; ?></span>

								<div class="spinner"></div>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>

				<p><?php esc_html_e( 'Once inserted, this content can be managed from the WordPress admin dashboard.', 'sink' ); ?></p>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
					   class="button-primary button button-large button-next"
					   data-callback="install_content"><?php esc_html_e( 'Continue', 'sink' ); ?></a>
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
					   class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'sink' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}

		private function _content_default_get() {

			$content = array();

			$content[ 'data' ] = array(
				'title'            => esc_html__( 'Contents', 'sink' ),
				'description'      => esc_html__( 'This will create all demo data as seen in the demo.', 'sink' ),
				'pending'          => esc_html__( 'Pending.', 'sink' ),
				'installing'       => esc_html__( 'Installing contents.', 'sink' ),
				'success'          => esc_html__( 'Success.', 'sink' ),
				'install_callback' => array( $this, '_content_install_data' ),
			);

			$content[ 'menus' ] = array(
				'title'            => esc_html__( 'Menu', 'sink' ),
				'description'      => esc_html__( 'Insert default menu as seen in the demo.', 'sink' ),
				'pending'          => esc_html__( 'Pending.', 'sink' ),
				'installing'       => esc_html__( 'Installing Default Menu.', 'sink' ),
				'success'          => esc_html__( 'Success.', 'sink' ),
				'install_callback' => array( $this, '_content_install_menu' ),
			);

			$content[ 'widgets' ] = array(
				'title'            => esc_html__( 'Widgets', 'sink' ),
				'description'      => esc_html__( 'Insert default sidebar widgets as seen in the demo.', 'sink' ),
				'pending'          => esc_html__( 'Pending.', 'sink' ),
				'installing'       => esc_html__( 'Installing Default Widgets.', 'sink' ),
				'success'          => esc_html__( 'Success.', 'sink' ),
				'install_callback' => array( $this, '_content_install_widgets' ),
			);

			$content[ 'theme_options' ] = array(
				'title'            => esc_html__( 'Options', 'sink' ),
				'description'      => esc_html__( 'Configure Default Theme Options.', 'sink' ),
				'pending'          => esc_html__( 'Pending.', 'sink' ),
				'installing'       => esc_html__( 'Installing default theme options.', 'sink' ),
				'success'          => esc_html__( 'Success.', 'sink' ),
				'install_callback' => array( $this, '_content_install_theme_options' ),
			);

			$content[ 'settings' ] = array(
				'title'            => esc_html__( 'Settings', 'sink' ),
				'description'      => esc_html__( 'Configure Reading Settings.', 'sink' ),
				'pending'          => esc_html__( 'Pending.', 'sink' ),
				'installing'       => esc_html__( 'Installing Default Reading Settings.', 'sink' ),
				'success'          => esc_html__( 'Success.', 'sink' ),
				'install_callback' => array( $this, '_content_install_settings' ),
			);

			if ( class_exists( 'RevSlider' ) ) {
				$content[ 'revslider' ] = array(
					'title'            => esc_html__( 'Slider', 'sink' ),
					'description'      => esc_html__( 'Load slider Data.', 'sink' ),
					'pending'          => esc_html__( 'Pending.', 'sink' ),
					'installing'       => esc_html__( 'Importing revolution slider slide.', 'sink' ),
					'success'          => esc_html__( 'Success.', 'sink' ),
					'install_callback' => array( $this, '_content_install_slider' ),
				);
			}


			return apply_filters( 'hippo_envato_setup_content_default', $content, $this );

		}

		public function ajax_content() {

			$content = $this->_content_default_get();
			if ( ! check_ajax_referer( 'envato_setup_nonce', 'wpnonce' ) || empty( $_POST[ 'content' ] ) && isset( $content[ $_POST[ 'content' ] ] ) ) {
				wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No content Found', 'sink' ) ) );
			}

			$json         = FALSE;
			$this_content = $content[ $_POST[ 'content' ] ];

			if ( isset( $_POST[ 'proceed' ] ) ) {
				// install the content!

				if ( ! empty( $this_content[ 'install_callback' ] ) ) {
					if ( $result = call_user_func( $this_content[ 'install_callback' ] ) ) {
						$json = array(
							'done'    => 1,
							'message' => $this_content[ 'success' ],
							'debug'   => $result,
						);
					}
				}

			} else {

				$json = array(
					'url'      => admin_url( 'admin-ajax.php' ),
					'action'   => 'envato_setup_content',
					'proceed'  => 'true',
					'content'  => $_POST[ 'content' ],
					'_wpnonce' => wp_create_nonce( 'envato_setup_nonce' ),
					'message'  => $this_content[ 'installing' ],
				);
			}

			if ( $json ) {
				$json[ 'hash' ] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
				wp_send_json( $json );
			} else {
				wp_send_json( array( 'error' => 1, 'message' => esc_html__( 'Error', 'sink' ) ) );
			}

			exit;

		}

		/**
		 * Logo & Design
		 */

		public function envato_setup_logo_design() {

			?>
			<h1><?php esc_html_e( 'Logo &amp; Design', 'sink' ); ?></h1>
			<form method="post">
				<p><?php printf( __( 'Please add your logo below. For best results, the logo should be a transparent PNG. The logo can be changed at any time from the <strong>Customize &RightArrow; Site Identity </strong>Section', 'sink' ), $this->theme_nice_name ); ?></p>

				<table>
					<tr>
						<td>
							<div id="current-logo">
								<?php
									hippo_custom_logo();
								?>
							</div>
						</td>
						<td>
							<a href="#" class="button button-upload"><?php esc_html_e( 'Upload New Logo', 'sink' ); ?></a>
						</td>
					</tr>
				</table>


				<!--<p><?php /*_e( 'Please choose the color scheme for this website. The color scheme (along with font colors &amp; styles) can be changed at any time from the Appearance > Customize area in your dashboard.', 'sink' ); */ ?></p>

				<div class="theme-presets">
					<ul>
						<?php
					/*							$current_demo = get_theme_mod( 'theme_style', 'pink' );
												$demo_styles  = apply_filters( 'beautiful_default_styles', array() );
												foreach ( $demo_styles as $demo_name => $demo_style ) {
													*/ ?>
								<li<?php /*echo $demo_name == $current_demo ? ' class="current" ' : ''; */ ?>>
									<a href="#" data-style="<?php /*echo esc_attr( $demo_name ); */ ?>"><img
											src="<?php /*echo esc_url( $demo_style[ 'image' ] ); */ ?>"></a>
								</li>
							<?php /*} */ ?>
					</ul>
				</div>-->
				<p></p>

				<p><em>Please Note: Advanced changes to website graphics/colors may require extensive PhotoShop and Web
						Development knowledge.</p>


				<input type="hidden" name="new_logo_id" id="new_logo_id" value="">
				<input type="hidden" name="new_style" id="new_style" value="">

				<p class="envato-setup-actions step">
					<input type="submit" class="button-primary button button-large button-next"
					       value="<?php esc_attr_e( 'Continue', 'sink' ); ?>" name="save_step"/>
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
					   class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'sink' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}

		/**
		 * Save logo & design options
		 */
		public function envato_setup_logo_design_save() {
			check_admin_referer( 'envato-setup' );


			$new_logo_id = (int) $_POST[ 'new_logo_id' ];
			// save this new logo url into the database and calculate the desired height based off the logo width.
			// copied from dtbaker.theme_options.php
			if ( $new_logo_id ) {

				set_theme_mod( 'custom_logo', $new_logo_id );

				/*$attr       = wp_get_attachment_image_src( $new_logo_id, 'full' );
				$attr_thumb = wp_get_attachment_image_src( $new_logo_id, 'thumbnail' );
				if ( $attr && ! empty( $attr[ 1 ] ) && ! empty( $attr[ 2 ] ) ) {
					$ReduxFramework = ReduxFrameworkInstances::get_instance( velvet_option_name() );
					$ReduxFramework->set( 'logo', array(
						'url'       => $attr[ 0 ],
						'width'     => $attr[ 1 ],
						'height'    => $attr[ 2 ],
						'thumbnail' => $attr_thumb[ 0 ],
					) );

				}*/
			}

			/*$new_style   = $_POST[ 'new_style' ];
			$demo_styles = apply_filters( 'beautiful_default_styles', array() );
			if ( isset( $demo_styles[ $new_style ] ) ) {
				set_theme_mod( 'theme_style', $new_style );
			}*/

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			exit;
		}

		public function envato_setup_customize() {
			?>

			<h1>Theme Customization</h1>
			<p>
			<p><?php echo sprintf( __( 'Most changes to the website can be made through the <strong>%1$s Options</strong> menu from the WordPress
				admin. These include:', 'sink' ), $this->theme_nice_name ); ?>
			</p>

			<?php do_action( 'hippo_envato_setup_customize_features', $this ); ?>


			<p>To change the Sidebars go to <strong>Appearance &rightarrow; Widgets</strong>. Here widgets can be "drag
				&amp; dropped" into sidebars. More details in
				documentation.</p>

			<?php do_action( 'hippo_envato_setup_customize', $this ) ?>

			<?php if ( ! is_child_theme() ) { ?>
				<p>
					<em><strong>Advanced Users:</strong> If you are going to make changes to the theme source code please use a <a
							href="https://codex.wordpress.org/Child_Themes" target="_blank">Child Theme</a> rather than
						modifying the main theme HTML/CSS/PHP code. This allows the parent theme to receive updates
						without
						overwriting your source code changes. <br/>
						See <code><?php printf( '%s-child.zip', $this->theme_name ) ?></code> in the main folder for
						a sample.</em>
				</p>
			<?php } ?>


			<p class="envato-setup-actions step">
				<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
				   class="button button-primary button-large button-next"><?php esc_html_e( 'Continue', 'sink' ); ?></a>
			</p>

			<?php
		}

		public function envato_setup_help_support() {
			?>
			<h1>Help and Support</h1>
			<p>This theme comes with 6 months item support from purchase date (with the option to extend this period).
				This license allows you to use this theme on a single website. Please purchase an additional license to
				use this theme on another website.</p>
			<p>Item Support can be accessed
				from <?php printf( '<a href="%1$s" target="_blank">%1$s</a>', $this->support_link ) ?> and includes:</p>
			<ul>
				<li>Availability of the author to answer questions</li>
				<li>Answering technical questions about item features</li>
				<li>Assistance with reported bugs and issues</li>
				<li>Help with bundled 3rd party plugins</li>
			</ul>

			<p>Item Support <strong>DOES NOT</strong> Include:</p>
			<ul>
				<li>Customization services.</li>
				<li>Installation services ( but we can provide a quick start pack if you need full demo like
					setup. <?php printf( '<a href="%1$s" target="_blank">Read more about quick start pack.</a>', $this->qs_pack_link ) ?>
					)
				</li>
				<li>Help and Support for non-bundled 3rd party plugins (i.e. plugins you install yourself later on)</li>
			</ul>
			<p>More details about item support can be found in the ThemeForest <a
					href="http://themeforest.net/page/item_support_policy" target="_blank">Item Support Polity</a>. </p>
			<p class="envato-setup-actions step">
				<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
				   class="button button-primary button-large button-next"><?php esc_html_e( 'Agree and Continue', 'sink' ); ?></a>
				<?php wp_nonce_field( 'envato-setup' ); ?>
			</p>
			<?php
		}

		/**
		 * Final step
		 */
		public function envato_setup_ready() {
			?>
			<?php printf( '<a href="https://twitter.com/share" class="twitter-share-button"
			   data-url="%s?ref=EmranAhmed"
			   data-text="I just installed the %s #WordPress theme from #ThemeForest"
			   data-via="EnvatoMarket" data-size="large">Tweet</a>', $this->portfolio_link, $this->theme_nice_name ); ?>
			<script>
				(function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (!d.getElementById(id)) {
						js     = d.createElement(s);
						js.id  = id;
						js.src = "//platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, "script", "twitter-wjs"));
			</script>

			<h1><?php esc_html_e( 'Your Website is Ready!', 'sink' ); ?></h1>

			<p>Congratulations! The theme has been activated and your website is ready. Login to your WordPress
				dashboard to make changes and modify any of the default content to suit your needs.</p>
			<p>Please come back and <a href="<?php echo esc_url( $this->download_link ) ?>" target="_blank">leave a
					5-star rating</a>
				if you are happy with this theme.
				<br/>Follow <?php printf( '<a href="https://twitter.com/%1$s" target="_blank">@%1$s</a>', $this->twitter_username ) ?>
				on Twitter to see updates. Thanks! </p>

			<div class="envato-setup-next-steps">
				<div class="envato-setup-next-steps-first">
					<h2><?php esc_html_e( 'Next Steps', 'sink' ); ?></h2>
					<ul>
						<li class="setup-product">
							<?php printf( '<a class="button button-primary button-large"
						                             href="https://twitter.com/%1$s"
						                             target="_blank"> Follow @%1$s on Twitter</a>', $this->twitter_username ); ?>

						</li>
						<li class="setup-product">
							<a class="button button-next button-large"
							   href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'View your new website!', 'sink' ); ?>
							</a>
						</li>
					</ul>
				</div>
				<div class="envato-setup-next-steps-last">
					<h2><?php esc_html_e( 'More Resources', 'sink' ); ?></h2>
					<ul>
						<li class="documentation">
							<?php printf( '<a href="%s" target="_blank">Read the Theme Documentation</a>', $this->docs_link ) ?>
						</li>
						<li class="howto">
							<a href="https://wordpress.org/support/"
							   target="_blank"><?php esc_html_e( 'Learn how to use WordPress', 'sink' ); ?></a>
						</li>
						<li class="rating">
							<a href="<?php echo esc_url( $this->download_link ) ?>"
							   target="_blank"><?php esc_html_e( 'Leave an Item Rating', 'sink' ); ?></a>
						</li>
						<li class="support"><?php printf( '<a href="%s" target="_blank">Get Help and Support</a>', $this->support_link ) ?></li>
					</ul>
				</div>
			</div>
			<?php
		}

		private function _content_install_data() {
			return $this->_import_wordpress_xml_file( dirname( __FILE__ ) . "/content/data.xml" );
		}

		private function _import_wordpress_xml_file( $xml_file_path ) {

			if ( class_exists( 'Hippo_Content_Import' ) ) {
				ob_start();
				new Hippo_Content_Import( $xml_file_path );
				ob_end_clean();

				return TRUE;
			}

			return FALSE;
		}

		private function _content_install_menu() {

			$data = $this->_get_json( 'menu.json' );

			$menu_locations = get_theme_mod( 'nav_menu_locations' );

			foreach ( $data as $location => $title ) {

				$get_menus = wp_get_nav_menu_object( $title );

				if ( isset( $get_menus->term_id ) ) {
					$menu_locations[ $location ] = $get_menus->term_id;
				}

			}

			set_theme_mod( 'nav_menu_locations', $menu_locations );

			return TRUE;
		}

		private function _get_json( $file ) {

			$url    = wp_nonce_url( add_query_arg( array() ), 'envato-setup' );
			$method = 'direct'; // Leave blank so WP_Filesystem can populate it as necessary.
			if ( FALSE === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, FALSE, FALSE ) ) ) {
				return array();
			}

			// Now we have some credentials, setup WP_Filesystem.
			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( esc_url_raw( $url ), $method, FALSE, FALSE );

				return array();
			}

			global $wp_filesystem;

			$file = dirname( __FILE__ ) . '/content/' . basename( $file );

			if ( $wp_filesystem->exists( $file ) ) {
				$file = $wp_filesystem->get_contents( $file );

				return json_decode( $file, TRUE );
			} else {
				return array();
			}
		}

		private function _content_install_slider() {

			ob_start();
			$data = apply_filters( 'hippo_import_rev_slider_slides' );
			new Hippo_Rev_Slider_Import( $data );
			ob_end_clean();

			return TRUE;

		}

		private function _content_install_theme_options() {

			$file = $this->_get_json_abs_path( 'options.json' );

			ob_start();
			new Hippo_RedixThemeOption_Import( $file );
			ob_end_clean();

			return TRUE;
		}

		private function _get_json_abs_path( $file ) {
			if ( is_file( dirname( __FILE__ ) . '/content/' . basename( $file ) ) ) {
				return dirname( __FILE__ ) . '/content/' . basename( $file );
			}

			return array();
		}

		private function _content_install_widgets() {
			$file = $this->_get_json_abs_path( 'widgets.json' );

			ob_start();
			new Hippo_Widget_Import( $file );
			ob_end_clean();

			return TRUE;
		}

		private function _content_install_settings() {

			$data = $this->_get_json( 'settings.json' );

			// we also want to update the widget area manager options.
			foreach ( $data as $option => $value ) {

				if ( $option == 'page_on_front' or $option == 'page_for_posts' ) {

					$post = get_page_by_title( $value );
					update_option( $option, $post->ID );

				} else {
					update_option( $option, $value );
				}
			}

			return TRUE;
		}
	}

	new Hippo_Envato_Theme_Setup_Wizard();