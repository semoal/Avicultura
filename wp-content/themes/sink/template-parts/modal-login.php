<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
?>
<div id="login" class="modal fade">

	<div class="modal-header">
		<a data-dismiss="modal" href="#" aria-label="<?php esc_html_e( 'Close', 'sink' ); ?>"
		   class="modal-close close">&times;</a>
	</div>
	<!-- .modal-header -->

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="form-container">
				<div class="card">
					<h2 class="title"><?php esc_html_e( 'Sign-In', 'sink' ); ?></h2>

					<?php if ( function_exists( 'wc_print_notices' ) ): wc_print_notices(); endif; ?>

					<form method="post" class="user-login">

						<?php do_action( 'woocommerce_login_form_start' ); ?>

						<div class="input-field input-container">
							<label
								for="login-username"><?php esc_html_e( 'Username or Email Address', 'sink' ); ?></label>
							<input id="login-username" class="form-control validate" type="text"
							       name="username"
							       value="<?php if ( ! empty( $_POST[ 'username' ] ) ) {
								       echo esc_attr( $_POST[ 'username' ] );
							       } ?>" required>
						</div>
						<div class="input-field input-container">
							<label for="login-password"><?php esc_html_e( 'Password', 'sink' ); ?></label>
							<input id="login-password" class="form-control validate" type="password" name="password"
							       required>
						</div>
						<?php do_action( 'woocommerce_login_form' ); ?>
						<div class="button-container login-submit">
							<?php wp_nonce_field( 'woocommerce-login' ); ?>
							<input type="submit" class="btn btn-primary" name="login"
							       value="<?php esc_attr_e( 'Submit', 'sink' ); ?>"/>
						</div>

						<?php do_action( 'woocommerce_login_form_end' ); ?>

					</form>

					<div class="footer">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'sink' ); ?></a>
					</div>
				</div>
				<!-- .card -->

				<?php if (true) : ?>
				<?php //if ( get_option( 'users_can_register' ) ) : ?>
					<div class="card alt">
						<div class="toggle"></div>
						<h2 class="title"><?php esc_html_e( 'Register', 'sink' ); ?>
							<span class="close"></span>
						</h2>

						<form method="post" class="user-register">
							<?php do_action( 'woocommerce_register_form_start' ); ?>

							<div class="input-field input-container">
								<label for="register-username"><?php esc_html_e( "Username", "sink" ); ?></label>
								<input id="register-username" class="form-control" type="text" name="username"
								       value="<?php if ( ! empty( $_POST[ 'username' ] ) ) {
									       echo esc_attr( $_POST[ 'username' ] );
								       } ?>" required>
							</div>
							<div class="input-field input-container">
								<label for="register-password"><?php esc_html_e( "Password", "sink" ); ?></label>
								<input id="register-password" class="form-control" type="password" name="password"
								       required>
							</div>
							<div class="input-field input-container">
								<label for="register-email"><?php esc_html_e( "Email", "sink" ); ?></label>
								<input id="register-email" class="form-control" type="email" name="email"
								       value="<?php if ( ! empty( $_POST[ 'email' ] ) ) {
									       echo esc_attr( $_POST[ 'email' ] );
								       } ?>"/>
							</div>

							<?php do_action( 'woocommerce_register_form' ); ?>
							<?php do_action( 'register_form' ); ?>

							<div class="button-container">
								<?php wp_nonce_field( 'woocommerce-register' ); ?>
								<input type="submit" class="btn btn-default" name="register"
								       value="<?php esc_attr_e( 'Register', 'sink' ); ?>"/>
							</div>

							<?php do_action( 'woocommerce_register_form_end' ); ?>
						</form>
					</div> <!-- /.card alt -->
				<?php endif; ?>
			</div>
			<!-- /.form-container -->
		</div>
		<!-- /.modal-content -->
	</div>
</div> <!-- .modal -->