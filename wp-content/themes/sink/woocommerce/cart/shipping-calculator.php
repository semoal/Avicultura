<?php
	/**
	 * Shipping Calculator
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/cart/shipping-calculator.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see           https://docs.woothemes.com/document/template-structure/
	 * @author        WooThemes
	 * @package       WooCommerce/Templates
	 * @version       2.0.8
	 */

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( 'no' === get_option( 'woocommerce_enable_shipping_calc' ) || ! WC()->cart->needs_shipping() ) {
		return;
	}
?>
<div class="row">
	<div class="col-md-12">

		<?php do_action( 'woocommerce_before_shipping_calculator' ); ?>

		<form id="woocommerce-shipping-calculator-form" class="woocommerce-shipping-calculator"
		      action="<?php echo esc_url( wc_get_cart_url() ); ?>"
		      method="post">


			<h2><a href="#" class="shipping-calculator-button"><?php esc_html_e( 'Calculate Shipping', 'sink' ); ?></a></h2>

			<section class="shipping-calculator-form" style="display:none;">

				<p class="form-row form-row-wide" id="calc_shipping_country_field">

					<label for="calc_shipping_country"><?php esc_html_e( 'Select a country&hellip;', 'sink' ); ?></label>

					<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state"
					        rel="calc_shipping_state">
						<option value=""><?php esc_html_e( 'Select a country&hellip;', 'sink' ); ?></option>
						<?php
							foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), FALSE ) . '>' . esc_html( $value ) . '</option>';
							}
						?>
					</select>
				</p>

				<p class="form-row form-row-wide" id="calc_shipping_state_field">

					<label for="calc_shipping_state" class=""><?php esc_html_e( 'State / county', 'sink' ); ?></label>

					<?php
						$current_cc = WC()->customer->get_shipping_country();
						$current_r  = WC()->customer->get_shipping_state();
						$states     = WC()->countries->get_states( $current_cc );

						// Hidden Input
						if ( is_array( $states ) && empty( $states ) ) {

							?><input type="hidden" name="calc_shipping_state" id="calc_shipping_state"
							         placeholder="<?php esc_attr_e( 'State / county', 'sink' ); ?>" /><?php

							// Dropdown Input
						} elseif ( is_array( $states ) ) {

							?>
							<select class="state_select" name="calc_shipping_state" id="calc_shipping_state"
							        placeholder="<?php esc_attr_e( 'State / county', 'sink' ); ?>">
								<option value=""><?php esc_html_e( 'Select a state&hellip;', 'sink' ); ?></option>
								<?php
									foreach ( $states as $ckey => $cvalue ) {
										echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, FALSE ) . '>' . esc_html( $cvalue ) . '</option>';
									}
								?>
							</select>
							<?php

							// Standard Input
						} else {

							?><input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>"
							         placeholder="<?php esc_attr_e( 'State / county', 'sink' ); ?>"
							         name="calc_shipping_state" id="calc_shipping_state" /><?php

						}
					?>
				</p>

				<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', FALSE ) ) : ?>

					<p class="form-row form-row-wide" id="calc_shipping_city_field">

						<label for="calc_shipping_city" class=""><?php esc_html_e( 'City', 'sink' ); ?></label>

						<input type="text" class="input-text"
						       value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>"
						       placeholder="<?php esc_attr_e( 'City', 'sink' ); ?>" name="calc_shipping_city"
						       id="calc_shipping_city"/>
					</p>

				<?php endif; ?>

				<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', TRUE ) ) : ?>

					<p class="form-row form-row-wide" id="calc_shipping_postcode_field">

						<label for="calc_shipping_postcode"
						       class=""><?php esc_html_e( 'Postcode / Zip', 'sink' ); ?></label>


						<input type="text" class="input-text"
						       value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>"
						       placeholder="<?php esc_attr_e( 'Postcode / Zip', 'sink' ); ?>"
						       name="calc_shipping_postcode" id="calc_shipping_postcode"/>
					</p>

				<?php endif; ?>

				<p>
					<button type="submit" name="calc_shipping" value="1"
					        class="button"><?php esc_html_e( 'Update Totals', 'sink' ); ?></button>
				</p>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>

			</section>
		</form>

		<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>
	</div>
</div>