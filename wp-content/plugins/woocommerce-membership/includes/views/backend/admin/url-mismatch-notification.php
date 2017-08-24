<?php

/**
 * View for site URL mismatch notification
 * Displayed on development/staging websites or when user changes main website URL
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="message" class="error rpwcm_url_mismatch">
    <p>
        <strong><?php _e('WooCommerce Membership URL mismatch', 'woocommerce-membership'); ?></strong>
    </p>
    <p>
        <?php _e('Your website URL has recently been changed. Customer membership emails have been disabled to prevent duplicate emails originating from development or staging servers.', 'woocommerce-membership'); ?><br />
        <?php _e('If you have moved this website permanently and would like to re-enable these features, select appropriate action below.', 'woocommerce-membership'); ?>
    </p>
    <form action="" method="post">
        <button class="button-primary" name="rpwcm_url_mismatch_action" value="ignore"><?php _e('Hide this warning', 'woocommerce-membership'); ?></button>
        <button class="button" name="rpwcm_url_mismatch_action" value="change"><?php _e('Make current URL primary', 'woocommerce-membership'); ?></button>
    </form>
</div>
