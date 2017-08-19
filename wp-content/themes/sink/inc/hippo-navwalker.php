<?php

	if ( ! class_exists( 'Hippo_Menu_Walker' ) ):

		class Hippo_Menu_Walker extends Walker_Nav_Menu {

			/**
			 * Menu Fallback
			 * =============
			 * If this function is assigned to the wp_nav_menu's fallback_cb variable
			 * and a manu has not been assigned to the theme location in the WordPress
			 * menu manager the function with display nothing to a non-logged in user,
			 * and will add a link to the WordPress menu manager if logged in as an admin.
			 *
			 * @param array $args passed from the wp_nav_menu function.
			 *
			 */
			public static function fallback( $args ) {
				if ( current_user_can( 'manage_options' ) ) {
					$fb_output = '<li class="menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'sink' ) . '</a></li>';
					$fb_output .= '<li class="menu-item"><a target="_blank" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'sink' ) . '</a></li>';
					printf( $args[ 'items_wrap' ], 'menu-header-menu', 'menu', $fb_output );
				}
			}

			/**
			 * @see   Walker::start_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param int    $depth  Depth of page. Used for padding.
			 */

			public function start_lvl( &$output, $depth = 0, $args = array() ) {

				$indent = str_repeat( "\t", $depth );

				if ( $depth < 1 ) {
					$output .= '<div class="submenu-wrapper submenu-wrapper-topbottom menu-label-' . ( $depth ) . '"><div class="submenu-inner  submenu-inner-topbottom">';
				} else {
					$output .= '<div class="submenu-sub-wrapper submenu-sub-wrapper-leftright menu-label-' . ( $depth ) . '"><div class="submenu-sub-inner submenu-sub-inner-leftright">';
				}

				$output .= "\n$indent<ul role=\"menu\" class=\"sub-dropdown-menu\">\n";
			}

			public function end_lvl( &$output, $depth = 0, $args = array() ) {
				$indent = str_repeat( "\t", $depth );
				$output .= "\n$indent</ul>\n";
				$output .= '</div></div>';
			}

			/**
			 * @see   Walker::start_el()
			 * @since 3.0.0
			 *
			 * @param string $output       Passed by reference. Used to append additional content.
			 * @param object $item         Menu item data object.
			 * @param int    $depth        Depth of menu item. Used for padding.
			 * @param int    $current_page Menu item ID.
			 * @param object $args
			 */
			public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				$load_sidebar = FALSE;

				if ( function_exists( 'hippo_get_menu_meta' ) ) {
					$load_sidebar      = hippo_get_menu_meta( $item->ID, 'widgets' );
					$load_column_class = hippo_get_menu_meta( $item->ID, 'menucolumnclass', 'col-md-10' );

					$menu_heading               = hippo_get_menu_meta( $item->ID, 'menuheading', '' );
					$menu_icon_color            = hippo_get_menu_meta( $item->ID, 'iconcolor', '' );
					$menu_icon_background_color = hippo_get_menu_meta( $item->ID, 'iconbackgroundcolor', '' );
					$menu_icon                  = hippo_get_menu_meta( $item->ID, 'icon', '' );
					$menu_background_image_id   = hippo_get_menu_meta( $item->ID, 'menubackgroundimage', '' );
				}


				/**
				 * Dividers, Headers or Disabled
				 * =============================
				 * Determine whether the item is a Divider, Header, Disabled or regular
				 * menu item. To prevent errors we use the strcasecmp() function to so a
				 * comparison that is not case sensitive. The strcasecmp() function returns
				 * a 0 if the strings are equal.
				 */
				$class_names = $value = '';

				$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;

				$classes[] = 'menu-depth-' . $depth;


				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

				if ( $load_sidebar and is_active_sidebar( $load_sidebar ) ) {

					$args->has_children = TRUE;
					$class_names .= ' has-widget has-megamenu';

				}

				if ( isset( $args->has_children ) and $args->has_children ) {
					$class_names .= ' dropdown';
				}

				if ( ! empty( $menu_heading ) ) {
					$class_names .= ' menu-heading';
				}

				if ( ! empty( $menu_icon ) ) {
					$class_names .= ' menu-has-icon';
				}

				if ( in_array( 'current-menu-item', $classes ) ) {
					$class_names .= ' active';
				}

				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $value . $class_names . '>';

				$atts             = array();
				$atts[ 'title' ]  = ! empty( $item->title ) ? $item->title : '';
				$atts[ 'target' ] = ! empty( $item->target ) ? $item->target : '';
				$atts[ 'rel' ]    = ! empty( $item->xfn ) ? $item->xfn : '';

				// If item has_children add atts to a.

				/***
				 *
				 * if ( $args->has_children && $depth === 0 ) {
				 * $atts['href']        = '#';
				 * //$atts['data-toggle']    = 'dropdown';
				 * //$atts['class']            = 'dropdown-toggle';
				 * //$atts['aria-haspopup']    = 'true';
				 * } else {
				 * $atts['href'] = ! empty( $item->url ) ? $item->url : '';
				 * }
				 */

				$atts[ 'href' ] = ! empty( $item->url ) ? $item->url : '';


				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$item_output = isset( $args->before ) ? $args->before : '';


				if ( ! empty( $menu_icon ) ) { //
					$item_output .= '<a' . $attributes . '><i style="color:' . esc_attr( $menu_icon_color ) . ';';

					if ( ! empty( $menu_icon_background_color ) ) {
						$item_output .= 'background-color: ' . esc_attr( $menu_icon_background_color );
					}

					$item_output .= '" class="' . esc_attr( $menu_icon ) . '"></i> ';
				} elseif ( ! empty( $menu_heading ) ) {
					$item_output .= '<a' . $attributes . '><div class="header-menu">';
				} else {
					$item_output .= '<a' . $attributes . '>';
				}

				$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );


				if ( isset( $args->has_children ) and $args->has_children and 0 === $depth ) {
					$item_output .= ' <span class="fa fa-angle-down"></span>';
				}
				if ( isset( $args->has_children ) and $args->has_children and $depth >= 1 ) {
					$item_output .= ' <span class="fa fa-angle-right"></span>';
				}

				if ( ! empty( $menu_heading ) and empty( $menu_icon ) ) {
					$item_output .= '</div>';
				}

				$item_output .= '</a>';

				if ( $load_sidebar and is_active_sidebar( $load_sidebar ) ) {

					$megamenu_style = '';

					if ( ! empty( $menu_background_image_id ) ) {
						$menu_background_image = wp_get_attachment_image_src( $menu_background_image_id, 'full' );
						$megamenu_style        = 'style="background-image: url(' . $menu_background_image[ 0 ] . ')"';
					}


					if ( $depth < 1 ) {
						$item_output .= '<div class="submenu-wrapper submenu-wrapper-topbottom menu-label-' . ( $depth ) . ' ' . esc_attr( $load_column_class ) . '"><div class="submenu-inner submenu-inner-topbottom megamenu-inner-topbottom megamenu-inner" ' . $megamenu_style . '>';
					} else {
						$item_output .= '<div class="submenu-sub-wrapper submenu-sub-wrapper-leftright menu-label-' . ( $depth ) . '"><div class="submenu-sub-inner submenu-sub-inner-leftright megamenu-inner-leftright megamenu-inner" ' . $megamenu_style . '>';
					}


					$item_output .= '

                            <ul class="label' . ( $depth + 1 ) . ' sub-dropdown-menu">
                                <li>
                                    <div class="megamenu-container ' . apply_filters( 'hippo_megamenu_container_class', '' ) . '"><!-- .container class removed -->
                                        <div class="row">';

					ob_start();
					dynamic_sidebar( $load_sidebar );
					$item_output .= ob_get_clean();
					$item_output .= '
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>';
				}

				$item_output .= isset( $args->after ) ? $args->after : '';

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			}

			/**
			 * Traverse elements to create list from elements.
			 *
			 * Display one element if the element doesn't have any children otherwise,
			 * display the element and its children. Will only traverse up to the max
			 * depth and no ignore elements under that depth.
			 *
			 * This method shouldn't be called directly, use the walk() method instead.
			 *
			 * @see   Walker::start_el()
			 * @since 2.5.0
			 *
			 * @param object $element           Data object
			 * @param array  $children_elements List of elements to continue traversing.
			 * @param int    $max_depth         Max depth to traverse.
			 * @param int    $depth             Depth of current element.
			 * @param array  $args
			 * @param string $output            Passed by reference. Used to append additional content.
			 *
			 * @return null Null on failure with no changes to parameters.
			 */
			public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
				if ( ! $element ) {
					return;
				}

				$id_field = $this->db_fields[ 'id' ];

				// Display this element.
				if ( is_object( $args[ 0 ] ) ) {
					$args[ 0 ]->has_children = ! empty( $children_elements[ $element->$id_field ] );
				}

				parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
			}
		}

	endif;
