<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customer Membership Expired email
 *
 * @class WooCommerce_Membership_Email_Customer_Membership_Expired
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Email_Customer_Membership_Expired')) {

class WooCommerce_Membership_Email_Customer_Membership_Expired extends WooCommerce_Membership_Email
{

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->id               = 'customer_membership_expired';
        $this->customer_email   = true;
        $this->title            = __('Membership expired', 'woocommerce-membership');
        $this->description      = __('Membership expired emails are sent to customers when memberships expire (if they are configured to expire).', 'woocommerce-membership');

        $this->heading  = __('Your membership expired', 'woocommerce-membership');
        $this->subject  = __('Your {site_title} membership expired', 'woocommerce-membership');

        // Call parent constructor
        parent::__construct();
    }

}
}
