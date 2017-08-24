<?php

/**
 * Customer Membership Expiration Reminder email template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

echo $email_heading . "\n\n";

echo sprintf(__('Your membership %s on %s expires in %s days.', 'woocommerce-membership'), $plan_name, get_option('blogname'), $days) . "\n\n";

echo "****************************************************\n";

echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));
