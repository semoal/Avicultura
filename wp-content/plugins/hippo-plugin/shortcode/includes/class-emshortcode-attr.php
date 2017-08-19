<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! class_exists( 'em_Shortcode_Attr' ) ):

		class em_Shortcode_Attr {

			private $parentcodes = '';

			private $deep_child = - 1;

			public function  set_attributes( $codename, $shortcode, $parentcodes = '' ) {
				ob_start();

				$this->deep_child ++;
				?>
				<?php
				if ( isset( $shortcode[ 'attributes' ] ) ) {

					$increment  = 0;
					$total_attr = count( $shortcode[ 'attributes' ] );
					foreach ( $shortcode[ 'attributes' ] as $id => $value ) {
						$this->generate_attribute( $codename, $id, $value, $this->parentcodes );
						$increment ++;
					}
				}
				?>

				<!--child -->
				<?php
				if ( $this->has_child( $shortcode ) ) {

					if ( empty( $this->parentcodes ) ) {
						$this->parentcodes = $codename . '[0]';
					}


					foreach ( $shortcode[ 'child' ] as $childcodename => $childcodes ) {


						//  if ($this->is_cloneable($childcodes)) {
						$this->parentcodes .= '[child][' . $childcodename . '][__' . $this->deep_child . '__]';
						//   } else {
						//      $this->parentcodes .= '[child][' . $childcodename . ']';
						//   }

						?>
						<li class="child-shortcode">
							<ul class="shortcode-wrapper" data-shortcode="<?php echo $childcodename ?>">
								<li class="shortcode-item">
									<div class="shortcode-header-wrapper">
										<?php if ( $this->is_cloneable( $childcodes ) or $this->is_sortable( $childcodes ) ) { ?>
											<span class="sort-shortcode-item  fa fa-arrows"></span>
										<?php } ?>
										<span class="shortcode-header"> <?php echo $childcodes[ 'title' ] ?> </span>
										<?php if ( $this->is_cloneable( $childcodes ) ) { ?>
											<span class="clone-shortcode-item fa fa-clipboard"></span>
											<span class="remove-shortcode-item fa fa-times"></span>
										<?php } ?>
									</div>
									<ul class="shortcode-container">
										<?php
											echo $this->set_attributes( $childcodename, $childcodes, $this->parentcodes );
										?>
									</ul>
								</li>
							</ul>
						</li>
						<?php
					}
				}

				return ob_get_clean();
			}

			private function generate_attribute( $codename, $id, $input, $parentcodes ) {

				$data_atts = '';

				$withchild = ( isset( $input[ 'child_also' ] ) and ( $input[ 'child_also' ] == TRUE ) ) ? 'with-child' : '';

				if ( isset( $input[ 'condition' ] ) and is_array( $input[ 'condition' ] ) ) {
					$data_atts = "data-condition='" . json_encode( $input[ 'condition' ] ) . "'";
					$data_atts .= ' class="hippo-has-condition ' . $withchild . '"';
				}
				?>
				<li data-name="<?php echo $id ?>" <?php echo $data_atts ?>>
					<?php
						echo $this->generate_type( $codename, $id, $input, $parentcodes );
					?>
				</li>
				<?php
			}

			private function generate_type( $codename, $id, $input, $parentcodes ) {

				ob_start();

				if ( empty( $parentcodes ) ) {
					$name = $codename . $parentcodes . '[0][attributes][' . $id . ']';
				} else {
					$name = $parentcodes . '[attributes][' . $id . ']';
				}

				//  echo $parentcodes;

				if ( ! isset( $input[ 'default' ] ) ) {
					$input[ 'default' ] = '';
				}

				if ( ! isset( $input[ 'hint' ] ) ) {
					$input[ 'hint' ] = '';
				}

				if ( ! isset( $input[ 'multiple' ] ) ) {
					$input[ 'multiple' ] = FALSE;
				}

				switch ( $input[ 'type' ] ) {

					case "text":
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>
							<div class="input-field">
								<input name="<?php echo $name ?>" type="text"
								       value="<?php echo $input[ 'default' ] ?>"/>
							</div>

							<div class="input-field-hint">
								<?php echo $input[ 'hint' ] ?>
							</div>

						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "image":

						//$choose = isset($input[ 'chooseID' ]) ?
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>

							<div class="input-field">
								<input <?php echo ( isset( $input[ 'chooseID' ] ) ) ? '' : 'name="' . $name . '"' ?>
									type="hidden"
									class="image"
									value=""/>

								<a href="javascript:;" class="wp-media-buttons open-media button"
								   title="<?php _e( 'Add Image', 'hippo-plugin' ) ?>">
									<div class="add_media"><span
											class="wp-media-buttons-icon"></span> <?php _e( 'Add Image', 'hippo-plugin' ) ?>
									</div>
								</a>

								<input <?php echo ( isset( $input[ 'chooseID' ] ) ) ? 'name="' . $name . '"' : '' ?>
									type="hidden"
									class="image-id"
									value=""/>
								<a class="hippo-media-clear display-none"
								   href="javascript:;"><?php _e( 'Clear', 'hippo-plugin' ) ?></a>
							</div>

						</label>

						<p class="description"><?php echo $input[ 'description' ] ?></p>

						<?php
						break;

					case "select":

						if ( $input[ 'multiple' ] ) {
							$name .= '[]';
						}
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>

							<div class="input-field">
								<select name="<?php echo $name ?>" <?php
									echo ( $input[ 'multiple' ] ) ? 'multiple="multiple"' : ''; ?>>
									<?php foreach ( $input[ 'options' ] as $key => $value ) { ?>
										<option
											value="<?php echo $key ?>" <?php selected( $input[ 'default' ], $key ); ?>><?php echo $value ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="input-field-hint">
								<?php echo $input[ 'hint' ] ?>
							</div>

						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "select2":

						$style = isset( $input[ 'style' ] ) ? $input[ 'style' ] : ' width: 200px';
						if ( $input[ 'multiple' ] ) {
							$name .= '[]';
						}
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>

							<div class="input-field">
								<select style="<?php echo $style ?>" class="select-2" name="<?php echo $name ?>" <?php
									echo ( $input[ 'multiple' ] ) ? 'multiple="multiple"' : ''; ?>>
									<?php foreach ( $input[ 'options' ] as $key => $value ) { ?>
										<option
											value="<?php echo $key ?>" <?php selected( $input[ 'default' ], $key ); ?>><?php echo $value ?></option>
									<?php } ?>
								</select>
							</div>

						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "icon":
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>
							<div class="input-field">
								<select style="width:200px" class="hippo-plugin-select2-icon" name="<?php echo $name ?>">
									<?php foreach ( $input[ 'options' ] as $key => $value ) { ?>
										<option
											value="<?php echo $key ?>" <?php selected( $input[ 'default' ], $key ); ?>><?php echo $value ?></option>
									<?php } ?>
								</select>
							</div>
						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "post":

						$post_type     = $input[ 'post_type' ];
						$post_taxonomy = isset( $input[ 'taxonomy' ] ) ? $input[ 'taxonomy' ] : FALSE;
						if ( $input[ 'multiple' ] ) {
							$name .= '[]';
						}
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>
							<div class="input-field">
								<select style="width:250px" class="select-post" name="<?php echo $name ?>" <?php
									echo ( $input[ 'multiple' ] ) ? 'multiple="multiple"' : ''; ?>>
									<?php foreach ( $this->get_posts( $post_type, $post_taxonomy ) as $key => $value ) { ?>
										<option
											value="<?php echo $key ?>" <?php selected( $input[ 'default' ], $key ); ?>><?php echo $value ?></option>
									<?php } ?>
								</select>
							</div>
						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "taxonomy":

						$taxonomy = $input[ 'taxonomy' ];
						if ( $input[ 'multiple' ] ) {
							$name .= '[]';
						}

						$args     = isset( $input[ 'args' ] ) ? $input[ 'args' ] : array();

						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>
							<div class="input-field">
								<select style="width:250px" class="select-post" name="<?php echo $name ?>" <?php
									echo ( $input[ 'multiple' ] ) ? 'multiple="multiple"' : ''; ?>>
									<?php foreach ( get_terms( $taxonomy, $args ) as $key => $term ) { ?>
										<option
											value="<?php echo $term->term_id ?>" <?php selected( $input[ 'default' ], $term->term_id ); ?>><?php echo $term->name . ' ( ' . $term->count . ' )' ?></option>
									<?php } ?>
								</select>
							</div>
						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "textarea":
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>
							<div class="input-field">
                            <textarea rows="5" cols="30" name="<?php echo $name ?>"
                                      class=""><?php echo $input[ 'default' ] ?></textarea>
							</div>
						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "editor_contents":
						?>
						<label>
							<div class="label">
								<?php echo $input[ 'label' ] ?>
							</div>
							<div class="input-field">
                            <textarea rows="5" cols="30" class="editor-selected-contents"
                                      name="<?php echo $name ?>"> </textarea>
							</div>
						</label>
						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					case "color":
						?>

						<label>
						<div class="label">
							<?php echo $input[ 'label' ] ?>
						</div>
						<div class="input-field">

							<!--<input type="text" class="hidden-color-input display-none"
                               value="<?php /*echo $input[ 'default' ] */
							?>"
                               data-default-color="<?php /*echo $input[ 'default' ] */
							?>"
                               data-name="<?php /*echo $name */
							?>"
                               data-class="shortcode-color-picker-field">-->

							<input type="text" class="shortcode-color-picker-field"
							       value="<?php echo $input[ 'default' ] ?>" name="<?php echo $name ?>"
							       data-default-color="<?php echo $input[ 'default' ] ?>"/>

						</div>

						<p class="description"><?php echo $input[ 'description' ] ?></p>
						<?php
						break;

					default:
						do_action( 'hippo_shortcode_generate_type', $codename, $id, $input, $parentcodes );
						break;

				}

				return ob_get_clean();
			}

			private function get_posts( $post_type, $taxonomy_name = FALSE ) {

				$args = array(
					'posts_per_page' => - 1,
					'orderby'        => 'post_date',
					'order'          => 'DESC',
					'post_type'      => $post_type,
					'post_status'    => 'publish'
				);

				$posts_array = get_posts( $args );

				$posts = array();

				foreach ( $posts_array as $post ) {

					$taxonomy = '';
					$title    = $post->post_title;
					if ( $taxonomy_name ) {
						$terms       = get_the_terms( $post->ID, $taxonomy_name );
						$terms_array = array();
						foreach ( $terms as $term ) {
							$terms_array[] = $term->name;
						}
						$taxonomy = ' :: ' . implode( ', ', $terms_array );

						$title = wp_trim_words( $post->post_title, 4 ) . $taxonomy;
					}


					$posts[ $post->ID ] = $title;
				}

				return $posts;
			}

			private function has_child( $shortcode ) {
				return ( isset( $shortcode[ 'child' ] ) and is_array( $shortcode[ 'child' ] ) );
			}

			private function is_cloneable( $shortcode ) {
				return ( isset( $shortcode[ 'cloneable' ] ) and $shortcode[ 'cloneable' ] == TRUE );
			}

			private function is_sortable( $shortcode ) {
				return ( isset( $shortcode[ 'sortable' ] ) and $shortcode[ 'sortable' ] == TRUE );
			}
		}

	endif; //  class_exists( 'em_Shortcode_Attr' )