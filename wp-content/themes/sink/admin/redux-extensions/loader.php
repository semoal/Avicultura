<?php

	// Replace {$redux_opt_name} with your opt_name.
	// Also be sure to change this function name!

	$redux_opt_name = hippo_option_name();

	if ( ! function_exists( 'redux_register_hippo_extension_loader' ) ) :

		function redux_register_hippo_extension_loader( $ReduxFramework ) {

			$extension_path = trailingslashit( locate_template( 'admin/redux-extensions/extensions' ) );


			foreach ( glob( $extension_path . "*", GLOB_ONLYDIR ) as $folder ) :

				$extension_class_dir_name = basename( $folder );
				$extension_class_name     = 'ReduxFramework_Extension_' . $extension_class_dir_name;


				if ( ! class_exists( $extension_class_name ) ) :

					$class_file = sprintf( 'admin/redux-extensions/extensions/%1$s/extension_%1$s.php', $extension_class_dir_name );
					$class_file = apply_filters( 'redux/extension/' . $ReduxFramework->args[ 'opt_name' ] . '/' . $extension_class_dir_name, $class_file );

					locate_template( $class_file, TRUE );

					new $extension_class_name( $ReduxFramework );

				endif;

			endforeach;


		}

		// Modify {$redux_opt_name} to match your opt_name
		add_action( "redux/extensions/{$redux_opt_name}/before", 'redux_register_hippo_extension_loader', 0 );
	endif;
