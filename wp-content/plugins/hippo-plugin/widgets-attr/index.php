<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! class_exists( 'Hippo_Widget_Attributes' ) ) :

		final class Hippo_Widget_Attributes {

			public function __construct( $sidebars = array(), $fields = array() ) {

				add_action( 'init', array( $this, 'setup' ) );
			}

			public function setup() {

				add_action( 'in_widget_form', array( $this, 'form' ), 10, 3 );
				add_filter( 'widget_update_callback', array( $this, 'save_update' ), 10, 2 );
				add_filter( 'dynamic_sidebar_params', array( $this, 'display' ) );
			}

			public function form( $widget, $return, $instance ) {

				$grid  = isset( $instance[ 'hippo_bs_grid_class' ] ) ? $instance[ 'hippo_bs_grid_class' ] : '';
				$clear = isset( $instance[ 'hippo_bs_grid_clear' ] ) ? $instance[ 'hippo_bs_grid_clear' ] : '';

				ob_start();
				?>
				<p>
					<label
						for="<?php echo $widget->get_field_id( 'hippo_bs_grid_class' ) ?>"><?php _e( 'Bootstrap Grid Class:', 'hippo-plugin' ) ?>
						<input class="widefat" value="<?php echo $grid ?>"
						       id="<?php echo $widget->get_field_id( 'hippo_bs_grid_class' ) ?>"
						       name="<?php echo $widget->get_field_name( 'hippo_bs_grid_class' ) ?>">
						<br/>
						<small><?php _e( 'Twitter Bootstrap grid class name. like: col-md-3, col-sm-4 etc. You can add multiple class name separate by space.', 'hippo-plugin' ) ?></small>
					</label>
				</p>


				<p><input value="1" <?php checked( '1', $clear ) ?>
				          id="<?php echo $widget->get_field_id( 'hippo_bs_grid_clear' ) ?>"
				          name="<?php echo $widget->get_field_name( 'hippo_bs_grid_clear' ) ?>"
				          type="checkbox">&nbsp;<label
						for="<?php echo $widget->get_field_id( 'hippo_bs_grid_clear' ) ?>"><?php _e( 'Clear grid column', 'hippo-plugin' ) ?></label>
					<br/>
					<small><?php _e( 'This will add a clearfix class to clearfix bootstrap grid column.', 'hippo-plugin' ) ?></small>
				</p>

				<?php

				do_action( 'hippo-plugin-widget-attr-form', $widget, $return, $instance );

				echo ob_get_clean();
			}

			public function save_update( $instance, $new_instance ) {
				$instance[ 'hippo_bs_grid_class' ] = $new_instance[ 'hippo_bs_grid_class' ];
				$instance[ 'hippo_bs_grid_clear' ] = $new_instance[ 'hippo_bs_grid_clear' ];

				$instance = apply_filters( 'hippo-plugin-widget-attr-save', $instance, $new_instance );

				return $instance;
			}

			public function display( $params ) {

				global $wp_registered_widgets;

				$sidebar_id   = $params[ 0 ][ 'id' ];
				$sidebar_name = $params[ 0 ][ 'name' ];

				$widget_id        = $params[ 0 ][ 'widget_id' ];
				$widget_obj       = $wp_registered_widgets[ $widget_id ];
				$widget_opt       = get_option( $widget_obj[ 'callback' ][ 0 ]->option_name );
				$widget_num       = $widget_obj[ 'params' ][ 0 ][ 'number' ];
				$grid_class       = isset( $widget_opt[ $widget_num ][ 'hippo_bs_grid_class' ] ) ? $widget_opt[ $widget_num ][ 'hippo_bs_grid_class' ] : '';
				$grid_clear_class = isset( $widget_opt[ $widget_num ][ 'hippo_bs_grid_clear' ] ) ? $widget_opt[ $widget_num ][ 'hippo_bs_grid_clear' ] : '';


				if ( preg_match( '/class="/', $params[ 0 ][ 'before_widget' ] ) ) {

					$default_grid_classes = apply_filters( 'hippo_widget_grid_class_to_remove', array() );

					if ( $grid_class ) {
						$params[ 0 ][ 'before_widget' ] = str_ireplace( $default_grid_classes, '', $params[ 0 ][ 'before_widget' ] );
					}
					$params[ 0 ][ 'before_widget' ] = preg_replace( '/class="/', "class=\"{$grid_class} ", $params[ 0 ][ 'before_widget' ], 1 );
				} else {
					$params[ 0 ][ 'before_widget' ] = preg_replace( '/(\<[a-zA-Z]+)(.*?)(\>)/', "$1 $2 class=\"{$grid_class}\" $3", $params[ 0 ][ 'before_widget' ], 1 );
				}

				if ( ! empty( $grid_clear_class ) ) {
					$params[ 0 ][ 'after_widget' ] = $params[ 0 ][ 'after_widget' ] . '<div class="clearfix"></div>';
				}

				$params = apply_filters( 'hippo-plugin-widget-attr-display-params', $params );

				return $params;
			}
		}
	endif;

	if ( ! function_exists( 'Hippo_Widget_Attributes' ) ):

		function Hippo_Widget_Attributes() {
			//$GLOBALS[ 'hippo_widget_attributes' ] = new Hippo_Widget_Attributes();
			new Hippo_Widget_Attributes();
		}

		add_action( 'plugins_loaded', 'Hippo_Widget_Attributes' );

	endif;