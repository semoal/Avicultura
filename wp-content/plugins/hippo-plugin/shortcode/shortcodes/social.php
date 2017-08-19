<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! function_exists( 'hippo_register_social_shortcode' ) ):

		function hippo_register_social_shortcode( $shortcode ) {

			$register_social = array(
				'title'       => __( 'Social links', 'hippo-plugin' ),
				'description' => __( 'Social links', 'hippo-plugin' ),
			);


			$shortcode->register( 'social', $register_social );

			$register_social_link = array(
				'title'       => __( 'Social links', 'hippo-plugin' ),
				'description' => __( 'Social links', 'hippo-plugin' ),
				'child_of'    => array( 'social' ), // use if its a child
				'cloneable'   => TRUE, // use if its a child
				'attributes'  => array(

					'icon' => array(
						'type'        => 'icon',
						'label'       => __( 'Social Icons', 'hippo-plugin' ),
						'description' => __( 'Choose desire share icon', 'hippo-plugin' ),
						'options'     => hippo_fontawesome_icons()
					),
					'link' => array(
						'type'        => 'text', // text, textarea, color, select, select2, image, font, editor_contents
						'label'       => __( 'Social link', 'hippo-plugin' ),
						'description' => __( 'Link of a social site', 'hippo-plugin' )
					)
				)

			);

			$shortcode->register( 'social-link', $register_social_link );
		}

		add_action( 'hippo_register_shortcode', 'hippo_register_social_shortcode' );
	endif; // function_exists( 'hippo_register_social_shortcode' )

	if ( ! function_exists( 'hippo_shortcode_social' ) ):
		function hippo_shortcode_social( $atts, $contents = '' ) {
			$attributes = shortcode_atts( array(), $atts );
			ob_start();
			?>
			<div>
				<ul class="social-shares circle list-inline">
					<?php
						echo do_shortcode( $contents );
					?>
				</ul>
			</div>
			<?php

			return ob_get_clean();
		}

		add_shortcode( 'social', 'hippo_shortcode_social' );
	endif; // function_exists('hippo_shortcode_social')

	if ( ! function_exists( 'hippo_shortcode_social_link' ) ):
		function hippo_shortcode_social_link( $atts, $contents = '' ) {
			$attributes = shortcode_atts( array(
				                              'icon' => '',
				                              'link' => ''
			                              ), $atts );
			ob_start();
			?>

			<li><a href="<?php echo $attributes[ 'link' ] ?>" target="_blank"><i
						class="<?php echo $attributes[ 'icon' ] ?>"></i></a></li>
			<?php
			return ob_get_clean();
		}

		add_shortcode( 'social-link', 'hippo_shortcode_social_link' );

	endif; // function_exists('hippo_shortcode_social_link')

