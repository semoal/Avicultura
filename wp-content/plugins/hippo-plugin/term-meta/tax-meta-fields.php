<?php

    defined( 'ABSPATH' ) or die( 'Keep Silent' );

    $fields = apply_filters( 'hippo-product-category-term-meta-options', array(
        array(
            'label' => __( 'Catalogue Position', 'hippo-plugin' ), // <label>
            'desc'  => __( 'Enter catalogue position.', 'hippo-plugin' ), // description
            'id'    => 'catalogue_position', // name of field
            'type'  => 'text', // text, url, image, select, checkbox, radio, map
            'size'  => '40', // field size
        ),
        array(
            'label'   => __( 'Catalogue column', 'hippo-plugin' ),
            // <label>
            'desc'    => __( 'Choose number of catalogue column. Calculated from medium device.', 'hippo-plugin' ),
            // description
            'id'      => 'catalogue_column',
            // name of field
            'type'    => 'select2',
            // text, url, image, select, checkbox, radio, map
            'options' => apply_filters( 'hippo-catalogue-slider-column-options', array(
                '3'  => __( '3 Columns 1/4', 'hippo-plugin' ),
                '4'  => __( '4 Columns 1/3', 'hippo-plugin' ),
                '6'  => __( '6 Columns 1/2', 'hippo-plugin' ),
                '12' => __( '12 Columns 1/1', 'hippo-plugin' ),
            ) ),
        ),
        array(
            'label'       => __( 'Catalogue column grid class', 'hippo-plugin' ),
            // <label>
            'desc'        => __( 'Enter catalogue grid column, without dot, use space to use multiple class. default is: col-md-*', 'hippo-plugin' ),
            // description
            'id'          => 'catalogue_grid',
            // name of field
            'type'        => 'text',
            // text, url, image, select, checkbox, radio, map
            'size'        => '40',
            // field size
            'placeholder' => __( 'col-sm-6 col-md-4', 'hippo-plugin' ),
        ),
        array(
            'label' => __( 'Catalogue Slider', 'hippo-plugin' ), // <label>
            'desc'  => __( 'Select a slider for catalogue slide', 'hippo-plugin' ), // description
            'id'    => 'product_catalogue_slider', // name of field
            'type'  => 'revslider', // text, url, image, select, checkbox, radio
        ),
    ) );

    new Hippo_Term_Meta( 'product_cat', 'product', $fields );


    if ( ! function_exists( 'hippo_variation_styling' ) ):

        function hippo_variation_styling() {

            $fields = array();

            $fields[ 'color' ] = array(
                array(
                    'label' => __( 'Color', 'hippo-plugin' ), // <label>
                    'desc'  => __( 'Choose a color', 'hippo-plugin' ), // description
                    'id'    => 'product_attribute_color', // name of field
                    'type'  => 'color'
                )
            );

            $fields[ 'image' ] = array(
                array(
                    'label' => __( 'Image', 'hippo-plugin' ), // <label>
                    'desc'  => __( 'Choose a Image', 'hippo-plugin' ), // description
                    'id'    => 'product_attribute_image', // name of field
                    'type'  => 'image'
                )
            );

            if ( function_exists( 'wc_get_attribute_taxonomies' ) ):

                // CHECK: woocommerce_product_option_terms filter on theme :)

                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if ( $attribute_taxonomies ) :
                    foreach ( $attribute_taxonomies as $tax ) :
                        $product_attr      = wc_attribute_taxonomy_name( $tax->attribute_name );
                        $product_attr_type = $tax->attribute_type;
                        if ( in_array( $product_attr_type, array( 'color', 'image' ) ) ) :
                            new Hippo_Term_Meta( $product_attr, 'product', $fields[ $product_attr_type ] );
                        endif; //  in_array( $product_attr_type, array( 'color', 'image' ) )
                    endforeach; // $attribute_taxonomies
                endif; // $attribute_taxonomies
            endif; // function_exists( 'wc_get_attribute_taxonomies' )

        }


        add_action( 'admin_init', 'hippo_variation_styling' );

    endif;

