<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$title_align = $el_width = $style = $title = $align =
	$color = $accent_color = $el_class = $layout = $css =
	$border_width = $add_icon = $i_type = $i_icon_fontawesome =
	$i_icon_openiconic = $i_icon_typicons = $i_icon_entypo =
	$i_icon_linecons = $i_color = $i_custom_color =
	$i_background_style = $i_background_color =
	$i_custom_background_color = $i_size = $i_css_animation = '';

	$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
	extract( $atts );

	$class = 'vc_separator wpb_content_element';

	$class .= ( '' !== $title_align ) ? ' vc_' . $title_align : '';
	$class .= ( '' !== $el_width ) ? ' vc_sep_width_' . $el_width : ' vc_sep_width_100';
	$class .= ( '' !== $style ) ? ' vc_sep_' . $style : '';
	$class .= ( '' !== $border_width ) ? ' vc_sep_border_width_' . $border_width : '';
	$class .= ( '' !== $align ) ? ' vc_sep_pos_' . $align : '';

	$class .= ( 'separator_no_text' === $layout ) ? ' vc_separator_no_text' : '';
	if ( '' !== $color && 'custom' !== $color ) {
		$class .= ' vc_sep_color_' . $color;
	}
	$inline_css = ( 'custom' === $color && '' !== $accent_color ) ? ' style="' . vc_get_css_color( 'border-color', $accent_color ) . '"' : '';

	$class_to_filter = $class;
	$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
	$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings[ 'base' ], $atts );

	if ( $show_category_link == 'yes' ) {
		$css_class .= ' hippo-vc_text_separator-with-link';
	}

	ob_start();
?>
<div class="<?php echo esc_attr( trim( $css_class ) ); ?>">
	<span class="vc_sep_holder vc_sep_holder_l">
		<span<?php echo $inline_css; ?> class="vc_sep_line"></span>
	</span>

	<?php if ( '' !== $title && 'separator_no_text' !== $layout ): ?>
		<?php if ( $show_category_link == 'yes' ) :
			$term      = get_term( $product_category_id, 'product_cat' );
			$term_link = get_term_link( $term );
			?>
			<h4><a href="<?php echo esc_html( $term_link ) ?>"><?php echo esc_html( $title ); ?></a></h4>
		<?php else: ?>
			<h4><?php echo esc_html( $title ); ?></h4>
		<?php endif; ?>
	<?php endif ?>
	<span class="vc_sep_holder vc_sep_holder_r">
		<span<?php echo $inline_css; ?> class="vc_sep_line"></span>
	</span>
</div>
<?php
	echo $this->endBlockComment( $this->getShortcode() );
	echo ob_get_clean();
?>
