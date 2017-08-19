<?php


	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	add_action( 'init', 'hippo_tinymce_buttons' );

	function hippo_tinymce_buttons() {
		add_filter( "mce_external_plugins", "hippo_add_mce_buttons" );
		add_filter( 'mce_buttons', 'hippo_register_mce_buttons' );
	}

	function hippo_add_mce_buttons( $plugin_array ) {
		$plugin_array[ 'hippo_buttons' ] = HIPPO_PLUGIN_URL . 'editor-button/editor-button.js';

		return $plugin_array;
	}

	function hippo_register_mce_buttons( $buttons ) {
		array_push( $buttons, 'addspan' );

		return $buttons;
	}


