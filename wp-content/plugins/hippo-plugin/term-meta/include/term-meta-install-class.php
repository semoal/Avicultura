<?php

	// Exit if accessed directly
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	/**
	 * Main WP Term Meta class
	 *
	 * This class is a big monkey patch to make WordPress term metadata possible. It
	 * facilitates the following functionality:
	 *
	 * - Creates & maintains the `wp_hippo_termmeta` table
	 * - Listens for new blogs
	 * - Listens for blog deletion and deletes the `wp_hippo_termmeta` table
	 * - Uses `WP_Meta_Query` and looks for    `meta_query` arguments
	 * - Deletes all metadata for terms when terms are deleted
	 * - Adds `wp_hippo_termmeta` to the main database object when appropriate
	 * -
	 *
	 * @link  https://make.wordpress.org/core/2013/07/28/potential-roadmap-for-taxonomy-meta-and-post-relationships/ Taxonomy Roadmap
	 * @link  http://core.trac.wordpress.org/ticket/10142 Term meta discussion
	 *
	 * @since 0.1.0
	 */

	if ( ! class_exists( 'Hippo_Term_Meta_Install' ) ):

		final class Hippo_Term_Meta_Install {

			private $term_meta_table_installed = TRUE;

			/**
			 * @var string File for plugin
			 */
			private $file = '';

			/**
			 * @var string URL to plugin
			 */
			private $url = '';

			/**
			 * @var string Path to plugin
			 */
			private $path = '';

			/**
			 * @var string Basename for plugin
			 */
			private $basename = '';

			/**
			 * @var object Database object (usually $GLOBALS['wpdb'])
			 */
			private $db = FALSE;

			/** Methods ***************************************************************/

			/**
			 * Hook into queries, admin screens, and more!
			 *
			 * @since 0.1.0
			 */
			public function __construct() {

				global $wpdb;

				// Setup plugin
				$this->file     = HIPPO_PLUGIN_FILE;
				$this->url      = HIPPO_PLUGIN_URL;
				$this->path     = HIPPO_PLUGIN_PATH;
				$this->basename = HIPPO_PLUGIN_BASENAME;
				$this->db       = $wpdb;


				// Force `termmeta` on to the global database object
				add_action( 'init', array( $this, 'add_termmeta_to_db_object' ) );
				add_action( 'switch_to_blog', array( $this, 'add_termmeta_to_db_object' ) );

				// Make `meta_query` arguments work
				add_filter( 'terms_clauses', array( $this, 'terms_clauses' ), 10, 3 );
				add_filter( 'get_terms_args', array( $this, 'get_terms_args' ), - 999 );

				// Delete all metadata when term is deleted
				add_action( 'deleted_term_taxonomy', array( $this, 'delete_all_meta_for_term' ) );

				// New site creation
				add_action( 'wpmu_new_blog', array( $this, 'new_blog' ) );

				// Add `termmeta` to array of tables to delete
				add_filter( 'wpmu_drop_tables', array( $this, 'drop_tables' ) );

				// Check if DB needs upgrading
				if ( is_admin() ) {
					add_action( 'admin_init', array( $this, 'admin_init' ) );
				}

			}

			/**
			 * Administration area hooks
			 *
			 * @since 0.1.0
			 */
			public function admin_init() {

				$this->maybe_upgrade_database();
			}

			/**
			 * Should a database update occur
			 *
			 * Runs on `admin_init`
			 *
			 * @since 0.1.0
			 */
			private function maybe_upgrade_database() {
				// Check DB for version
				$hippo_term_meta_table_installed = (bool) get_option( 'hippo_term_meta_table_installed', FALSE );
				// Needs
				if ( ! $hippo_term_meta_table_installed ) {
					$this->activate();
					update_option( 'hippo_term_meta_table_installed', TRUE );
				}
			}


			/**
			 * Modify the database object and add the `termmeta` table to it
			 *
			 * This is necessary to do directly because WordPress does have a mechanism
			 * for manipulating them safely. It's pretty fragile, but oh well.
			 *
			 * @since 0.1.0
			 */
			public function add_termmeta_to_db_object() {
				global $wpdb;
				$this->db->hippo_termmeta = $wpdb->hippo_termmeta = "{$this->db->prefix}hippo_termmeta";
			}

			/**
			 * When a new site is created, also install the `termmeta` table if this
			 * plugin is network activated on this network.
			 *
			 * @since 0.1.0
			 *
			 * @param int $site_id
			 */
			public function new_blog( $site_id = 0 ) {
				if ( is_plugin_active_for_network( $this->basename ) ) {
					$this->install( $site_id );
				}
			}

			/**
			 * Also drop the `termmeta` table
			 *
			 * @since 0.1.0
			 *
			 * @param array $tables
			 */
			public function drop_tables( $tables = array() ) {

				// Table to check for
				$table = "{$this->db->prefix}hippo_termmeta";

				// Add the `termmeta` table to the $tables array
				if ( ! isset( $tables[ $table ] ) ) {
					$tables[] = $table;
				}

				return $tables;
			}

			/**
			 * Install this plugin on a specific site
			 *
			 * @since 0.1.0
			 *
			 * @param int $site_id
			 */
			public function install( $site_id = 0 ) {

				// Not switched
				$switched = FALSE;

				// Should we switch?
				if ( FALSE !== $site_id ) {
					$switched = TRUE;
					switch_to_blog( $site_id );
				}

				// Create tables
				$this->create_termmeta_table();

				// Should we switch back?
				if ( TRUE === $switched ) {
					restore_current_blog();
				}
			}

			/**
			 * Activation hook
			 *
			 * Handles both single & multi site installations
			 *
			 * @since 0.1.0
			 *
			 * @param   bool $network_wide
			 */
			public function activate( $network_wide = FALSE ) {


				if ( is_multisite() ) {
					$network_wide = TRUE;
				}

				// If activated on a particular blog, just set it up there.
				if ( FALSE === $network_wide ) {
					$this->install( FALSE );

					return;
				}

				// Bail if a large network; you already know what to do
				if ( wp_is_large_network( 'sites' ) ) {
					return;
				}

				// Install on all sites in the network
				$sql      = "SELECT blog_id FROM {$this->db->blogs} WHERE site_id = %d";
				$prepared = $this->db->prepare( $sql, $this->db->siteid );
				$sites    = $this->db->get_col( $prepared );
				foreach ( $sites as $site_id ) {
					$this->install( $site_id );
				}


			}

			/**
			 * Create the `hippo_termmeta` table
			 *
			 * @since 0.1.0
			 */
			private function create_termmeta_table() {

				$charset_collate = '';
				if ( ! empty( $this->db->charset ) ) {
					$charset_collate = "DEFAULT CHARSET={$this->db->charset}";
				}

				if ( ! empty( $this->db->collate ) ) {
					$charset_collate .= " COLLATE {$this->db->collate}";
				}

				/*
				 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
				 * As of 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
				 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
				 */
				$max_index_length = 191;

				// Check for `dbDelta`
				if ( ! function_exists( 'dbDelta' ) ) {
					require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				}

				dbDelta( array(
					         "CREATE TABLE {$this->db->prefix}hippo_termmeta (
				meta_id bigint(20) NOT NULL AUTO_INCREMENT,
				term_id bigint(20) NOT NULL,
				hippo_term_id bigint(20) NOT NULL,
				meta_key varchar(255) DEFAULT NULL,
				meta_value longtext,
				PRIMARY KEY (meta_id),
				KEY term_id (term_id),
				KEY meta_key (meta_key($max_index_length))
			) {$charset_collate};"
				         ) );

				// Make doubly sure the global database object is modified
				$this->add_termmeta_to_db_object();
			}

			/** Term Meta Query *******************************************************/

			/**
			 * Filter `term_clauses` and add support for a `meta_query` argument
			 *
			 * @since 0.1.0
			 *
			 * @param array $pieces     Terms query SQL clauses.
			 * @param array $taxonomies An array of taxonomies.
			 * @param array $args       An array of terms query arguments.
			 *
			 * @return Array of query pieces, maybe modifed
			 */
			public function terms_clauses( $pieces = array(), $taxonomies = array(), $args = array() ) {

				// Maybe do a meta query
				if ( ! empty( $args[ 'meta_query' ] ) ) {

					// Make doubly sure global database object is prepared
					$this->add_termmeta_to_db_object();

					// Get the meta query parts
					$meta_query = new WP_Meta_Query( $args[ 'meta_query' ] );
					$meta_query->parse_query_vars( $args );

					// Combine pieces & meta-query clauses
					if ( ! empty( $meta_query->queries ) ) {

						/**
						 * It's possible in a future version of WordPress that our
						 * `term_id` usage might need to be swapped to `term_taxonomy_id`.
						 */
						$meta_clauses = $meta_query->get_sql( 'term', 'tt', 'term_id', $taxonomies );
						$pieces[ 'join' ] .= $meta_clauses[ 'join' ];
						$pieces[ 'where' ] .= $meta_clauses[ 'where' ];
					}
				}

				// Return possibly modified pieces array
				return $pieces;
			}

			/**
			 * Filter `get_terms_args` and add an empty `meta_query` argument.
			 *
			 * This is mostly a dumb hack to ensure that `meta_query` starts as an
			 * available argument in the `$args` array, to get developers familiar with
			 * it eventually maybe possibly being available all of the time.
			 *
			 * If we're being honest with each other, this method isn't even really
			 * necessary. It's just me being pedantic about environment variables, and
			 * hoping that if someone else comes along and sees how much care I took to
			 * be this thorough, they'll say "hey good job" or "that was really cool
			 * that you did that thing that no one else would have thought to do."
			 *
			 * @since 0.1.0
			 *
			 * @param  array $args An array of get_term() arguments.
			 *
			 * @return array  Array of arguments with `meta_query` parameter added
			 */
			public function get_terms_args( $args = array() ) {
				return wp_parse_args( $args, array(
					'meta_query' => ''
				) );
			}

			/**
			 * Delete all metadata for a given term ID
			 *
			 * This bit is largely taken from `wp_delete_post()` as there is no meta-
			 * data function specifically designed to facilitate the deletion of all
			 * meta associated with a given object.
			 *
			 * @since 0.1.0
			 *
			 * @param  int $term_id Term ID
			 */
			public function delete_all_meta_for_term( $term_id = 0 ) {

				// Make doubly sure global database object is prepared
				$this->add_termmeta_to_db_object();

				// Query the DB for metad ID's to delete
				$query         = "SELECT meta_id FROM {$this->db->hippo_termmeta} WHERE term_id = %d";
				$prepared      = $this->db->prepare( $query, $term_id );
				$term_meta_ids = $this->db->get_col( $prepared );

				// Bail if no term metadata to delete
				if ( empty( $term_meta_ids ) ) {
					return;
				}

				// Loop through and delete all meta by ID
				foreach ( $term_meta_ids as $mid ) {
					delete_metadata_by_mid( 'hippo_term', $mid );
				}
			}
		}
	endif; // class_exists( 'Hippo_Term_Meta_Install' )

	if ( ! function_exists( 'Hippo_Term_Meta_Install' ) ):

		/**
		 * Instantiate the main WordPress Term Meta class.
		 *
		 * @since 0.1.0
		 */
		function Hippo_Term_Meta_Install() {
			new Hippo_Term_Meta_Install();
		}

		add_action( 'plugins_loaded', 'Hippo_Term_Meta_Install' );

	endif; // function_exists( 'Hippo_Term_Meta_Install' )