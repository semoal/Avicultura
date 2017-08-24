<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customer Membership Expiration Reminder email
 *
 * @class WooCommerce_Membership_Email_Customer_Membership_Expiration_Reminder
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Email_Customer_Membership_Expiration_Reminder')) {

class WooCommerce_Membership_Email_Customer_Membership_Expiration_Reminder extends WooCommerce_Membership_Email
{

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->id               = 'customer_membership_expiration_reminder';
        $this->customer_email   = true;
        $this->title            = __('Membership expiration reminder', 'woocommerce-membership');
        $this->description      = __('Membership expiration reminder emails are sent to customers at predefined intervals before their membership expires.', 'woocommerce-membership');

        $this->heading  = __('Membership expiration reminder', 'woocommerce-membership');
        $this->subject  = __('Your {site_title} membership expires soon', 'woocommerce-membership');

        // Call parent constructor
        parent::__construct();
    }

}
}
