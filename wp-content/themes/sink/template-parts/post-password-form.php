<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	global $post;
	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );

?>

<form action="<?php echo esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) ?>" method="post">

	<?php esc_html_e( "To view this protected post, enter the password below:", 'sink' ) ?>

	<div class="h-form-inline">
		<div class="form-input">
			<div class="input-field">
				<label for="<?php echo esc_attr( $label ) ?>"><?php esc_html_e( "Password:", 'sink' ) ?></label>
				<input class="form-control" name="post_password" id="<?php echo esc_attr( $label ) ?>" type="password"/>
			</div>
		</div>
		<div class="btn-submit">
			<button class="btn btn-primary" type="submit" name="Submit"><?php esc_html_e( "Submit", 'sink' ) ?></button>
		</div>
	</div>
</form>
