<?php


	$prefix = 'example_';

	// Project post meta

	$fields = array(

		array(
			'label' => 'Repeatable', // <label>
			'id'    => $prefix.'details', // field id and name
			'type'  => 'repeatable', // type of field


			'repeatable_fields' => array ( // array of fields to be repeated

			                               array(
				                               'label'    => 'Label',
				                               'desc'     => 'put portfolio details label',
				                               'id'       => 'portfolio_label',
				                               'type'     => 'text',
			                               ),

			                               array(
				                               'label'     => 'Value',
				                               'desc'      => 'put portfolio ',
				                               'id'        => 'label_value',
				                               'type'      => 'text',
			                               ),


			)
		),

		array( // Post ID select box
		       'label' => 'Text', // <label>
		       'desc'  => 'Portfolio live link put here, leave blank for hide', // description
		       'id'    =>  $prefix.'live_link', // field id and name
		       'type'  => 'text', ),

		array( // Select box
		       'label'	=> 'Select Box', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'select', // field id and name
		       'type'	=> 'select2', // type of field
		       'options' => array ( // array of options
		                            'one' =>  'one',
		                            'two' =>  'two',
		                            'three' => 'three'
		       )
		),
		array( // Select box
		       'label'	=> 'Post Select 2', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'select', // field id and name
		       'type'	=> 'post_select2', // type of field
		       'post_type' => 'post'
		),

		array( // Select box
		       'label'	=> 'Date', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'date', // field id and name
		       'type'	=> 'date',
		),
		array( // Select box
		       'label'	=> 'Slider', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'slider', // field id and name
		       'type'	=> 'slider',
		       'suffix'	=> '%',
		),
		array( // Select box
		       'label'	=> 'Icons', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'icon', // field id and name
		       'type'	=> 'icons',
		       'options'	=> hippo_fontawesome_icons(),
		),
		array( // Select box
		       'label'	=> 'Image', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'image', // field id and name
		       'type'	=> 'image'
		),
		array( // Select box
		       'label'	=> 'Color', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'color', // field id and name
		       'type'	=> 'color'
		),

		array ( // Checkbox group
		        'label'	=> 'Checkbox Group', // <label>
		        'desc'	=> 'A description for the field.', // description
		        'id'	=> $prefix.'checkbox_group', // field id and name
		        'type'	=> 'checkbox_group', // type of field
		        'options' => array ( // array of options
		                             'one' => array ( // array key needs to be the same as the option value
		                                              'label' => 'Option One', // text displayed as the option
		                                              'value'	=> 'one' // value stored for the option
		                             ),
		                             'two' => array (
			                             'label' => 'Option Two',
			                             'value'	=> 'two'
		                             ),
		                             'three' => array (
			                             'label' => 'Option Three',
			                             'value'	=> 'three'
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
	new Hippo_Custom_Add_Meta_Box('example_meta', 'Example Meta', $fields, 'example', TRUE);


	$fields = array(

		array( // Select box
		       'label'	=> 'Gallery', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'gallery', // field id and name
		       'type'	=> 'gallery'
		),
	);

	new Hippo_Custom_Add_Meta_Box('example_gallery_meta', 'Example Meta Gallery', $fields, 'example', TRUE);

	$fields = array(

		array( // Select box
		       'label'	=> 'Map', // <label>
		       'desc'	=> 'A description for the field.', // description
		       'id'	=> $prefix.'map', // field id and name
		       'type'	=> 'map'
		),
	);

	new Hippo_Custom_Add_Meta_Box('example_map_meta', 'Example Map ', $fields, 'example', TRUE);

