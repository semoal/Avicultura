<?php

/**
 * Customer Membership Granted email template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

echo $email_heading . "\n\n";

echo sprintf(__('Your membership %s on %s has been granted.', 'woocommerce-membership'), $plan_name, get_option('blogname')) . "\n\n";

echo "****************************************************\n";

echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));
