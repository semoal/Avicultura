<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$output = '';
	$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
	extract( $atts );

	$width = wpb_translateColumnWidthToSpan( $width );
	$width = vc_column_offset_class_merge( $offset, $width );

	$css_classes = array(
		$this->getExtraClass( $el_class ),
		//'col', // <---------- Add materializecss column class
		$width,
		//vc_shortcode_custom_css_class( $css ),
	);

	$wrapper_attributes = array();

	$css_class   = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings[ 'base' ], $atts ) );
	$clear_class = preg_replace( '/\s+/', ' ', $clear_fix_classes );


	// Convert to bootstrap column class

	$css_class = str_ireplace( 'vc_col-sm-offset-', 'col-sm-offset-', $css_class );
	$css_class = str_ireplace( 'vc_col-xs-offset-', 'col-xs-offset-', $css_class );
	$css_class = str_ireplace( 'vc_col-md-offset-', 'col-md-offset-', $css_class );
	$css_class = str_ireplace( 'vc_col-lg-offset-', 'col-lg-offset-', $css_class );


	$css_class = str_ireplace( 'vc_col-sm-', 'col-sm-', $css_class );
	$css_class = str_ireplace( 'vc_col-xs-', 'col-xs-', $css_class );
	$css_class = str_ireplace( 'vc_col-md-', 'col-md-', $css_class );
	$css_class = str_ireplace( 'vc_col-lg-', 'col-lg-', $css_class );

	$css_class = str_ireplace( 'vc_hidden-lg', 'hidden-lg', $css_class );
	$css_class = str_ireplace( 'vc_hidden-md', 'hidden-md', $css_class );
	$css_class = str_ireplace( 'vc_hidden-sm', 'hidden-sm', $css_class );
	$css_class = str_ireplace( 'vc_hidden-xs', 'hidden-xs', $css_class );

	if ( ! empty( $custom_columns ) ) {
		$css_class = $custom_columns . ' ' . $css_class;
	}
	// End Convert to bootstrap column class


	$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

	$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= '<div class="wpb_wrapper ' . vc_shortcode_custom_css_class( $css ) . '">';
	$output .= wpb_js_remove_wpautop( $content );
	$output .= '</div>' . $this->endBlockComment( '.wpb_wrapper' );
	$output .= '</div>' . $this->endBlockComment( $this->getShortcode() );
	if ( $active_clearfix == 'yes' ) {
		$output .= '<div class="clearfix ' . esc_attr( trim( $clear_class ) ) . '"></div>';
	}

	$output .= $this->endBlockComment( $this->getShortcode() );
	echo $output;