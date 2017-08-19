<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$languages = wpml_get_active_languages_filter( '', 'skip_missing=0&orderby=code' );

	if ( ! empty( $languages ) ) : ?>
		<select id="lansel" onchange="window.location=this.value">
			<?php foreach ( $languages as $language ) : ?>

				<?php
				$selected = $language[ 'active' ];

				if ( $selected == 1 ) :
					$selected = 'selected';
				else :
					$selected = "";
				endif;
				?>
				<option
					value="<?php echo esc_url( $language[ 'url' ] ) ?>" <?php echo esc_attr( $selected ) ?>><?php echo esc_html( $language[ 'language_code' ] ) ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	endif;