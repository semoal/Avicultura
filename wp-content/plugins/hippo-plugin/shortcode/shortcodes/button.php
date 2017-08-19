<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! function_exists( 'hippo_register_button_shortcode' ) ):

		function hippo_register_button_shortcode( $shortcode ) {

			$register_button = array(
				'title'       => __( 'Button', 'hippo-plugin' ),
				'description' => __( 'Display button', 'hippo-plugin' ),
				'attributes'  => array(

					'style'       => array(
						'type'        => 'select',
						'label'       => __( 'Style', 'hippo-plugin' ),
						'description' => __( 'Select button style', 'hippo-plugin' ),
						'options'     => array(
							'simple-btn'       => __( 'Simple style', 'hippo-plugin' ),
							'rounded-btn'      => __( 'Rounded style', 'hippo-plugin' ),
							'simple-icon-btn'  => __( 'Simple icon style', 'hippo-plugin' ),
							'rounded-icon-btn' => __( 'Rounded & icon style', 'hippo-plugin' ),
							'only-border-btn'  => __( 'Only Border style', 'hippo-plugin' ),
							'border-icon-btn'  => __( 'Border & icon style', 'hippo-plugin' ),
						),
						'condition'   => array(

							'simple-btn'       => array(
								'show' => array( 'text', 'button_link', 'type', 'bg_color', 'text_color' ),
								'hide' => array( 'icon' ),
							),
							'rounded-btn'      => array(
								'show' => array( 'text', 'button_link', 'type', 'bg_color', 'text_color' ),
								'hide' => array( 'icon' ),
							),
							'simple-icon-btn'  => array(
								'show' => array( 'text', 'button_link', 'type', 'icon', 'bg_color', 'text_color' ),
								'hide' => array( '' ),
							),
							'rounded-icon-btn' => array(
								'show' => array( 'text', 'button_link', 'type', 'icon', 'bg_color', 'text_color' ),
								'hide' => array( '' ),
							),
							'only-border-btn'  => array(
								'show' => array( 'text', 'button_link', 'type', 'text_color' ),
								'hide' => array( 'icon', 'bg_color' ),
							),
							'border-icon-btn'  => array(
								'show' => array( 'text', 'button_link', 'type', 'icon', 'text_color' ),
								'hide' => array( 'bg_color' ),
							),
						),
					),
					'text'        => array(
						'type'        => 'text',
						'label'       => __( 'Text', 'hippo-plugin' ),
						'description' => __( 'Button Text', 'hippo-plugin' )
					),
					'button_link' => array(
						'type'        => 'text', // text, textarea, color, select, select2, image, font, editor_contents
						'label'       => __( 'Link', 'hippo-plugin' ),
						'description' => __( 'Button Link', 'hippo-plugin' )
					),
					'link_target' => array(
						'type'        => 'select',
						'label'       => __( 'Link target', 'hippo-plugin' ),
						'description' => __( 'Select link target', 'hippo-plugin' ),
						'options'     => array(
							'_self'   => __( 'Self', 'hippo-plugin' ),
							'_blank'  => __( 'Blank', 'hippo-plugin' ),
							'_parent' => __( 'Parent', 'hippo-plugin' ),
							'_top'    => __( 'Top', 'hippo-plugin' ),
						)
					),
					'type'        => array(
						'type'        => 'select',
						'label'       => __( 'Type', 'hippo-plugin' ),
						'description' => __( 'Button type', 'hippo-plugin' ),
						'options'     => array(
							'btn-sm'     => __( 'Small Button', 'hippo-plugin' ),
							'medium-btn' => __( 'Medium Button', 'hippo-plugin' ),
							'large-btn'  => __( 'Large Button', 'hippo-plugin' ),
						)
					),
					'icon'        => array(
						'type'        => 'icon',
						'label'       => __( 'Social Icons', 'hippo-plugin' ),
						'description' => __( 'Choose desire button icon', 'hippo-plugin' ),
						'options'     => hippo_fontawesome_icons()
					),
					'bg_color'    => array(
						'type'        => 'color',
						// text, textarea, color, select, select2, image, font, editor_contents
						'label'       => __( 'Background color', 'hippo-plugin' ),
						'description' => __( 'Select background color', 'hippo-plugin' ),
						'default'     => '#e74c3c',

					),
					'text_color'  => array(
						'type'        => 'color',
						// text, textarea, color, select, select2, image, font, editor_contents
						'label'       => __( 'Text color', 'hippo-plugin' ),
						'description' => __( 'Select text color', 'hippo-plugin' ),
						'default'     => '#FFFFFF',

					),


				)

			);

			$shortcode->register( 'button', $register_button );

		}

		add_action( 'hippo_register_shortcode', 'hippo_register_button_shortcode' );
	endif; // function_exists( 'hippo_register_button_shortcode' )

	if ( ! function_exists( 'hippo_shortcode_button' ) ):

		function hippo_shortcode_button( $atts, $contents = '' ) {
			$attributes = shortcode_atts( array(
				                              'style'       => 'simple-btn',
				                              'text'        => '',
				                              'button_link' => '',
				                              'link_target' => '',
				                              'type'        => '',
				                              'icon'        => '',
				                              'bg_color'    => '',
				                              'text_color'  => '',
			                              ), $atts );

			ob_start();
			?>


			<?php if ( $attributes[ 'style' ] == 'simple-btn' ) { ?>

				<div class="btn-wrapper simple-btn <?php echo $attributes[ 'type' ] ?>">
					<a style="background-color:<?php echo $attributes[ 'bg_color' ] ?>; color:<?php echo $attributes[ 'text_color' ] ?>"
					   href="<?php echo $attributes[ 'button_link' ] ?>"
					   target="<?php echo $attributes[ 'link_target' ] ?>"><?php echo $attributes[ 'text' ] ?></a>
				</div>

			<?php } elseif ( $attributes[ 'style' ] == 'rounded-btn' ) { ?>

				<div class="btn-wrapper rounded-btn <?php echo $attributes[ 'type' ] ?>">
					<a style="background-color:<?php echo $attributes[ 'bg_color' ] ?>; color:<?php echo $attributes[ 'text_color' ] ?>"
					   href="<?php echo $attributes[ 'button_link' ] ?>"
					   target="<?php echo $attributes[ 'link_target' ] ?>"><?php echo $attributes[ 'text' ] ?></a>
				</div>

			<?php } elseif ( $attributes[ 'style' ] == 'simple-icon-btn' ) { ?>

				<div class="btn-wrapper simple-icon-btn <?php echo $attributes[ 'type' ] ?>">
					<a style="background-color:<?php echo $attributes[ 'bg_color' ] ?>; color:<?php echo $attributes[ 'text_color' ] ?>"
					   href="<?php echo $attributes[ 'button_link' ] ?>"
					   target="<?php echo $attributes[ 'link_target' ] ?>"><i
							class="<?php echo $attributes[ 'icon' ] ?>"></i> <?php echo $attributes[ 'text' ] ?></a>
				</div>

			<?php } elseif ( $attributes[ 'style' ] == 'only-border-btn' ) { ?>

				<div class="btn-wrapper only-border-btn <?php echo $attributes[ 'type' ] ?>">
					<a style="background-color:<?php echo $attributes[ 'bg_color' ] ?>; color:<?php echo $attributes[ 'text_color' ] ?>"
					   href="<?php echo $attributes[ 'button_link' ] ?>"
					   target="<?php echo $attributes[ 'link_target' ] ?>"><?php echo $attributes[ 'text' ] ?></a>
				</div>

			<?php } elseif ( $attributes[ 'style' ] == 'rounded-icon-btn' ) { ?>

				<div class="btn-wrapper rounded-btn <?php echo $attributes[ 'type' ] ?>">
					<a style="background-color:<?php echo $attributes[ 'bg_color' ] ?>; color:<?php echo $attributes[ 'text_color' ] ?>"
					   href="<?php echo $attributes[ 'button_link' ] ?>"
					   target="<?php echo $attributes[ 'link_target' ] ?>"><i
							class="<?php echo $attributes[ 'icon' ] ?>"></i> <?php echo $attributes[ 'text' ] ?></a>
				</div>

			<?php } elseif ( $attributes[ 'style' ] == 'border-icon-btn' ) { ?>

				<div class="btn-wrapper only-border-btn <?php echo $attributes[ 'type' ] ?>">
					<a style="background-color:<?php echo $attributes[ 'bg_color' ] ?>; color:<?php echo $attributes[ 'text_color' ] ?>"
					   href="<?php echo $attributes[ 'button_link' ] ?>"
					   target="<?php echo $attributes[ 'link_target' ] ?>"><i
							class="<?php echo $attributes[ 'icon' ] ?>"></i> <?php echo $attributes[ 'text' ] ?></a>
				</div>

			<?php } ?>


			<?php
			return ob_get_clean();
		}

		add_shortcode( 'button', 'hippo_shortcode_button' );
	endif; // function_exists( 'hippo_shortcode_button' )