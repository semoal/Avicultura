<?php

/**
 * Customer Membership Granted email template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<?php do_action('woocommerce_email_header', $email_heading); ?>

<p><?php printf(__('Your membership %s on %s has been granted.', 'woocommerce-membership'), $plan_name, get_option('blogname')); ?></p>

<?php do_action('woocommerce_email_footer'); ?>
