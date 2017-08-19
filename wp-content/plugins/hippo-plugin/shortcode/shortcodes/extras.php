<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	//----------------------------------------------------------------------
	// FontAwesome Icon
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_register_fa_icon_shortcode' ) ):

		function hippo_register_fa_icon_shortcode( $shortcode ) {

			$register = array(
				'title'       => __( 'FontAwesome Icon', 'hippo-plugin' ),
				'description' => __( 'FontAwesome Icon', 'hippo-plugin' ),
				'attributes'  => array(

					'icon'  => array(
						'type'        => 'icon',
						'label'       => __( 'Choose Icons', 'hippo-plugin' ),
						'description' => __( 'Choose desire icon', 'hippo-plugin' ),
						'options'     => hippo_fontawesome_icons()
					),
					'size'  => array(
						'type'        => 'select',
						// text, textarea, color, select, select2, image, font, editor_contents
						'label'       => __( 'Icon size', 'hippo-plugin' ),
						'description' => __( 'Icon size', 'hippo-plugin' ),
						'options'     => array(
							''      => 'Normal',
							'fa-lg' => 'Large',
							'fa-2x' => '2X Large',
							'fa-3x' => '3X Large',
							'fa-4x' => '4X Large',
							'fa-5x' => '5X Large',
						)
					),
					'class' => array(
						'type'        => 'text',
						'label'       => __( 'Class', 'hippo-plugin' ),
						'description' => __( 'Extra class for icon', 'hippo-plugin' )
					),
				)

			);

			$shortcode->register( 'fa-icon', $register );
		}

		add_action( 'hippo_register_shortcode', 'hippo_register_fa_icon_shortcode' );
	endif; // function_exists('hippo_register_fa_icon_shortcode')

	if ( ! function_exists( 'hippo_shortcode_fa_icon' ) ):

		function hippo_shortcode_fa_icon( $atts, $contents = '' ) {
			$attributes = shortcode_atts( array(
				                              'class' => '',
				                              'icon'  => '',
				                              'size'  => ''
			                              ), $atts );
			ob_start();
			?>
			<i class="hippo-font-icon hippo-fa-icon <?php echo $attributes[ 'icon' ] ?> <?php echo $attributes[ 'size' ] ?> <?php echo $attributes[ 'class' ] ?>"></i>
			<?php
			return ob_get_clean();
		}

		add_shortcode( 'fa-icon', 'hippo_shortcode_fa_icon' );
	endif; // function_exists( 'hippo_shortcode_fa_icon' )


	//----------------------------------------------------------------------
	// Material Icon
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_register_ma_icon_shortcode' ) ):

		function hippo_register_ma_icon_shortcode( $shortcode ) {

			$register = array(
				'title'       => __( 'Material Icon', 'hippo-plugin' ),
				'description' => __( 'Material Icon', 'hippo-plugin' ),
				'attributes'  => array(

					'icon'  => array(
						'type'        => 'icon',
						'label'       => __( 'Choose Icons', 'hippo-plugin' ),
						'description' => __( 'Choose desire icon', 'hippo-plugin' ),
						'options'     => hippo_material_icons()
					),
					'size'  => array(
						'type'        => 'select',
						// text, textarea, color, select, select2, image, font, editor_contents
						'label'       => __( 'Icon size', 'hippo-plugin' ),
						'description' => __( 'Icon size', 'hippo-plugin' ),
						'options'     => array(
							''           => 'Normal',
							'zmdi-hc-lg' => 'Large',
							'zmdi-hc-2x' => '2X Large',
							'zmdi-hc-3x' => '3X Large',
							'zmdi-hc-4x' => '4X Large',
							'zmdi-hc-5x' => '5X Large',
						)
					),
					'class' => array(
						'type'        => 'text',
						'label'       => __( 'Class', 'hippo-plugin' ),
						'description' => __( 'Extra class for icon', 'hippo-plugin' )
					),
				)

			);

			$shortcode->register( 'ma-icon', $register );
		}

		add_action( 'hippo_register_shortcode', 'hippo_register_ma_icon_shortcode' );
	endif; // function_exists('hippo_register_ma_icon_shortcode')

	if ( ! function_exists( 'hippo_shortcode_ma_icon' ) ):

		function hippo_shortcode_ma_icon( $atts, $contents = '' ) {
			$attributes = shortcode_atts( array(
				                              'class' => '',
				                              'icon'  => '',
				                              'size'  => ''
			                              ), $atts );
			ob_start();
			?>
			<i class="hippo-font-icon hippo-ma-icon <?php echo $attributes[ 'icon' ] ?> <?php echo $attributes[ 'size' ] ?> <?php echo $attributes[ 'class' ] ?>"></i>
			<?php
			return ob_get_clean();
		}

		add_shortcode( 'ma-icon', 'hippo_shortcode_ma_icon' );
	endif; // function_exists( 'hippo_shortcode_ma_icon' )


	//----------------------------------------------------------------------
	// Widget Title Icon
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_widget_title_icon' ) ):

		function hippo_widget_title_icon( $atts, $contents = '' ) {
			$attributes = shortcode_atts( array(), $atts );
			ob_start();
			?>
			<span class="<?php echo $contents ?>"></span>
			<?php
			return ob_get_clean();
		}

		add_shortcode( 'widget-icon', 'hippo_widget_title_icon' );
	endif; // function_exists('hippo_widget_title_icon')

	//----------------------------------------------------------------------
	// oEmbed
	//----------------------------------------------------------------------

	if ( ! function_exists( 'hippo_register_hippo_oembed' ) ):

		function hippo_register_hippo_oembed( $shortcode ) {

			$register = array(
				'title'       => __( 'oEmbed', 'hippo-plugin' ),
				'description' => __( 'Embed WP supported links.', 'hippo-plugin' ),
				'attributes'  => array(

					'link' => array(
						'type'        => 'text',
						'label'       => __( 'oEmbed Link', 'hippo-plugin' ),
						'description' => __( 'WP supported oEmbed link to dipslay.', 'hippo-plugin' )
					),

				)

			);

			$shortcode->register( 'hippo-oembed', $register );
		}

		add_action( 'hippo_register_shortcode', 'hippo_register_hippo_oembed' );

	endif; // function_exists( 'hippo_register_hippo_oembed' )

	if ( ! function_exists( 'hippo_shortcode_hippo_oembed' ) ):

		function hippo_shortcode_hippo_oembed( $atts, $contents = '' ) {
			$attributes = shortcode_atts( array(
				                              'link' => ''
			                              ), $atts );
			ob_start();
			echo '<div class="hippo-oembed">';
			echo wp_oembed_get( $attributes[ 'link' ] );
			echo '</div>';

			return ob_get_clean();
		}

		add_shortcode( 'hippo-oembed', 'hippo_shortcode_hippo_oembed' );

	endif; // function_exists( 'hippo_shortcode_hippo_oembed' )