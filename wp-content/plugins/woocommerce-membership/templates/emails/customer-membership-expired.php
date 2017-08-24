<?php

/**
 * Customer Membership Expired email template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<?php do_action('woocommerce_email_header', $email_heading); ?>

<p><?php printf(__('Your membership %s on %s has expired.', 'woocommerce-membership'), $plan_name, get_option('blogname')); ?></p>

<?php do_action('woocommerce_email_footer'); ?>
