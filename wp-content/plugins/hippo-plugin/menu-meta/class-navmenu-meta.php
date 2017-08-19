<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	if ( ! class_exists( 'Hippo_Nav_Menu_Meta' ) ) :

		class Hippo_Nav_Menu_Meta {

			private $id      = '';
			private $title   = '';
			private $options = array( array() );

			public function __construct( $id, $title, $options ) {

				$this->id      = $id;
				$this->title   = $title;
				$this->options = $options;

				add_action( 'plugins_loaded', array( $this, 'init' ) );
			}

			public function init() {
				add_action( 'admin_init', array( $this, 'add_nav_menu_meta_boxes' ) );
			}

			public function add_nav_menu_meta_boxes() {
				add_meta_box(
					sprintf( '%s_nav_link', $this->id ),
					$this->title,
					array( $this, 'nav_menu_links' ),
					'nav-menus',
					'side',
					'high'
				);
			}

			public function nav_menu_links() {

				ob_start();
				?>

				<div id="posttype-<?php echo $this->id ?>" class="posttypediv">

					<div id="tabs-panel-posttype-<?php echo $this->id ?>-all" class="tabs-panel tabs-panel-active">
						<ul id="<?php echo $this->id ?>checklist-all" class="categorychecklist form-no-clear">

							<?php foreach ( $this->options as $index => $option ):

								$li_end_class = ( isset( $option[ 'end' ] ) ) ? 'class="hippo-nav-menu-feature-end"' : '';

								if ( ! isset( $option[ 'target' ] ) ) {
									$option[ 'target' ] = '';
								}
								if ( ! isset( $option[ 'classes' ] ) ) {
									$option[ 'classes' ] = '';
								}

								$key_index    = '-' . $index - 1;
								?>
								<li <?php echo $li_end_class ?>>
									<label class="menu-item-title">
										<input type="checkbox" class="menu-item-checkbox"
										       name="menu-item[<?php echo $key_index ?>][menu-item-object-id]"
										       value="<?php echo $key_index ?>"><?php echo esc_html( $option[ 'title' ] ) ?>
									</label>

									<input type="hidden" class="menu-item-type"
									       name="menu-item[<?php echo $key_index ?>][menu-item-type]"
									       value="custom">
									<input type="hidden" class="menu-item-title"
									       name="menu-item[<?php echo $key_index ?>][menu-item-title]"
									       value="<?php echo esc_html( $option[ 'title' ] ) ?>">
									<input type="hidden" class="menu-item-url"
									       name="menu-item[<?php echo $key_index ?>][menu-item-url]"
									       value="<?php echo esc_url( $option[ 'url' ] ) ?>">
									<input type="hidden" class="menu-item-target"
									       name="menu-item[<?php echo $key_index ?>][menu-item-target]"
									       value="<?php echo esc_attr( $option[ 'target' ] ) ?>">
									<input type="hidden" class="menu-item-classes"
									       name="menu-item[<?php echo $key_index ?>][menu-item-classes]"
									       value="<?php echo esc_attr( $option[ 'classes' ] ) ?>">
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<p class="button-controls">
						<span class="list-controls">
							<a href="<?php echo admin_url( "nav-menus.php?product-tab=all&amp;selectall=1#posttype-{$this->id}" ) ?>"
							   class="select-all"><?php _e( 'Select All', 'hippo-plugin' ) ?></a>
						</span>

						<span class="add-to-menu">
							<input type="submit" class="button-secondary submit-add-to-menu right"
							       value="<?php _e( 'Add to Menu', 'hippo-plugin' ) ?>"
							       name="add-post-type-menu-item" id="submit-posttype-<?php echo $this->id ?>">
							<span class="spinner"></span>
						</span>
					</p>
				</div>
				<?php
				echo ob_get_clean();
			}
		}

	endif;

/***
 * Example:
 *
 * $nav_meta = array(
 * array(
 * 'title'=>'Preset 1',
 * 'url'=>'?preset=preset1',
 * 'target'=>'_blank',
 * 'classes'=>'',
 * 'end'=>'0',
 * ),
 *
 * array(
 * 'title'=>'Preset 2',
 * 'url'=>'?preset=preset1',
 * 'target'=>'_blank',
 * 'classes'=>'',
 * 'end'=>'1',
 * )
 *
 * );
 *
 * new Hippo_Nav_Menu_Meta('id', 'Title', $nav_meta);
 */