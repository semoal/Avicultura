<?php
    /**
     * Lost password reset form.
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @see     https://docs.woocommerce.com/document/template-structure/
     * @author  WooThemes
     * @package WooCommerce/Templates
     * @version 3.0.0
     */

    defined( 'ABSPATH' ) or die( 'Keep Silent' );

    wc_print_notices();
?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password">

    <p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'sink' ) ); ?></p>

    <div class="input-field">
        <label for="password_1"><?php esc_html_e( 'New password', 'sink' ); ?> <span class="required">*</span></label>
        <input type="password" class="form-control" name="password_1" id="password_1"/>
    </div>

    <div class="input-field">
        <label for="password_2"><?php esc_html_e( 'Re-enter new password', 'sink' ); ?> <span class="required">*</span></label>
        <input type="password" class="form-control" name="password_2" id="password_2"/>
    </div>

    <input type="hidden" name="reset_key" value="<?php echo esc_attr( $args[ 'key' ] ); ?>"/>
    <input type="hidden" name="reset_login" value="<?php echo esc_attr( $args[ 'login' ] ); ?>"/>

    <div class="clear"></div>

    <?php do_action( 'woocommerce_resetpassword_form' ); ?>

    <p class="form-row">
        <input type="hidden" name="wc_reset_password" value="true"/>
        <input type="submit" class="btn btn-primary button" value="<?php esc_attr_e( 'Save', 'sink' ); ?>"/>
    </p>

    <?php wp_nonce_field( 'reset_password' ); ?>

</form>
