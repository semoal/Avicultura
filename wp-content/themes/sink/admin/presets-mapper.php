<?php


	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$redux_opt_name = hippo_option_name();

	if ( ! function_exists( 'hippo_preset_mapper' ) ):

		function hippo_preset_mapper( $section ) {

			foreach ( $section[ 'fields' ] as $key => $fields ) {

				if ( isset( $fields[ 'presets' ] ) and is_array( $fields[ 'presets' ] ) ) {

					$preset_names   = $fields[ 'options' ];
					$preset_defined = $fields[ 'presets' ];
					$preset_id      = $fields[ 'id' ];
					$section_id     = $fields[ 'section_id' ];

					foreach ( $preset_names as $name => $value ) {

						foreach ( $preset_defined as $preset_define ) {

							$preset_define[ 'required' ]   = array( $preset_id, '=', $name );
							$preset_define[ 'id' ]         = $name . '-' . $preset_define[ 'id' ];
							$preset_define[ 'section_id' ] = $section_id;

							if ( isset( $preset_define[ 'default' ] ) and isset( $preset_define[ 'default' ][ $name ] ) ) {
								$preset_define[ 'default' ] = $preset_define[ 'default' ][ $name ];
							}

							$section[ 'fields' ][] = $preset_define;

						}
					}
				}
			}

			return $section;
		}

		add_filter( 'redux/options/' . $redux_opt_name . '/section/hippo_preset_manager', 'hippo_preset_mapper' );

	endif;
