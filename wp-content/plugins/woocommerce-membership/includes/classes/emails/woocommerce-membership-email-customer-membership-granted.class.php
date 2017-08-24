<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customer Membership Granted email
 *
 * @class WooCommerce_Membership_Email_Customer_Membership_Granted
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Email_Customer_Membership_Granted')) {

class WooCommerce_Membership_Email_Customer_Membership_Granted extends WooCommerce_Membership_Email
{

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->id               = 'customer_membership_granted';
        $this->customer_email   = true;
        $this->title            = __('Membership granted', 'woocommerce-membership');
        $this->description      = __('Membership granted emails are sent to customers when their memberships are activated.', 'woocommerce-membership');

        $this->heading  = __('Membership granted', 'woocommerce-membership');
        $this->subject  = __('Your {site_title} membership has been granted', 'woocommerce-membership');

        // Call parent constructor
        parent::__construct();
    }

}
}
