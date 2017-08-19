<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$prefix = 'post_';

	$fields = array(

		array(
			'label' => __( 'Featured Video (.webm)', 'hippo-plugin' ), // <label>
			'desc'  => __( 'Webm Video like: example.webm', 'hippo-plugin' ), // description
			'id'    => $prefix . 'featured_webm', // field id and name
			'type'  => 'media', // type of field
		),
		array(
			'label' => __( 'Featured Video (.ogv)', 'hippo-plugin' ), // <label>
			'desc'  => __( 'Webm Video like: example.ogv', 'hippo-plugin' ), // description
			'id'    => $prefix . 'featured_ogv', // field id and name
			'type'  => 'media', // type of field
		),
		array(
			'label'   => __( 'Featured Video (.mp4)', 'hippo-plugin' ), // <label>
			'desc'    => __( 'Webm Video like: example.mp4', 'hippo-plugin' ), // description
			'id'      => $prefix . 'featured_mp4', // field id and name
			'type'    => 'media', // type of field
			'divider' => TRUE
		),
		array(
			'label'   => __( 'Embed Video', 'hippo-plugin' ),
			// <label>
			'desc'    => __( 'Embed video. Supported list: http://codex.wordpress.org/Embeds', 'hippo-plugin' ),
			// description
			'id'      => $prefix . 'video_embed',
			// field id and name
			'type'    => 'url',
			// type of field
			'divider' => TRUE
		),
	);

	/**
	 * Instantiate the class with all variables to create a meta box
	 * var $id string meta box id
	 * var $title string title
	 * var $fields array fields
	 * var $page string|array post type to add meta box to
	 * var $js bool including javascript or not
	 */
	new Hippo_Custom_Add_Meta_Box( 'post_video_meta', __( 'Video Options', 'hippo-plugin' ), $fields, 'post', TRUE );


	$fields = array(

		array(
			'label'   => __( 'Featured Audio (.mp3)', 'hippo-plugin' ), // <label>
			'desc'    => __( 'MP3 Audio like: example.mp3', 'hippo-plugin' ), // description
			'id'      => $prefix . 'featured_mp3', // field id and name
			'type'    => 'media', // type of field
			'divider' => TRUE
		),
		array(
			'label' => __( 'Featured Audio (.ogg)', 'hippo-plugin' ), // <label>
			'desc'  => __( 'OGG Audio like: example.ogg', 'hippo-plugin' ), // description
			'id'    => $prefix . 'featured_ogg', // field id and name
			'type'  => 'media', // type of field
		),
		array(
			'label'   => __( 'Embed Audio', 'hippo-plugin' ),
			// <label>
			'desc'    => __( 'Embed audio. Supported list: http://codex.wordpress.org/Embeds', 'hippo-plugin' ),
			// description
			'id'      => $prefix . 'audio_embed',
			// field id and name
			'type'    => 'url',
			// type of field
			'divider' => TRUE
		),
	);

	/**
	 * Instantiate the class with all variables to create a meta box
	 * var $id string meta box id
	 * var $title string title
	 * var $fields array fields
	 * var $page string|array post type to add meta box to
	 * var $js bool including javascript or not
	 */
	new Hippo_Custom_Add_Meta_Box( 'post_audio_meta', __( 'Audio Options', 'hippo-plugin' ), $fields, 'post', TRUE );


	$fields = array(


		array(
			'label' => __( 'Featured Gallery', 'hippo-plugin' ), // <label>
			'desc'  => __( 'Featured Gallery', 'hippo-plugin' ), // description
			'id'    => $prefix . 'featured_gallery', // field id and name
			'type'  => 'gallery', // type of field
		)
	);


	/**
	 * Instantiate the class with all variables to create a meta box
	 * var $id string meta box id
	 * var $title string title
	 * var $fields array fields
	 * var $page string|array post type to add meta box to
	 * var $js bool including javascript or not
	 */
	new Hippo_Custom_Add_Meta_Box( 'post_gallery_meta', __( 'Featured Gallery', 'hippo-plugin' ), $fields, 'post', TRUE );

