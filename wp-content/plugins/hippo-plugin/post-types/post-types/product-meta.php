<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	// Product post meta
	$prefix = 'product_';

	$fields = array(

		array(
			'label'     => __( 'Product policy', 'hippo-plugin' ),
			'desc'      => __( 'Enter policy here', 'hippo-plugin' ),
			'id'        => $prefix . 'policy',
			'type'      => 'editor',
			'sanitizer' => 'wp_kses_post',
			'post_type' => array( 'product' ),
		),

	);

	new Hippo_Custom_Add_Meta_Box( 'product_meta', __( 'Additional Info', 'hippo-plugin' ), $fields, 'product', TRUE );

