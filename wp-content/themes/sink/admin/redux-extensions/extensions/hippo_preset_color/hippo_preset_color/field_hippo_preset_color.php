<?php
	/**
	 * Redux Framework is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 2 of the License, or
	 * any later version.
	 *
	 * Redux Framework is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
	 *
	 * @package     ReduxFramework
	 * @author      ThemeHippo
	 * @version     1.0
	 */

	// Exit if accessed directly
	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	// Don't duplicate me!
	if ( ! class_exists( 'ReduxFramework_hippo_preset_color' ) ) :

		/**
		 * Main ReduxFramework_hippo_preset_color class
		 *
		 * @since       1.0.0
		 */
		class ReduxFramework_hippo_preset_color extends ReduxFramework {

			/**
			 * Field Constructor.
			 *
			 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			function __construct( $field = array(), $value = '', $parent ) {


				$this->parent = $parent;
				$this->field  = $field;
				$this->value  = $value;

				if ( empty( $this->extension_dir ) ) {

					$this->extension_dir = trailingslashit( locate_template( sprintf( 'admin/redux-extensions/extensions/%1$s/%1$s', $this->field[ 'type' ] ) ) );
					$this->extension_url = trailingslashit( hippo_locate_template_uri( sprintf( 'admin/redux-extensions/extensions/%1$s/%1$s', $this->field[ 'type' ] ) ) );
				}

				// Set default args for this field to avoid bad indexes. Change this to anything you use.
				$defaults    = array(
					'options'          => array(),
					'stylesheet'       => '',
					'output'           => TRUE,
					'enqueue'          => TRUE,
					'enqueue_frontend' => TRUE
				);
				$this->field = wp_parse_args( $this->field, $defaults );


			}

			/**
			 * Field Render Function.
			 *
			 * Takes the vars and outputs the HTML for the field in the settings
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			public function render() {


				echo '<input data-id="' . esc_attr( $this->field[ 'id' ] ) . '" name="' . esc_attr( $this->field[ 'name' ] . $this->field[ 'name_suffix' ] ) . '" id="' . esc_attr( $this->field[ 'id' ] ) . '-color" class="hippo-redux-color hippo-redux-color-init ' . esc_attr( $this->field[ 'class' ] ) . '"  type="text" value="' . ( ( ! isset( $this->value ) || empty( $this->value ) ) ? esc_attr( $this->field[ 'default' ] ) : esc_attr( $this->value ) ) . '" data-oldcolor=""  data-default-color="' . ( isset( $this->field[ 'default' ] ) ? esc_attr( $this->field[ 'default' ] ) : "" ) . '" />';
				echo '<input type="hidden" class="hippo-redux-saved-color" id="' . esc_attr( $this->field[ 'id' ] ) . '-saved-color' . '" value="">';

				if ( ! isset( $this->field[ 'transparent' ] ) || $this->field[ 'transparent' ] !== FALSE ) {

					$tChecked = "";

					if ( $this->value == "transparent" ) {
						$tChecked = ' checked="checked"';
					}

					echo '<label for="' . esc_attr( $this->field[ 'id' ] ) . '-transparency" class="color-transparency-check"><input type="checkbox" class="checkbox color-transparency ' . esc_attr( $this->field[ 'class' ] ) . '" id="' . esc_attr( $this->field[ 'id' ] ) . '-transparency" data-id="' . esc_attr( $this->field[ 'id' ] ) . '-color" value="1"' . $tChecked . '> ' . esc_html__( 'Transparent', 'sink' ) . '</label>';
				}
			}

			/**
			 * Enqueue Function.
			 *
			 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			public function enqueue() {

				// $extension = ReduxFramework_extension_hippo_preset::getInstance();

				wp_enqueue_script(
					'hippo-redux-field-color-js',
					$this->extension_url . 'field_hippo_preset_color.js',
					array( 'jquery', 'wp-color-picker', 'redux-js' ),
					time(),
					TRUE
				);


			}

			/**
			 * Output Function.
			 *
			 * Used to enqueue to the front-end
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			public function output() {

				if ( $this->field[ 'enqueue_frontend' ] ) {

				}

			}


		}
	endif;



