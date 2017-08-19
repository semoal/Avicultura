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
	if ( ! class_exists( 'ReduxFramework_hippo_repeater' ) ) :

		/**
		 * Main ReduxFramework_hippo_repeater class
		 *
		 * @since       1.0.0
		 */
		class ReduxFramework_hippo_repeater extends ReduxFramework {


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
					'fields'            => array(),
					'stylesheet'        => '',
					'output'            => TRUE,
					'enqueue'           => TRUE,
					'enqueue_frontend'  => TRUE,
					'delete_field_text' => esc_html__( 'Delete', 'sink' ),
					'new_field_text'    => esc_html__( 'Add New', 'sink' ),
				);
				$this->field = wp_parse_args( $this->field, $defaults );

			}


			public function repeater_start() {
				echo '</td></tr></table>';
			}

			public function repeater_end() {
				echo '<table class="form-table no-border"><tbody><tr><th></th><td>';
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

				echo $this->repeater_start();

				?>
				<!-- Start -->


				<table class="form-table hippo-repeatable-fields-table"
				       data-fields='<?php echo json_encode( array_map( array(
					                                                       $this,
					                                                       'get_available_fields'
				                                                       ), $this->field[ 'fields' ] ) ) ?>'>
					<tbody>


					<tr>
						<td colspan="2"><?php

								echo '<div class="redux_field_th">';

								if ( ! empty( $this->field[ 'title' ] ) ) {
									echo $this->field[ 'title' ];
								}

								if ( ! empty( $this->field[ 'subtitle' ] ) ) {
									echo '<span class="description">' . $this->field[ 'subtitle' ] . '</span>';
								}

								echo '</div>'; ?>
						</td>
					</tr>
					<?php


						$count = 0;
						foreach ( (array) $this->value as $type ) {
							$count = count( $type );
							break;
						}


						for ( $i = 0; $i < $count; $i ++ ) { ?>
							<tr class="hippo-repeatable-fields">
								<td>
									<?php
										foreach ( $this->field[ 'fields' ] as $key => $field ) { ?>

											<table class="form-table">
												<tbody>
												<tr>
													<th>
														<?php

															echo '<div class="redux_field_th">';

															if ( ! empty( $field[ 'title' ] ) ) {
																echo $field[ 'title' ];
															}

															if ( ! empty( $field[ 'subtitle' ] ) ) {
																echo '<span class="description">' . $field[ 'subtitle' ] . '</span>';
															}

															echo '</div>'; ?>

													</th>
													<td>

														<?php $this->generate_fields( $field, ( isset( $field[ 'default' ] ) ? $field[ 'default' ] : NULL ), $i ) ?>

													</td>
												</tr>
												</tbody>
											</table>


										<?php }
									?>
								</td>

								<td>
									<?php if ( $i > 0 ) { ?>
										<a href="javascript:;"
										   class="hippo-repeatable-delete-field button"><?php echo esc_html( $this->field[ 'delete_field_text' ] ) ?></a>
									<?php } ?> &nbsp;
								</td>

							</tr>
						<?php } ?>
					<tr class="hippo-repeatable-fields-table-add-new">
						<td>&nbsp;</td>
						<td><a href="javascript:;"
						       class="hippo-repeatable-new-field button button-primary"><?php echo esc_html( $this->field[ 'new_field_text' ] ) ?></a>
						</td>
					</tr>
					</tbody>
				</table>

				<script type="text/template">
					<tr class="hippo-repeatable-fields">
						<td>
							<?php
								foreach ( $this->field[ 'fields' ] as $key => $field ) { ?>
									<table class="form-table">
										<tbody>
										<tr>
											<th>
												<?php
													echo '<div class="redux_field_th">';

													if ( ! empty( $field[ 'title' ] ) ) {
														echo $field[ 'title' ];
													}

													if ( ! empty( $field[ 'subtitle' ] ) ) {
														echo '<span class="description">' . $field[ 'subtitle' ] . '</span>';
													}

													echo '</div>'; ?>
											</th>
											<td>
												<?php $this->generate_fields( $field, ( isset( $field[ 'default' ] ) ? $field[ 'default' ] : NULL ), '{{index}}' ) ?>
											</td>
										</tr>
										</tbody>
									</table>
								<?php } ?>
						</td>

						<td>
							<a href="javascript:;"
							   class="hippo-repeatable-delete-field button"><?php echo esc_html( $this->field[ 'delete_field_text' ] ) ?></a>
						</td>

					</tr>
				</script>


				<!-- End -->

				<?php

				echo $this->repeater_end();

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

			public function get_available_fields( $arr ) {
				return $arr[ 'type' ];
			}

			public function enqueue() {

				// $extension = ReduxFramework_extension_hippo_preset::getInstance();

				//print_r( $this->field[ 'fields' ] );


				wp_enqueue_script(
					'redux-field-hippo-repeater-js',
					$this->extension_url . 'field_hippo_repeater.js',
					array( 'jquery', 'redux-js' ),
					time(),
					TRUE
				);

				wp_enqueue_style(
					'redux-field-hippo-hippo_repeater-css',
					$this->extension_url . 'field_hippo_repeater.css',
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


			public function generate_fields( $field, $default_value, $index = 0 ) {


				//print_r( $this->parent ); die;

				$field_class = "ReduxFramework_{$field['type']}";

				//print_r($this->parent->args); die;

				$opt_name = $this->parent->args[ 'opt_name' ];


				if ( ! class_exists( $field_class ) ) {

					$class_file = apply_filters( "redux/{$opt_name}/field/class/{$field['type']}", self::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );

					if ( $class_file ) {
						if ( file_exists( $class_file ) ) {
							require_once( $class_file );
						}
					}
				}

				////

				//print_r($this->value); die;

				if ( class_exists( $field_class ) ) {


					$value = isset ( $this->value[ $field[ 'id' ] ][ $index ] ) ? $this->value[ $field[ 'id' ] ][ $index ] : NULL;

					if ( $value == NULL ) {
						$value = $default_value;
					}

					//print_r( $this->value );
					//die;

					$field[ 'name' ] = $this->field[ 'name' ] . "[{$field[ 'id' ]}][{$index}]";
					$field[ 'id' ]   = strtr( $this->field[ 'name' ] . "[{$field[ 'id' ]}][{$index}]", array(
						'[' => '_',
						']' => ''
					) );

					//$field[ 'id' ]->parent->args['opt_name']

					if ( ! isset ( $field[ 'name_suffix' ] ) ) {
						$field[ 'name_suffix' ] = "";
					}

					if ( ! isset ( $field[ 'class' ] ) ) {
						$field[ 'class' ] = "";
					}

					//$field[ 'class' ] = ( isset( $field[ 'class' ] ) ? $field[ 'class' ] : '' );


					do_action_ref_array( "redux/field/{$opt_name}/{$field['type']}/render/before", array(
						&$field,
						&$value
					) );


					do_action_ref_array( "redux/field/{$opt_name}/render/before", array(
						&$field,
						&$value
					) );


					$render = new $field_class ( $field, $value, $this->parent );


					ob_start();

					$render->render();

					$_render = apply_filters( "redux/field/{$opt_name}/{$field['type']}/render/after", ob_get_contents(), $field );

					$_render = apply_filters( "redux/field/{$opt_name}/render/after", $_render, $field );

					ob_end_clean();

					//save the values into a unique array in case we need it for dependencies
					$this->fieldsValues[ $field[ 'id' ] ] = ( isset ( $value[ 'url' ] ) && is_array( $value ) ) ? $value[ 'url' ] : $value;

					//create default data und class string and checks the dependencies of an object
					$class_string = '';
					$data_string  = '';

					$this->check_dependencies( $field );


					do_action_ref_array( "redux/field/{$opt_name}/{$field['type']}/fieldset/before/{$opt_name}", array(
						&$field,
						&$value
					) );


					do_action_ref_array( "redux/field/{$opt_name}/fieldset/before/{$opt_name}", array(
						&$field,
						&$value
					) );

					//if ( ! isset( $field['fields'] ) || empty( $field['fields'] ) ) {
					$hidden = '';
					if ( isset ( $field[ 'hidden' ] ) && $field[ 'hidden' ] ) {
						$hidden = 'hidden ';
					}

					if ( isset( $field[ 'full_width' ] ) && $field[ 'full_width' ] == TRUE ) {
						$class_string .= "redux_remove_th";
					}

					if ( isset ( $field[ 'fieldset_class' ] ) && ! empty( $field[ 'fieldset_class' ] ) ) {
						$class_string .= ' ' . $field[ 'fieldset_class' ];
					}

					echo '<fieldset id="' . $opt_name . '-' . $field[ 'id' ] . '" class="' . $hidden . 'redux-field-container redux-field redux-field-init redux-container-' . $field[ 'type' ] . ' ' . $class_string . '" data-id="' . $field[ 'id' ] . '" ' . $data_string . ' data-type="' . $field[ 'type' ] . '">';
					//}

					echo $_render;

					if ( ! empty ( $field[ 'desc' ] ) ) {
						$field[ 'description' ] = $field[ 'desc' ];
					}

					echo ( isset ( $field[ 'description' ] ) && $field[ 'type' ] != "info" && $field[ 'type' ] !== "section" && ! empty ( $field[ 'description' ] ) ) ? '<div class="description field-desc">' . $field[ 'description' ] . '</div>' : '';

					//if ( ! isset( $field['fields'] ) || empty( $field['fields'] ) ) {
					echo '</fieldset>';
					//}


					do_action_ref_array( "redux/field/{$opt_name}/{$field['type']}/fieldset/after/{$opt_name}", array(
						&$field,
						&$value
					) );


					do_action_ref_array( "redux/field/{$opt_name}/fieldset/after/{$opt_name}", array(
						&$field,
						&$value
					) );


				}

				////
			}
		}
	endif;
