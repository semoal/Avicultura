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
	 * @author      Dovy Paukstys
	 * @version     3.1.5
	 */

	// Exit if accessed directly
	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	// Don't duplicate me!
	if ( ! class_exists( 'ReduxFramework_hippo_preset' ) ) :

		/**
		 * Main ReduxFramework_hippo_preset class
		 *
		 * @since       1.0.0
		 */
		class ReduxFramework_hippo_preset extends ReduxFramework {

			/**
			 * @param array  $field
			 * @param string $value
			 * @param array  $parent
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

				// HTML output goes here


				//print_r($this->parent->args->opt_name); die;


				if ( ! empty( $this->field[ 'options' ] ) ) {
					echo '<div class="redux-table-container">';
					echo '<ul class="redux-image-select">';

					$x = 1;

					foreach ( $this->field[ 'options' ] as $k => $v ) {


						$style = '';

						if ( ! empty( $this->field[ 'width' ] ) ) {
							$style .= 'width: ' . $this->field[ 'width' ];

							if ( is_numeric( $this->field[ 'width' ] ) ) {
								$style .= 'px';
							}

							$style .= ';';
						} else {
							$style .= " width: 100%; ";
						}

						if ( ! empty( $this->field[ 'height' ] ) ) {
							$style .= 'height: ' . $this->field[ 'height' ];

							if ( is_numeric( $this->field[ 'height' ] ) ) {
								$style .= 'px';
							}

							$style .= ';';
						}


						$theValue = $k;

						$selected = ( checked( $this->value, $theValue, FALSE ) != '' ) ? ' redux-image-select-selected' : '';

						$presets   = '';
						$is_preset = FALSE;

						$this->field[ 'class' ] .= ' noUpdate ';

						$is_preset_class = $is_preset ? '-preset-' : ' ';

						echo '<li class="redux-image-select">';

						echo '<label class="' . esc_attr( $selected ) . ' redux-image-select' . esc_attr( $is_preset_class . $this->field[ 'id' ] . '_' . $x ) . '" for="' . esc_attr( $this->field[ 'id' ] ) . '_' . ( array_search( $k, array_keys( $this->field[ 'options' ] ) ) + 1 ) . '">';

						echo '<input type="radio" class="' . esc_attr( $this->field[ 'class' ] ) . '" id="' . esc_attr( $this->field[ 'id' ] ) . '_' . ( array_search( $k, array_keys( $this->field[ 'options' ] ) ) + 1 ) . '" name="' . esc_attr( $this->field[ 'name' ] . $this->field[ 'name_suffix' ] ) . '" value="' . esc_attr( $theValue ) . '" ' . checked( $this->value, $theValue, FALSE ) . $presets . '/>';

						//$locate_image = locate_template('admin/presets/'.$v.'.png');
						$image = get_template_directory_uri() . '/admin/presets/' . $k . '.png';

						echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $v ) . '" style="' . esc_attr( $style ) . '"' . $presets . ' />';

						echo '</label>';

						echo '</li>';

						$x ++;
					}

					echo '</ul>';
					echo '</div>';
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
					'redux-field-hippo-preset-js',
					$this->extension_url . 'field_hippo_preset.js',
					array( 'jquery', 'redux-js' ),
					time(),
					TRUE
				);

				wp_enqueue_style(
					'redux-field-hippo-preset-css',
					$this->extension_url . 'field_hippo_preset.css',
					time(),
					TRUE
				);

				/* wp_enqueue_script(
					'redux-field-icon-select-js',
					$this->extension_url . 'field_hippo_preset.js',
					array( 'jquery' ),
					time(),
					true
				);

				wp_enqueue_style(
					'redux-field-icon-select-css',
					$this->extension_url . 'field_hippo_preset.css',
					time(),
					true
				); */

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



