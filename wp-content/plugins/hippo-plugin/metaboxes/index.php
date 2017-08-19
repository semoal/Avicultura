<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	// metaboxes directory constant
	define( 'HIPPO_CUSTOM_METABOXES_DIR', HIPPO_PLUGIN_URL . 'metaboxes' );

	/**
	 * recives data about a form field and spits out the proper html
	 *
	 * @param    array                 $field      array with various bits of information about the field
	 * @param    string|int|bool|array $meta       the saved data for this field
	 * @param    array                 $repeatable if is this for a repeatable field, contains parant id and the current integar
	 *
	 * @return   string                html for the field
	 */
	function hippo_custom_meta_box_field( $field, $meta = NULL, $repeatable = NULL ) {
		if ( ! ( $field || is_array( $field ) ) ) {
			return;
		}

		// get field data
		$type              = isset( $field[ 'type' ] ) ? $field[ 'type' ] : 'text';
		$label             = isset( $field[ 'label' ] ) ? $field[ 'label' ] : NULL;
		$desc              = isset( $field[ 'desc' ] ) ? '<span class="description">' . links_add_target( make_clickable( $field[ 'desc' ] ) ) . '</span>' : NULL;
		$placeholder       = isset( $field[ 'placeholder' ] ) ? $field[ 'placeholder' ] : '';
		$size              = isset( $field[ 'size' ] ) ? $field[ 'size' ] : '30';
		$post_type         = isset( $field[ 'post_type' ] ) ? $field[ 'post_type' ] : NULL;
		$options           = isset( $field[ 'options' ] ) ? $field[ 'options' ] : NULL;
		$settings          = isset( $field[ 'settings' ] ) ? $field[ 'settings' ] : NULL;
		$repeatable_fields = isset( $field[ 'repeatable_fields' ] ) ? $field[ 'repeatable_fields' ] : NULL;

		if ( isset( $field[ 'default' ] ) ) {
			if ( empty( $meta ) ) {
				$meta = $field[ 'default' ];
			}
		}


		// the id and name for each field
		$id = $name = isset( $field[ 'id' ] ) ? $field[ 'id' ] : NULL;
		if ( $repeatable ) {
			$name = $repeatable[ 0 ] . '[' . $repeatable[ 1 ] . '][' . $id . ']';
			$id   = $repeatable[ 0 ] . '_' . $repeatable[ 1 ] . '_' . $id;
		}
		switch ( $type ) {


			// basic
			case 'text':
			case 'tel':
			case 'email':
				echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $meta ) . '" class="regular-text" size="' . $size . '" placeholder="' . $placeholder . '" />
					<br />' . $desc;
				break;

			case 'url':
				echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_url( $meta ) . '" class="regular-text" size="' . $size . '" placeholder="' . $placeholder . '" />
					<br />' . $desc;
				break;

			case 'number':
				echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . intval( $meta ) . '" class="regular-text"  size="' . $size . '" placeholder="' . $placeholder . '" />
					<br />' . $desc;
				break;
			// textarea
			case 'textarea':
				echo '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" cols="60" rows="4" placeholder="' . $placeholder . '" >' . esc_textarea( $meta ) . '</textarea>
					<br />' . $desc;
				break;
			// editor
			case 'editor':
				echo wp_editor( $meta, $id, $settings ) . '<br />' . $desc;
				break;
			// checkbox
			case 'checkbox':
				echo '<input type="checkbox" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" ' . checked( $meta, TRUE, FALSE ) . ' value="1" />
					<label for="' . esc_attr( $id ) . '">' . $desc . '</label>';
				break;
			// select, chosen
			case 'select':
			case 'select2':
				echo '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '"', $type == 'select2' ? ' class="hippo-plugin-select2"' : '', isset( $multiple ) && $multiple == TRUE ? ' multiple="multiple"' : '', '>
					<option value="">Select One</option>'; // Select One
				foreach ( $options as $key => $option ) {
					echo '<option' . selected( $meta, $key, FALSE ) . ' value="' . $key . '">' . $option . '</option>';
				}
				echo '</select><br />' . $desc;
				break;
			// radio
			case 'radio':
				echo '<ul class="meta_box_items">';
				foreach ( $options as $key => $option ) {
					echo '<li>
                    <input type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '-' . $key . '" value="' . $key . '" ' . checked( $meta, $key, FALSE ) . ' />
						<label for="' . esc_attr( $id ) . '-' . $key . '">' . $option . '</label></li>';
				}
				echo '</ul>' . $desc;
				break;
			// checkbox_group
			case 'checkbox_group':
			case 'checkbox':
				echo '<ul class="meta_box_items">';
				foreach ( $options as $option ) {
					echo '<li><input type="checkbox" value="' . $option[ 'value' ] . '" name="' . esc_attr( $name ) . '[]" id="' . esc_attr( $id ) . '-' . $option[ 'value' ] . '"', is_array( $meta ) && in_array( $option[ 'value' ], $meta ) ? ' checked="checked"' : '', ' />
						<label for="' . esc_attr( $id ) . '-' . $option[ 'value' ] . '">' . $option[ 'label' ] . '</label></li>';
				}
				echo '</ul>' . $desc;
				break;

			// color
			case 'color':
				//$meta = $meta ? $meta : '#';
				echo '
                <input type="text" class="metacolorpicker"  name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . $meta . '" class="my-color-field" data-default-color="' . $meta . '" />
               <br />' . $desc;
				break;
			// map
			case 'map':

				$meta[ 'lat' ]     = isset( $meta[ 'lat' ] ) ? $meta[ 'lat' ] : 0;
				$meta[ 'lng' ]     = isset( $meta[ 'lng' ] ) ? $meta[ 'lng' ] : 0;
				$meta[ 'address' ] = isset( $meta[ 'address' ] ) ? $meta[ 'address' ] : '';

				echo '<div class="hippo-map-wrapper">';
				echo '<input class="hippo-map-autocomplete-input" data-lat="' . $meta[ 'lat' ] . '" data-lng="' . $meta[ 'lng' ] . '" name="' . esc_attr( $name ) . '[address]" id="' . esc_attr( $id ) . '" type="text" placeholder="' . $placeholder . '" value="' . $meta[ 'address' ] . '">';
				echo '<div class="hippo-map-container"></div>';
				echo '<input class="map-lat-value" name="' . esc_attr( $name ) . '[lat]" type="hidden" value="' . $meta[ 'lat' ] . '">';
				echo '<input class="map-lng-value" name="' . esc_attr( $name ) . '[lng]" type="hidden" value="' . $meta[ 'lng' ] . '">';
				echo '</div>';
				break;
			// post_select, post_chosen
			case 'post_select':
			case 'post_list':
			case 'post_select2':
				echo '<select data-placeholder=" Select " name="' . esc_attr( $name ) . '[]" id="' . esc_attr( $id ) . '"', $type == 'post_select2' ? ' class="hippo-plugin-select2"' : '', isset( $multiple ) && $multiple == TRUE ? ' multiple="multiple"' : '', '>
					<option value=""></option>'; // Select One
				$posts = get_posts( array(
					                    'post_type'      => $post_type,
					                    'posts_per_page' => - 1,
					                    'orderby'        => 'name',
					                    'order'          => 'ASC'
				                    ) );
				foreach ( $posts as $item ) {
					echo '<option value="' . $item->ID . '"' . selected( is_array( $meta ) && in_array( $item->ID, $meta ), TRUE, FALSE ) . '>' . $item->post_title . '</option>';
				}
				$post_type_object = get_post_type_object( $post_type );
				echo '</select> &nbsp;<span class="description"><a href="' . admin_url( 'edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label ) . '</a></span><br />' . $desc;
				break;
			// post_checkboxes
			case 'post_checkboxes':
				$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => - 1 ) );
				echo '<ul class="meta_box_items">';
				foreach ( $posts as $item ) {
					echo '<li><input type="checkbox" value="' . $item->ID . '" name="' . esc_attr( $name ) . '[]" id="' . esc_attr( $id ) . '-' . $item->ID . '"', is_array( $meta ) && in_array( $item->ID, $meta ) ? ' checked="checked"' : '', ' />
						<label for="' . esc_attr( $id ) . '-' . $item->ID . '">' . $item->post_title . '</label></li>';
				}
				$post_type_object = get_post_type_object( $post_type );
				echo '</ul> ' . $desc, ' &nbsp;<span class="description"><a href="' . admin_url( 'edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label ) . '</a></span>';
				break;
			// post_drop_sort
			case 'post_drop_sort':
				//areas
				$post_type_object = get_post_type_object( $post_type );
				echo '<p>' . $desc . ' &nbsp;<span class="description"><a href="' . admin_url( 'edit.php?post_type=' . $post_type . '">Manage ' . $post_type_object->label ) . '</a></span></p><div class="post_drop_sort_areas">';
				foreach ( $areas as $area ) {
					echo '<ul id="area-' . $area[ 'id' ] . '" class="sort_list">
						<li class="post_drop_sort_area_name">' . $area[ 'label' ] . '</li>';
					if ( is_array( $meta ) ) {
						$items = explode( ',', $meta[ $area[ 'id' ] ] );
						foreach ( $items as $item ) {
							$output = $display == 'thumbnail' ? get_the_post_thumbnail( $item, array(
								204,
								30
							) ) : get_the_title( $item );
							echo '<li id="' . $item . '">' . $output . '</li>';
						}
					}
					echo '</ul>
					<input type="hidden" name="' . esc_attr( $name ) . '[' . $area[ 'id' ] . ']"
					class="store-area-' . $area[ 'id' ] . '"
					value="', $meta ? $meta[ $area[ 'id' ] ] : '', '" />';
				}
				echo '</div>';
				// source
				$exclude = NULL;
				if ( ! empty( $meta ) ) {
					$exclude = implode( ',', $meta ); // because each ID is in a unique key
					$exclude = explode( ',', $exclude ); // put all the ID's back into a single array
				}
				$posts = get_posts( array(
					                    'post_type'      => $post_type,
					                    'posts_per_page' => - 1,
					                    'post__not_in'   => $exclude
				                    ) );
				echo '<ul class="post_drop_sort_source sort_list">
					<li class="post_drop_sort_area_name">Available ' . $label . '</li>';
				foreach ( $posts as $item ) {
					$output = $display == 'thumbnail' ? get_the_post_thumbnail( $item->ID, array(
						204,
						30
					) ) : get_the_title( $item->ID );
					echo '<li id="' . $item->ID . '">' . $output . '</li>';
				}
				echo '</ul>';
				break;
			// tax_select
			case 'tax_select':
				echo '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">
					<option value="">Select One</option>'; // Select One
				$terms      = get_terms( $id, 'get=all' );
				$post_terms = wp_get_object_terms( get_the_ID(), $id );
				$taxonomy   = get_taxonomy( $id );
				$selected   = $post_terms ? $taxonomy->hierarchical ? $post_terms[ 0 ]->term_id : $post_terms[ 0 ]->slug : NULL;
				foreach ( $terms as $term ) {
					$term_value = $taxonomy->hierarchical ? $term->term_id : $term->slug;
					echo '<option value="' . $term_value . '"' . selected( $selected, $term_value, FALSE ) . '>' . $term->name . '</option>';
				}
				echo '</select> &nbsp;<span class="description"><a href="' . get_bloginfo( 'url' ) . '/wp-admin/edit-tags.php?taxonomy=' . $id . '">Manage ' . $taxonomy->label . '</a></span>
				<br />' . $desc;
				break;
			// tax_checkboxes
			case 'tax_checkboxes':
				$terms      = get_terms( $id, 'get=all' );
				$post_terms = wp_get_object_terms( get_the_ID(), $id );
				$taxonomy   = get_taxonomy( $id );
				$checked    = $post_terms ? $taxonomy->hierarchical ? $post_terms[ 0 ]->term_id : $post_terms[ 0 ]->slug : NULL;
				foreach ( $terms as $term ) {
					$term_value = $taxonomy->hierarchical ? $term->term_id : $term->slug;
					echo '<input type="checkbox" value="' . $term_value . '" name="' . $id . '[]" id="term-' . $term_value . '"' . checked( $checked, $term_value, FALSE ) . ' /> <label for="term-' . $term_value . '">' . $term->name . '</label><br />';
				}
				echo '<span class="description">' . $field[ 'desc' ] . ' <a href="' . get_bloginfo( 'url' ) . '/wp-admin/edit-tags.php?taxonomy=' . $id . '&post_type=' . $page . '">Manage ' . $taxonomy->label . '</a></span>';
				break;
			// date
			case 'date':
				echo '<input type="text" class="datepicker" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . $meta . '" size="30" />
					<br />' . $desc;
				break;
			// slider
			case 'slider':
				$value = $meta != '' ? intval( $meta ) : '0';

				$min    = isset( $field[ 'min' ] ) ? $field[ 'min' ] : '0';
				$max    = isset( $field[ 'max' ] ) ? $field[ 'max' ] : '10';
				$step   = isset( $field[ 'step' ] ) ? $field[ 'step' ] : '0';
				$suffix = isset( $field[ 'suffix' ] ) ? $field[ 'suffix' ] : '';
				$prefix = isset( $field[ 'prefix' ] ) ? $field[ 'prefix' ] : '';

				echo '<input style="width:200px" type="range"  name="' . esc_attr( $name ) . '" value="' . $value . '" min="' . $min . '" max="' . $max . '" step="' . $step . '" id="' . esc_attr( $id ) . '-slider" />
					<div style="width:200px; text-align: center;">' . $prefix . '<span>' . $value . ' </span>' . $suffix . '</div>
					<br />' . $desc;
				break;
			// image
			case 'image':
				$image = HIPPO_CUSTOM_METABOXES_DIR . '/images/image.png';
				echo '<div class="meta_box_image"><span class="meta_box_default_image" style="display:none">' . $image . '</span>';
				if ( $meta ) {
					$image = wp_get_attachment_image_src( intval( $meta ), 'medium' );
					$image = $image[ 0 ];
				}
				echo '<input name="' . esc_attr( $name ) . '" type="hidden" class="meta_box_upload_image" value="' . intval( $meta ) . '" />
						<img src="' . esc_attr( $image ) . '" class="meta_box_preview_image" alt="" />
							<a href="#" class="meta_box_upload_image_button button button-small" rel="' . get_the_ID() . '">Choose Image</a>
							<small>&nbsp;<a href="#" class="meta_box_clear_image_button button button-small button-danger">Remove Image</a></small></div>
							<br clear="all" />' . $desc;
				break;
			// file


			// file
			case 'file':
			case 'media':

				$iconClass = 'meta_box_file';
				if ( $meta ) {
					$iconClass .= ' checked';
				}

				echo '
                <div class="meta_box_file_stuff">
                <input name="' . esc_attr( $name ) . '" type="hidden" class="meta_box_upload_media" value="' . $meta . '" />
			    <span class="' . $iconClass . '"></span>
				<a href="#" class="meta_box_upload_media_button button button-small button-primary">Choose Media</a>
				<small>&nbsp;<a href="#" class="meta_box_clear_file_button button button-small button-danger">Remove</a></small>
				</div>
				<br clear="all" />' . $desc;

				break;

			// gallery
			case 'gallery':

				$ids = $meta;

				?>
				<table class="form-table gallery-metabox" data-name="<?php echo esc_attr( $name ) ?>">
					<tr>
						<td>
							<a class="gallery-add button button-primary" href="#"
							   data-uploader-title="Add image(s) to gallery"
							   data-uploader-button-text="Add image(s)">Add image(s)</a>

							<ul class="gallery-metabox-list">
								<?php if ( $ids ) {
									foreach ( $ids as $key => $value ) {
										$image = wp_get_attachment_image_src( $value ); ?>

										<li class="gallery-metabox-list-li">
											<input type="hidden"
											       name="<?php echo esc_attr( $name ) ?>[<?php echo $key; ?>]"
											       value="<?php echo $value; ?>">
											<img class="image-preview" src="<?php echo $image[ 0 ]; ?>">
											<a class="change-image button button-small button-primary" href="#"
											   data-uploader-title="Change image"
											   data-uploader-button-text="Change image">Change image</a><br>
											<a class="remove-image button button-small button-danger" href="#">Remove
												image</a>
										</li>

										<?php
									}
								} ?>
							</ul>

						</td>
					</tr>
				</table>


				<?php


				break;

			// repeatable
			case 'repeatable':
				echo '<table id="' . esc_attr( $id ) . '-repeatable" class="meta_box_repeatable" cellspacing="0">
				<thead>
					<tr>
						<th><span class="sort_label"></span></th>
						<th>'.__('Fields', 'hippo-plugin').'</th>
						<th><a class="meta_box_repeatable_add" href="javascript:;"></a></th>
					</tr>
				</thead>
				<tbody>';
				$i = 0;
				// create an empty array
				if ( $meta == '' || $meta == array() ) {
					$keys = wp_list_pluck( $repeatable_fields, 'id' );
					$meta = array( array_fill_keys( $keys, NULL ) );
				}
				$meta = array_values( $meta );


				foreach ( $meta as $row ) {
					echo '<tr>
						<td><span class="sort hndle"></span></td><td>';
					foreach ( $repeatable_fields as $repeatable_field ) {
						if ( ! array_key_exists( $repeatable_field[ 'id' ], $meta[ $i ] ) ) {
							$meta[ $i ][ $repeatable_field[ 'id' ] ] = NULL;
						}
						echo '<label>' . $repeatable_field[ 'label' ] . '</label><p>';
						echo hippo_custom_meta_box_field( $repeatable_field, $meta[ $i ][ $repeatable_field[ 'id' ] ], array(
							$id,
							$i
						) );
						echo '</p>';
					} // end each field
					echo '</td><td><a class="meta_box_repeatable_remove" href="javascript:;"></a></td></tr>';
					$i ++;
				} // end each row
				echo '</tbody>';
				echo '
				<tfoot>
					<tr>
						<th><span class="sort_label"></span></th>
						<th>Fields</th>
						<th><a class="meta_box_repeatable_add" href="javascript:;"></a></th>
					</tr>
				</tfoot>';
				echo '</table>
				' . $desc;
				break;


			// icons

			// icons
			case 'icons':
			case 'icon':
				add_thickbox();
				echo '<ul class="meta_box_items icons">';
				// echo '<li class="hippo-metabox-label"><label class="label">' . $label . '</label></li>';
				echo '<li class="preview-icon"><i class="' . $meta . '"></i></li>
                <li class="icon-input"><input type="text" class="hidden-textbox" name="' . esc_attr( $name ) . '" value="' . $meta . '" /></li>
                <li class="icon-selector-box">
                <a title="Select Icon" href="#TB_inline?width=600&height=450&inlineId=icon-' . esc_attr( $id ) . '"  class="meta-icon-selector button button-primary button-small"> Select </a>
                <a href="" class="meta-icon-remove  button button-danger button-small" style="display:' . ( ( $meta == '' ) ? 'none' : '' ) . '" > Remove </a>
                </li>


                <li  id="icon-' . esc_attr( $id ) . '" style="display:none;">
                <div class="meta-icon-meta-box">';
				foreach ( $options as $key => $option ) {
					echo '
						<label for="' . esc_attr( $id ) . '-' . $option . '" class="' . ( ( $meta == $key ) ? 'selected' : '' ) . '">
						<input type="radio" id="' . esc_attr( $id ) . '-' . $option . '" value="' . $key . '" ' . checked( $meta, $key, FALSE ) . ' />
						<i class="' . $key . '"></i>
						</label>';
				}
				echo '</div></li>';

				echo '</ul>';
				echo '<span class="description">' . $desc . '</span>';
				break;

			default:
				do_action( 'hippo-plugin-cmb-generate-type', $field, $meta );
				do_action( "hippo-plugin-cmb-generate-type-{$type}", $field, $meta );
				break;

		} //end switch

	}


	/**
	 * Finds any item in any level of an array
	 *
	 * @param    string $needle   field type to look for
	 * @param    array  $haystack an array to search the type in
	 *
	 * @return   bool whether or not the type is in the provided array
	 */
	function hippo_meta_box_find_field_type( $needle, $haystack ) {
		foreach ( $haystack as $h ) {
			if ( isset( $h[ 'type' ] ) && $h[ 'type' ] == 'repeatable' ) {
				return hippo_meta_box_find_field_type( $needle, $h[ 'repeatable_fields' ] );
			} elseif ( ( isset( $h[ 'type' ] ) && $h[ 'type' ] == $needle ) || ( isset( $h[ 'repeatable_type' ] ) && $h[ 'repeatable_type' ] == $needle ) ) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Find repeatable
	 *
	 * This function does almost the same exact thing that the above function
	 * does, except we're exclusively looking for the repeatable field. The
	 * reason is that we need a way to look for other fields nested within a
	 * repeatable, but also need a way to stop at repeatable being true.
	 * Hopefully I'll find a better way to do this later.
	 *
	 * @param    string $needle   field type to look for
	 * @param    array  $haystack an array to search the type in
	 *
	 * @return    bool                whether or not the type is in the provided array
	 */
	function hippo_meta_box_find_repeatable( $needle = 'repeatable', $haystack ) {
		foreach ( $haystack as $h ) {
			if ( isset( $h[ 'type' ] ) && $h[ 'type' ] == $needle ) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * sanitize boolean inputs
	 */
	function hippo_meta_box_santitize_boolean( $string ) {
		if ( ! isset( $string ) || $string != 1 || $string != TRUE ) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * outputs properly sanitized data
	 *
	 * @param    string $string   the string to run through a validation function
	 * @param    string $function the validation function
	 *
	 * @return                        a validated string
	 */
	function hippo_meta_box_sanitize( $string, $function = 'sanitize_text_field' ) {
		switch ( $function ) {
			case 'intval':
				return intval( $string );
			case 'absint':
				return absint( $string );
			case 'wp_kses_post':
				return wp_kses_post( $string );
			case 'wp_kses_data':
				return wp_kses_data( $string );
			case 'esc_url_raw':
				return esc_url_raw( $string );
			case 'is_email':
				return is_email( $string );
			case 'sanitize_title':
				return sanitize_title( $string );
			case 'santitize_boolean':
				return santitize_boolean( $string );
			case 'sanitize_text_field':
			default:
				return sanitize_text_field( $string );
		}
	}

	/**
	 * Map a multideminsional array
	 *
	 * @param    string $func      the function to map
	 * @param    array  $meta      a multidimensional array
	 * @param    array  $sanitizer a matching multidimensional array of sanitizers
	 *
	 * @return    array                new array, fully mapped with the provided arrays
	 */
	function hippo_meta_box_array_map_r( $func, $meta, $sanitizer ) {

		$newMeta = array();
		$meta    = array_values( $meta );

		foreach ( $meta as $key => $array ) {
			if ( $array == '' ) {
				continue;
			}
			/**
			 * some values are stored as array, we only want multidimensional ones
			 */
			if ( ! is_array( $array ) ) {
				return array_map( $func, $meta, (array) $sanitizer );
				break;
			}
			/**
			 * the sanitizer will have all of the fields, but the item may only
			 * have valeus for a few, remove the ones we don't have from the santizer
			 */
			$keys         = array_keys( $array );
			$newSanitizer = $sanitizer;
			if ( is_array( $sanitizer ) ) {
				foreach ( $newSanitizer as $sanitizerKey => $value ) {
					if ( ! in_array( $sanitizerKey, $keys ) ) {
						unset( $newSanitizer[ $sanitizerKey ] );
					}
				}
			}
			/**
			 * run the function as deep as the array goes
			 */
			foreach ( $array as $arrayKey => $arrayValue ) {
				if ( is_array( $arrayValue ) ) {
					$array[ $arrayKey ] = hippo_meta_box_array_map_r( $func, $arrayValue, $newSanitizer[ $arrayKey ] );
				}
			}

			$array           = array_map( $func, $array, $newSanitizer );
			$newMeta[ $key ] = array_combine( $keys, array_values( $array ) );
		}

		return $newMeta;
	}

	/**
	 * takes in a few peices of data and creates a custom meta box
	 *
	 * @param    string       $id     meta box id
	 * @param    string       $title  title
	 * @param    array        $fields array of each field the box should include
	 * @param    string|array $page   post type to add meta box to
	 */
	class Hippo_Custom_Add_Meta_Box {

		private $id;
		private $title;
		private $fields;
		private $page;

		public function __construct( $id, $title, $fields, $page ) {
			$this->id     = $id;
			$this->title  = $title;
			$this->fields = $fields;
			$this->page   = $page;

			if ( ! is_array( $this->page ) ) {
				$this->page = array( $this->page );
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_head', array( $this, 'admin_head' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
			add_action( 'save_post', array( $this, 'save_box' ) );
		}

		/**
		 * enqueue necessary scripts and styles
		 */
		function admin_enqueue_scripts() {
			global $pagenow;
			if ( in_array( $pagenow, array(
					'post-new.php',
					'post.php'
				) ) && in_array( get_post_type(), $this->page )
			) {


				foreach ( $this->fields as $field ) {
					if ( $field[ 'type' ] == 'map' ) {
						wp_enqueue_script( 'google-map', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places' );
					}
				}

				// js
				$deps = array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker' );

				wp_enqueue_script( 'hippo-html5slider', HIPPO_CUSTOM_METABOXES_DIR . '/js/html5slider.js', $deps );
				wp_enqueue_script( 'hippo-google-map', HIPPO_CUSTOM_METABOXES_DIR . '/js/hippo-google-map.js', $deps );


				wp_enqueue_script( 'hippo-meta_box', HIPPO_CUSTOM_METABOXES_DIR . '/js/scripts.js', $deps );


				// css
				$deps = array( 'jqueryui', 'wp-color-picker' );
				wp_register_style( 'jqueryui', HIPPO_CUSTOM_METABOXES_DIR . '/css/jqueryui.css' );

				wp_enqueue_media();
				wp_enqueue_style( 'media' );
				wp_enqueue_style( 'meta_box', HIPPO_CUSTOM_METABOXES_DIR . '/css/meta_box.css', $deps );
			}
		}

		/**
		 * adds scripts to the head for special fields with extra js requirements
		 */
		function admin_head() {
		}

		/**
		 * adds the meta box for every post type in $page
		 */
		function add_box() {
			foreach ( $this->page as $page ) {
				add_meta_box( $this->id, $this->title, array( $this, 'meta_box_callback' ), $page, 'normal', 'high' );
			}
		}

		/**
		 * outputs the meta box
		 */
		function meta_box_callback() {
			// Use nonce for verification
			wp_nonce_field( 'custom_meta_box_nonce_action', 'custom_meta_box_nonce_field' );


			$fields = apply_filters( 'hippo-plugin-cmb-fields', $this->fields, $this->page, $this->id );

			// Begin the field table and loop
			echo '<table class="form-table meta_box">';
			foreach ( $fields as $field ) {

				$field = apply_filters( 'hippo-plugin-cmb-field', $field, $this->page, $this->id );

				if ( $field[ 'type' ] == 'section' ) {
					echo '<tr>
						<td colspan="2">
							<h2>' . $field[ 'label' ] . '</h2>
						</td>
					</tr>';
				}
				elseif ( $field[ 'type' ] == 'icons' ) {

					echo '<tr>
                    <th><label for="' . $field[ 'id' ] . '">' . $field[ 'label' ] . '</label></th>
                    <td>';

					$meta = get_post_meta( get_the_ID(), $field[ 'id' ], TRUE );
					echo hippo_custom_meta_box_field( $field, $meta );

					echo '</td></tr>';
				}
				elseif ( $field[ 'type' ] == 'gallery' ) {

					echo '<tr><td>';

					$meta = get_post_meta( get_the_ID(), $field[ 'id' ], TRUE );
					echo hippo_custom_meta_box_field( $field, $meta );

					echo '</td></tr>';
				}
				elseif ( $field[ 'type' ] == 'map' ) {

					echo '<tr><td colspan="2">';

					$meta = get_post_meta( get_the_ID(), $field[ 'id' ], TRUE );
					echo hippo_custom_meta_box_field( $field, $meta );

					echo '</td></tr>';
				}
				else {

					$row_divider_class = isset( $field[ 'divider' ] ) ? ' class="meta-box-divider"' : '';

					echo '<tr' . $row_divider_class . '>
						<th><label for="' . $field[ 'id' ] . '">' . $field[ 'label' ] . '</label></th>
						<td>';

					$meta = get_post_meta( get_the_ID(), $field[ 'id' ], TRUE );
					echo hippo_custom_meta_box_field( $field, $meta );

					echo '</td>
					</tr>';
				}
			} // end foreach
			echo '</table>'; // end table
		}

		/**
		 * saves the captured data
		 */
		function save_box( $post_id ) {
			$post_type = get_post_type();

			// verify nonce
			if ( ! isset( $_POST[ 'custom_meta_box_nonce_field' ] ) ) {
				return $post_id;
			}
			if ( ! ( in_array( $post_type, $this->page ) || wp_verify_nonce( $_POST[ 'custom_meta_box_nonce_field' ], 'custom_meta_box_nonce_action' ) ) ) {
				return $post_id;
			}
			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			// check permissions
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}

			// loop through fields and save the data
			foreach ( $this->fields as $field ) {
				if ( $field[ 'type' ] == 'section' ) {
					$sanitizer = NULL;
					continue;
				}
				if ( in_array( $field[ 'type' ], array( 'tax_select', 'tax_checkboxes' ) ) ) {
					// save taxonomies
					if ( isset( $_POST[ $field[ 'id' ] ] ) ) {
						$term = $_POST[ $field[ 'id' ] ];
						wp_set_object_terms( $post_id, $term, $field[ 'id' ] );
					}
				} elseif ( $field[ 'type' ] == 'map' ) {
					$new = $_POST[ $field[ 'id' ] ];
					update_post_meta( $post_id, $field[ 'id' ], $new );
				} else {
					// save the rest
					$new = FALSE;
					$old = get_post_meta( $post_id, $field[ 'id' ], TRUE );
					if ( isset( $_POST[ $field[ 'id' ] ] ) ) {
						$new = $_POST[ $field[ 'id' ] ];
					}


					if ( isset( $new ) && '' == $new && $old ) {
						delete_post_meta( $post_id, $field[ 'id' ], $old );
					} elseif ( isset( $new ) && $new != $old ) {
						$sanitizer = isset( $field[ 'sanitizer' ] ) ? $field[ 'sanitizer' ] : 'sanitize_text_field';
						if ( is_array( $new ) ) {
							$new = hippo_meta_box_array_map_r( 'hippo_meta_box_sanitize', $new, $sanitizer );
						} else {
							$new = hippo_meta_box_sanitize( $new, $sanitizer );
						}

						update_post_meta( $post_id, $field[ 'id' ], $new );
					}
				}
			} // end foreach
		}

	}


	class Hippo_Taxonomy_To_Radio {

		private $taxonomy;
		private $post_type;
		private $info;

		public function __construct( $post_type, $taxonomy ) {


			$this->taxonomy  = $taxonomy;
			$this->post_type = $post_type;

			add_action( 'admin_menu', array( $this, 'remove_meta_box' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		}

		// Remove taxonomy meta box
		public function remove_meta_box() {
			// The taxonomy metabox ID. This is different for non-hierarchical taxonomies
			$tax_mb_id = $this->taxonomy . 'div';
			remove_meta_box( $tax_mb_id, $this->post_type, 'normal' );
		}

		// Add new taxonomy meta box
		public function add_meta_box() {

			$tax = get_taxonomy( $this->taxonomy );

			add_meta_box( $this->taxonomy, $tax->labels->name, array(
				$this,
				'metabox_inner'
			), $this->post_type, 'side', 'core' );
		}

		// Callback to set up metabox
		public function metabox_inner( $post ) {
			// Get taxonomy and terms
			$taxonomy = $this->taxonomy;
			$tax      = get_taxonomy( $taxonomy );
			$name     = 'tax_input[' . $taxonomy . ']';
			$terms    = get_terms( $taxonomy, array( 'hide_empty' => 0 ) );

			//Get current and popular terms
			$popular   = get_terms( $taxonomy, array(
				'orderby'      => 'count',
				'order'        => 'DESC',
				'number'       => 10,
				'hierarchical' => FALSE
			) );
			$postterms = get_the_terms( $post->ID, $taxonomy );
			$current   = ( $postterms ? array_pop( $postterms ) : FALSE );
			$current   = ( $current ? $current->term_id : 0 );


			//print_r($tax); die;

			?>
			<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

				<!-- Display tabs-->
				<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
					<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all"><?php echo $tax->labels->all_items; ?></a>
					</li>
					<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop"><?php _e( 'Most Used' ); ?></a>
					</li>
				</ul>

				<!-- Display popular taxonomy terms -->
				<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
					<ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear">
						<?php foreach ( $popular as $term ) {
							$id = "id='in-popular-{$taxonomy}-$term->term_id'";
							echo "<li id='popular-$taxonomy-$term->term_id'><label class='selectit'>";
							echo "<input type='radio' {$id}" . checked( $current, $term->term_id, FALSE ) . "value='$term->term_id' /> $term->name <br />";
							echo "</label></li>";
						} ?>
					</ul>
				</div>
				<!-- Display taxonomy terms -->
				<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
					<ul id="<?php echo $taxonomy; ?>checklist"
					    class="list:<?php echo $taxonomy ?> categorychecklist form-no-clear">
						<?php foreach ( $terms as $term ) {
							$id = "id='in-{$taxonomy}-{$term->term_id}'";
							echo "<li id='$taxonomy-$term->term_id'><label class='selectit'>";
							echo "<input type='radio' {$id} name='{$name}'" . checked( $current, $term->term_id, FALSE ) . "value='$term->term_id' /> $term->name<br />";
							echo "</label></li>";
						} ?>
					</ul>
				</div>

				<div>
					<h4>
						<a target="_blank"
						   href="<?php echo admin_url( "edit-tags.php" ) . "?taxonomy=$taxonomy&post_type={$this->post_type}" ?>">
							+ <?php echo $tax->labels->add_new_item ?></a>
					</h4>
				</div>
			</div>
			<?php
		}
	}

// Convert Portfolio type checkbox to radio
// new Hippo_Taxonomy_To_Radio( 'example', 'portfolio-type');
