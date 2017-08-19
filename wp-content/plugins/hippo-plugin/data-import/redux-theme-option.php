<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	class Hippo_RedixThemeOption_Import {

		public function __construct( $file ) {
			if ( file_exists( $file ) ) {
				$this->import_option_data( $file );
			}
		}

		public function import_option_data( $import_file ) {

			if ( function_exists( 'hippo_option_name' ) ) {
				$ReduxFramework = ReduxFrameworkInstances::get_instance( hippo_option_name() );

				$fetch        = file_get_contents( $import_file );
				$options_data = apply_filters( 'hippo_import_process_theme_option_data', $fetch, $fetch );
				$options_data = (array) json_decode( $options_data, TRUE );


				foreach ( $options_data as $key => $value ) {
					$ReduxFramework->set( $key, $value );
				}

				?>

				<div class="narrow updated below-h2">
					<p><?php _e( 'Theme Options Imported successfully.', 'hippo-plugin' ) ?></p>
				</div>
				<?php
			}
		}
	}