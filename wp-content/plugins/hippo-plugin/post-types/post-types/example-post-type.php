<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	function hippo_post_type_example() {

		$labels = array(
			'name'               => _x( 'Example', 'hippo-plugin' ),
			'singular_name'      => _x( 'Example', 'hippo-plugin' ),
			'menu_name'          => __( 'Example', 'hippo-plugin' ),
			'parent_item_colon'  => __( 'Parent Example:', 'hippo-plugin' ),
			'all_items'          => __( 'Example', 'hippo-plugin' ),
			'view_item'          => __( 'View Example', 'hippo-plugin' ),
			'add_new_item'       => __( 'Add New Example', 'hippo-plugin' ),
			'add_new'            => __( 'New Example', 'hippo-plugin' ),
			'edit_item'          => __( 'Edit Example', 'hippo-plugin' ),
			'update_item'        => __( 'Update Example', 'hippo-plugin' ),
			'search_items'       => __( 'Search Example', 'hippo-plugin' ),
			'not_found'          => __( 'No Example Item found', 'hippo-plugin' ),
			'not_found_in_trash' => __( 'No Example Item found in Trash', 'hippo-plugin' ),
		);
		$args   = array(
			'description'         => __( 'Example', 'hippo-plugin' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'comments' ),
			'taxonomies'          => array( 'portfolio-type' ),
			'hierarchical'        => FALSE,
			'public'              => TRUE,
			'show_ui'             => TRUE,
			'show_in_menu'        => TRUE,
			'show_in_nav_menus'   => TRUE,
			'show_in_admin_bar'   => TRUE,
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-images-alt',
			'can_export'          => TRUE,
			'has_archive'         => FALSE,
			'exclude_from_search' => TRUE,
			'publicly_queryable'  => TRUE,
			'capability_type'     => 'post',
		);


		register_post_type( 'example', $args );


	}

	// Hook into the 'init' action
	//add_action('init', 'hippo_post_type_example');


	$prefix = 'example_';

	// Project post meta

	$fields = array(

		array(
			'label'             => 'Repeatable', // <label>
			'id'                => $prefix . 'details', // field id and name
			'type'              => 'repeatable', // type of field


			'repeatable_fields' => array( // array of fields to be repeated

			                              array(
				                              'label' => 'Label',
				                              'desc'  => 'put portfolio details label',
				                              'id'    => 'portfolio_label',
				                              'type'  => 'text',
			                              ),
			                              array(
				                              'label' => 'Value',
				                              'desc'  => 'put portfolio ',
				                              'id'    => 'label_value',
				                              'type'  => 'text',
			                              ),


			)
		),
		array( // Post ID select box
		       'label' => 'Text', // <label>
		       'desc'  => 'Portfolio live link put here, leave blank for hide', // description
		       'id'    => $prefix . 'live_link', // field id and name
		       'type'  => 'text',
		),
		array( // Select box
		       'label'   => 'Select Box', // <label>
		       'desc'    => 'A description for the field.', // description
		       'id'      => $prefix . 'select', // field id and name
		       'type'    => 'select2', // type of field
		       'options' => array( // array of options
		                           'one'   => 'one',
		                           'two'   => 'two',
		                           'three' => 'three'
		       )
		),
		array( // Select box
		       'label'     => 'Post Select 2', // <label>
		       'desc'      => 'A description for the field.', // description
		       'id'        => $prefix . 'select', // field id and name
		       'type'      => 'post_select2', // type of field
		       'post_type' => 'post'
		),
		array( // Select box
		       'label' => 'Date', // <label>
		       'desc'  => 'A description for the field.', // description
		       'id'    => $prefix . 'date', // field id and name
		       'type'  => 'date',
		),
		array( // Select box
		       'label'  => 'Slider', // <label>
		       'desc'   => 'A description for the field.', // description
		       'id'     => $prefix . 'slider', // field id and name
		       'type'   => 'slider',
		       'suffix' => '%',
		),
		array( // Select box
		       'label'   => 'Icons', // <label>
		       'desc'    => 'A description for the field.', // description
		       'id'      => $prefix . 'icon', // field id and name
		       'type'    => 'icons',
		       'options' => hippo_fontawesome_icons(),
		),
		array( // Select box
		       'label' => 'Image', // <label>
		       'desc'  => 'A description for the field.', // description
		       'id'    => $prefix . 'image', // field id and name
		       'type'  => 'image'
		),
		array( // Select box
		       'label' => 'Color', // <label>
		       'desc'  => 'A description for the field.', // description
		       'id'    => $prefix . 'color', // field id and name
		       'type'  => 'color'
		),
		array( // Checkbox group
		       'label'   => 'Checkbox Group', // <label>
		       'desc'    => 'A description for the field.', // description
		       'id'      => $prefix . 'checkbox_group', // field id and name
		       'type'    => 'checkbox_group', // type of field
		       'options' => array( // array of options
		                           'one'   => array( // array key needs to be the same as the option value
		                                             'label' => 'Option One', // text displayed as the option
		                                             'value' => 'one' // value stored for the option
		                           ),
		                           'two'   => array(
			                           'label' => 'Option Two',
			                           'value' => 'two'
		                           ),
		                           'three' => array(
			                           'label' => 'Option Three',
			                           'value' => 'three'
		                           )
		       )
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
	// new Hippo_Custom_Add_Meta_Box('example_meta', 'Example Meta', $fields, 'example', TRUE);


	$fields = array(

		array( // Select box
		       'label' => 'Gallery', // <label>
		       'desc'  => 'A description for the field.', // description
		       'id'    => $prefix . 'gallery', // field id and name
		       'type'  => 'gallery'
		),
	);

	//new Hippo_Custom_Add_Meta_Box('example_gallery_meta', 'Example Meta Gallery', $fields, 'example', TRUE);

	$fields = array(

		array( // Select box
		       'label' => 'Map', // <label>
		       'desc'  => 'A description for the field.', // description
		       'id'    => $prefix . 'map', // field id and name
		       'type'  => 'map'
		),
	);

	//new Hippo_Custom_Add_Meta_Box('example_map_meta', 'Example Map ', $fields, 'example', TRUE);

