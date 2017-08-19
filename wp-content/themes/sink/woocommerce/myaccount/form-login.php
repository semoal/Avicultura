<?php
	/**
	 * Login Form
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see     https://docs.woothemes.com/document/template-structure/
	 * @author  WooThemes
	 * @package WooCommerce/Templates
	 * @version 2.6.0
	 */

	defined( 'ABSPATH' ) or die( 'Keep Silent' );
?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

	<div class="row">

		<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

		<div class="col-md-6 col-xs-12" id="customer_login">
			<?php else: ?>
			<div class="col-xs-12" id="customer_login">
				<?php endif; ?>

				<h2><?php esc_html_e( 'Login', 'sink' ); ?></h2>

				<form method="post" class="login">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<div class="input-field">
						<label for="username"><?php esc_html_e( 'Username or email address', 'sink' ); ?> <span
								class="required">*</span></label>
						<input type="text" class="form-control" name="username" id="username"
						       value="<?php if ( ! empty( $_POST[ 'username' ] ) ) {
							       echo esc_attr( $_POST[ 'username' ] );
						       } ?>"/>
					</div>
					<div class="input-field">
						<label for="password"><?php esc_html_e( 'Password', 'sink' ); ?> <span class="required">*</span></label>
						<input class="form-control" type="password" name="password" id="password"/>
					</div>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<p class="form-row">
						<?php wp_nonce_field( 'woocommerce-login' ); ?>
						<input type="submit" class="button" name="login"
						       value="<?php esc_attr_e( 'Login', 'sink' ); ?>"/>
						<label for="rememberme" class="inline">
							<input name="rememberme" type="checkbox" id="rememberme"
							       value="forever"/> <?php esc_html_e( 'Remember me', 'sink' ); ?>
						</label>
					</p>

					<p class="lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'sink' ); ?></a>
					</p>

					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>

				<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
			</div> <!-- .col-md-6 col-xs-12 -->
			<?php else: ?>
		</div> <!-- .col-xs-12 -->
	<?php endif; ?>

		<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
			<div class="col-md-6 col-xs-12" id="customer_register">

				<h2><?php esc_html_e( 'Register', 'sink' ); ?></h2>

				<form method="post" class="register">

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

						<div class="input-field">
							<label for="reg_username"><?php esc_html_e( 'Username', 'sink' ); ?> <span class="required">*</span></label>
							<input type="text" class="form-control" name="username" id="reg_username"
							       value="<?php if ( ! empty( $_POST[ 'username' ] ) ) {
								       echo esc_attr( $_POST[ 'username' ] );
							       } ?>"/>
						</div>

					<?php endif; ?>

					<div class="input-field">
						<label for="reg_email"><?php esc_html_e( 'Email address', 'sink' ); ?> <span
								class="required">*</span></label>
						<input type="email" class="form-control" name="email" id="reg_email"
						       value="<?php if ( ! empty( $_POST[ 'email' ] ) ) {
							       echo esc_attr( $_POST[ 'email' ] );
						       } ?>"/>
					</div>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

						<div class="input-field">
							<label for="reg_password"><?php esc_html_e( 'Password', 'sink' ); ?> <span class="required">*</span></label>
							<input type="password" class="form-control" name="password" id="reg_password"/>
						</div>

					<?php endif; ?>

					<!-- Spam Trap -->
					<div style="<?php echo( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label
							for="trap"><?php esc_html_e( 'Anti-spam', 'sink' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1"/></div>

					<?php do_action( 'woocommerce_register_form' ); ?>
					<?php do_action( 'register_form' ); ?>

					<p class="form-row">
						<?php wp_nonce_field( 'woocommerce-register' ); ?>
						<input type="submit" class="button" name="register"
						       value="<?php esc_attr_e( 'Register', 'sink' ); ?>"/>
					</p>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>

			</div>

		<?php endif; ?>
	</div> <!-- row -->
<?php do_action( 'woocommerce_after_customer_login_form' );