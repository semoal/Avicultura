<?php

	/**
	 * Redux Framework is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 2 of the License, or
	 * any later version.
	 *
	 * Redux Framework is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
	 *
	 * @package     ReduxFramework
	 * @author      ThemeHippo
	 * @version     1.0
	 */

	// Exit if accessed directly
	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	// Don't duplicate me!
	if ( ! class_exists( 'ReduxFramework_extension_hippo_preset_color' ) ) :


		/**
		 * Main ReduxFramework_extension_hippo_preset_color extension class
		 *
		 * @since       3.1.6
		 */
		class ReduxFramework_extension_hippo_preset_color extends ReduxFramework {

			// Protected vars
			protected     $parent;
			public        $extension_url;
			public        $extension_dir;
			public static $theInstance;

			public function __construct( $parent ) {

				$this->parent     = $parent;
				$this->field_name = 'hippo_preset_color';

				if ( empty( $this->extension_dir ) ) {
					$this->extension_dir = trailingslashit( locate_template( sprintf( 'admin/redux-extensions/extensions/%s', $this->field_name ) ) );
				}


				self::$theInstance = $this;

				add_filter( 'redux/' . $this->parent->args[ 'opt_name' ] . '/field/class/' . $this->field_name, array(
					&$this,
					'overload_field_path'
				) ); // Adds the local field

			}

			public function getInstance() {
				return self::$theInstance;
			}

			// Forces the use of the embeded field path vs what the core typically would use
			public function overload_field_path( $field ) {
				return locate_template( sprintf( 'admin/redux-extensions/extensions/%1$s/%1$s/field_%1$s.php', $this->field_name ) );
			}

		} // class
	endif; // if
