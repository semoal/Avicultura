<?php

defined( 'ABSPATH' ) or die( 'Keep Silent' );

?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="wrapper" id="wrapper">

	<?php do_action( 'hippo_theme_before_inner_wrapper' ); ?>

	<div class="inner-wrapper pusher">

		<?php do_action( 'hippo_theme_start_inner_wrapper' ); ?>

		<?php if ( hippo_option( 'show-preloader', FALSE, TRUE ) ): ?>
			<div id="page-pre-loader" class="page-pre-loader-wrapper">
				<div class="page-pre-loader">
					<svg class="circular" viewBox="0 0 50 50">
						<circle class="path" cx="25" cy="25" r="20"></circle>
					</svg>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( hippo_option( 'header-top-bg-visibility', FALSE, TRUE ) ) :

			$custom_bg = "";
			if ( hippo_option( 'custom-top-bg', FALSE, FALSE ) and ! hippo_option( 'bg-color-option', FALSE, TRUE ) ) :
				$custom_bg = 'background-color: ' . hippo_option( 'custom-top-bg', 'background-color', '#5DCAD1' ) . ';';
				if ( hippo_option( 'custom-top-bg', 'background-image', FALSE ) ):
					$custom_bg .= 'background-image: url(' . hippo_option( 'custom-top-bg', 'background-image' ) . ');';
					$custom_bg .= 'background-position: ' . hippo_option( 'custom-top-bg', 'background-position' ) . ';';
					$custom_bg .= 'background-size: ' . hippo_option( 'custom-top-bg', 'background-size' ) . ';';
					$custom_bg .= 'background-repeat: ' . hippo_option( 'custom-top-bg', 'background-repeat' ) . ';';
					$custom_bg .= 'background-attachment: ' . hippo_option( 'custom-top-bg', 'background-attachment' ) . ';';
				endif;
			endif;

			if ( hippo_option( 'bg-color-option', FALSE, TRUE ) ) :
				$header_bg_style = hippo_option_get_header_background_style();
			else :
				$header_bg_style = 'bg-style-custom';
			endif;
			?>
			<div class="header-bg-wrapper <?php echo esc_attr( $header_bg_style ); ?>"
			     style="<?php echo esc_attr( $custom_bg ); ?>">
				<div class="header-bg"></div>
			</div>

		<?php endif; ?>

		<div class="container">
			<div class="row">
				<div class="contents">
<?php

	$header_style = hippo_option_get_header_style();

	if ( $header_style == 'header-style-two' ) :
		get_header( 'two' );
	elseif ( $header_style == 'header-style-three' ) :
		get_header( 'three' );
	elseif ( $header_style == 'header-style-four' ) :
		get_header( 'four' );
	else :
		get_header( 'one' );
	endif;

	get_header( 'page' );
