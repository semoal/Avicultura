<?php

/**
 * Customer Membership Expiration Reminder email template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<?php do_action('woocommerce_email_header', $email_heading); ?>

<p><?php printf(__('Your membership %s on %s expires in %s days.', 'woocommerce-membership'), $plan_name, get_option('blogname'), $days); ?></p>

<?php do_action('woocommerce_email_footer'); ?>
