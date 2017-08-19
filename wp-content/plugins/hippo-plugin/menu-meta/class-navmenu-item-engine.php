<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	class Hippo_Menu_Item_Meta_Engine {

		private static $instance         = NULL;
		private        $_meta_key_prefix = 'hippo_menu-item_meta_';
		private        $_fields          = array();

		public function __construct() {
			add_action( 'admin_init', array( $this, 'setup' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'on_admin_script_load' ) );
		}

		public function on_admin_script_load() {

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'em-menu-meta-style', EM_MENU_META_URL . '/css/style.css' );
		}

		public function setup() {

			$fields = apply_filters( 'hippo_nav_menu_item_meta', self::getInstance()->_fields );

			if ( empty( $fields ) ) {
				return;
			}

			foreach ( $fields as $field_key => $field ) {
				self::getInstance()->_fields[ $field_key ] = $fields[ $field_key ];
			}

			add_filter( 'wp_edit_nav_menu_walker', array( self::getInstance(), 'wp_edit_nav_menu_walker_call' ) );

			add_action( 'save_post', array( self::getInstance(), 'save_menu_meta' ), 10, 2 );
		}

		public static function getInstance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new Hippo_Menu_Item_Meta_Engine();
			}

			return self::$instance;
		}

		public function wp_edit_nav_menu_walker_call() {
			return 'Hippo_Walker_Nav_Menu_Edit';
		}

		public function save_menu_meta( $post_id, $post ) {

			if ( $post->post_type !== 'nav_menu_item' ) {
				return;
			}

			foreach ( self::getInstance()->_fields as $field_name => $field ) {

				$form_field_name = 'menu-item-' . $field_name;

				$key = $this->_meta_key_prefix . $field_name;

				if ( isset( $_POST[ $form_field_name ][ $post_id ] ) ) {
					$value = stripslashes( $_POST[ $form_field_name ][ $post_id ] );
					update_post_meta( $post_id, $key, $value );
				} else {
					delete_post_meta( $post_id, $key );
				}
			}
		}

		public function generate_field( $item, $depth, $args ) {

			$fields = '';
			foreach ( self::getInstance()->_fields as $key_name => $field ) {

				if ( isset( $field[ 'depth' ] ) and is_array( $field[ 'depth' ] ) and ! in_array( $depth, $field[ 'depth' ] ) ) {
					continue;
				} elseif ( isset( $field[ 'depth' ] ) and absint( $field[ 'depth' ] ) !== $depth ) {
					continue;
				}

				$field[ 'name' ]    = $key_name;
				$field[ 'default' ] = ( isset( $field[ 'default' ] ) ) ? $field[ 'default' ] : '';
				$value              = self::getInstance()
				                          ->get_menu_meta( $item->ID, $field[ 'name' ], $field[ 'default' ] );

				if ( $field[ 'type' ] == 'checkbox' or $field[ 'type' ] == 'radio' ) {
					$field[ 'selected' ] = $value;
				} else {
					$field[ 'value' ] = $value;
				}

				$field[ 'id' ]         = $item->ID;
				$field[ 'size' ]       = ( isset( $field[ 'size' ] ) ) ? $field[ 'size' ] : 'wide';
				$field[ 'size' ]       = ( $field[ 'size' ] == 'half' ) ? 'thin' : $field[ 'size' ];
				$field[ 'dependency' ] = ( isset( $field[ 'dependency' ] ) ) ? $field[ 'dependency' ] : array();

				$field[ 'desc' ] = ( isset( $field[ 'desc' ] ) ) ? $field[ 'desc' ] : FALSE;

				$field[ 'depends' ] = array();

				foreach ( $field[ 'dependency' ] as $dep_opt_arr ) {
					foreach ( $dep_opt_arr as $dep_field => $dep_opt ) {
						$field[ 'depends' ][][ '#edit-menu-item-' . $dep_field . '-' . $field[ 'id' ] ] = $dep_opt;
					}
				}

				$fields .= self::getInstance()->attribute( $field );
			}

			return $fields;
		}

		public static function get_menu_meta( $id, $name, $default = NULL ) {

			$data = get_post_meta( $id, self::getInstance()->_meta_key_prefix . $name, TRUE );

			$has_meta = (bool) $data;
			if ( ! $has_meta ) {
				$data = $default;
			}

			return $data;
		}

		public function attribute( $field ) {
			ob_start();

			$depends = empty( $field[ 'depends' ] ) ? '' : "data-depends='" . json_encode( $field[ 'depends' ] ) . "'";

			?>
			<div <?php echo $depends ?>
				class="additional-menu-field additional-menu-field-<?php echo $field[ 'name' ] ?> description description-<?php echo $field[ 'size' ] ?>">
				<?php
					switch ( $field[ 'type' ] ) {
						case 'checkbox':
						case 'radio':
							?>
							<label
								for="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>">
								<input type="<?php echo $field[ 'type' ] ?>"
								       id="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>"
								       class="widefat code edit-menu-item-<?php echo $field[ 'name' ] ?>"
								       name="menu-item-<?php echo $field[ 'name' ] ?>[<?php echo $field[ 'id' ] ?>]"
								       value="<?php echo esc_attr( $field[ 'value' ] ) ?>" <?php checked( $field[ 'selected' ], $field[ 'value' ] ) ?>>
								<?php echo $field[ 'label' ] ?></label>
							<?php
							break;

						case 'textarea':
							?>
							<label
								for="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>">
								<?php echo $field[ 'label' ] ?><br>
                                <textarea
	                                id="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>"
	                                class="widefat code edit-menu-item-<?php echo $field[ 'name' ] ?>"
	                                name="menu-item-<?php echo $field[ 'name' ] ?>[<?php echo $field[ 'id' ] ?>]"><?php echo $field[ 'value' ] ?></textarea>
							</label>
							<?php
							break;

						case 'select':
						case 'select2':
							$field[ 'options' ] = isset( $field[ 'options' ] ) ? $field[ 'options' ] : array();
							$class_name         = ( $field[ 'type' ] == 'select2' ) ? 'hippo-plugin-select2' : '';
							?>
							<label
								for="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>">
								<?php echo $field[ 'label' ] ?><br>
								<select
									id="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>"
									class="widefat code edit-menu-item-<?php echo $field[ 'name' ] ?> <?php echo $class_name ?>"
									name="menu-item-<?php echo $field[ 'name' ] ?>[<?php echo $field[ 'id' ] ?>]">

									<?php foreach ( $field[ 'options' ] as $key => $value ) { ?>
										<option
											value="<?php echo $key ?>" <?php selected( $key, $field[ 'value' ] ) ?>><?php echo $value ?></option>
									<?php } ?>
								</select>
							</label>
							<?php
							break;

						case 'icon':
							$field[ 'options' ] = isset( $field[ 'options' ] ) ? $field[ 'options' ] : array();
							?>
							<label
								for="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>">
								<?php echo $field[ 'label' ] ?><br>
								<select
									id="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>"
									class="widefat code edit-menu-item-<?php echo $field[ 'name' ] ?> hippo-plugin-select2-icon"
									name="menu-item-<?php echo $field[ 'name' ] ?>[<?php echo $field[ 'id' ] ?>]">
									<option value=""> -- Select Icon --</option>
									<?php foreach ( $field[ 'options' ] as $key => $value ) { ?>
										<option
											value="<?php echo $key ?>" <?php selected( $key, $field[ 'value' ] ) ?>><?php echo $value ?></option>
									<?php } ?>
								</select>
							</label>
							<?php
							break;

						case 'text':
						default:
							?>
							<label
								for="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>">
								<?php echo $field[ 'label' ] ?><br>
								<input
									type="<?php echo $field[ 'type' ] ?>"
									id="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>"
									class="widefat code edit-menu-item-<?php echo $field[ 'name' ] ?>"
									name="menu-item-<?php echo $field[ 'name' ] ?>[<?php echo $field[ 'id' ] ?>]"
									value="<?php echo esc_attr( $field[ 'value' ] ) ?>">
							</label>
							<?php
							break;

						case 'color':
							?>
							<label
								for="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>">
								<?php echo $field[ 'label' ] ?><br>
								<input
									type="text"
									id="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>"
									class="widefat code edit-menu-item-<?php echo $field[ 'name' ] ?> hippocolorpicker"
									name="menu-item-<?php echo $field[ 'name' ] ?>[<?php echo $field[ 'id' ] ?>]"
									value="<?php echo esc_attr( $field[ 'value' ] ) ?>">
							</label>
							<?php
							break;

						case 'image':
							?>
							<label
								for="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>">
								<?php echo $field[ 'label' ] ?><br>
							</label>


							<div class="meta-image-field-wrapper">
								<div style="float: left; margin-right: 10px;"><img
										data-placeholder="<?php echo esc_url( $this->hippo_placeholder_img_src() ); ?>"
										src="<?php echo esc_url( $this->get_img_src( $field[ 'value' ] ) ); ?>"
										width="60px"
										height="60px"/>
								</div>
								<div style="line-height: 60px;">
									<input type="hidden"
									       id="edit-menu-item-<?php echo $field[ 'name' ] ?>-<?php echo $field[ 'id' ] ?>"
									       name="menu-item-<?php echo $field[ 'name' ] ?>[<?php echo $field[ 'id' ] ?>]"
									       value="<?php echo esc_attr( $field[ 'value' ] ) ?>"/>
									<button type="button"
									        class="hippo_upload_image_button button button-primary button-small"><?php _e( 'Upload / Add image', 'hippo-plugin' ); ?></button>
									<button type="button"
									        style="<?php echo( empty( $field[ 'value' ] ) ? 'display:none' : '' ) ?>"
									        class="hippo_remove_image_button button button-danger button-small"><?php _e( 'Remove image', 'hippo-plugin' ); ?></button>
								</div>
								<div class="clear clearfix"></div>
							</div>


							<?php
							break;
					}

					echo( ! empty( $field[ 'desc' ] ) ? '<br><span class="description">' . esc_html( $field[ 'desc' ] ) . '</span>' : '' );
				?>
			</div>
			<?php
			return ob_get_clean();
		}

		public function hippo_placeholder_img_src() {
			return HIPPO_PLUGIN_URL . 'images/placeholder.png';
		}

		public function get_img_src( $thumbnail_id = FALSE ) {
			if ( ! empty( $thumbnail_id ) ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = $this->hippo_placeholder_img_src();
			}

			return $image;
		}
	}


	if ( ! function_exists( 'hippo_menu_item_meta_engine_init' ) ):

		function hippo_menu_item_meta_engine_init() {
			Hippo_Menu_Item_Meta_Engine::getInstance();
		}

		add_action( 'plugins_loaded', 'hippo_menu_item_meta_engine_init' );
	endif;


	if ( ! function_exists( 'hippo_get_menu_meta' ) ) :
		function hippo_get_menu_meta( $id, $name, $default = NULL ) {
			return Hippo_Menu_Item_Meta_Engine::getInstance()->get_menu_meta( $id, $name, $default );
		}
	endif;


/**
 * Adding Meta Field
 *
 *
 *    add_filter('hippo_nav_menu_item_meta', function ($fields) {
 *
 * $fields[ 'menucolumnclass' ] = array(
 * 'type'       => 'text',
 * 'label'      => esc_html__( 'Mega Menu Column Class', 'sink' ),
 * 'default'    => 'col-md-10',
 * 'depth'      => 0,
 * 'dependency' => array(
 * array( 'widgets' => array( 'type' => '!empty' ) )
 * )
 * )
 *
 * return $fields;
 * });
 */

/**
 * On Nav Walker
 *
 *
 * echo hippo_get_menu_meta($item->ID,'color');
 *
 */





