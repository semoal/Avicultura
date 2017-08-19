<?php
	/**
	 * Checkout coupon form
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
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
	 * @version 2.2
	 */

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! wc_coupons_enabled() ) {
		return;
	}

?>

<?php if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) : ?>
<div class="col-md-12 coupon-form-wrapper">
	<?php else : ?>
	<div class="col-md-6 coupon-form-wrapper">
		<?php endif; ?>

		<?php $info_message = apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'sink' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'sink' ) . '</a>' );
			wc_print_notice( $info_message, 'notice' );
		?>

		<form class="checkout_coupon" method="post" style="display:none">

			<div class="input-group">
				<input type="text" name="coupon_code" class="form-control"
				       placeholder="<?php esc_attr_e( 'Coupon code', 'sink' ); ?>" id="coupon_code" value=""/>

			<span class="input-group-btn">
	            <input type="submit" class="button" name="apply_coupon"
	                   value="<?php esc_attr_e( 'Apply Coupon', 'sink' ); ?>"/>
	         </span>
			</div>
			<div class="clear clearfix"></div>
		</form>
	</div>