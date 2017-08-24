<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Integration with subscription plugins
 *
 * @class WooCommerce_Membership_Subscription
 * @package Subscriptio
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Subscription')) {

class WooCommerce_Membership_Subscription
{

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Cancel activation/deactivation if subscriptions plugin defines its support for memberships
        add_filter('woocommerce_membership_cancel_activation', array($this, 'cancel_activation'), 10, 5);
        add_filter('woocommerce_membership_cancel_deactivation', array($this, 'cancel_deactivation'), 10, 5);

        // Allow subscription plugins to activate/deactivate memberships
        add_action('subscriptio_membership_activate', array($this, 'activate'), 10, 2);
        add_action('subscriptio_membership_deactivate', array($this, 'deactivate'), 10, 2);
    }

    /**
     * Cancel membership activation if subscriptions plugin defines its support for memberships
     *
     * @access public
     * @param bool $cancel
     * @param int $order_id
     * @param int $order_item_id
     * @param mixed $order_item
     * @param int $product_id
     * @return bool
     */
    public function cancel_activation($cancel, $order_id, $order_item_id, $order_item, $product_id)
    {
        if (apply_filters('woocommerce_membership_subscription_support', false)) {
            if (apply_filters('woocommerce_membership_product_is_subscription', false, $product_id)) {
                $plans = RightPress_WC_Legacy::order_item_get_meta($order_item, '_rpwcm_plans', false);
                $plans = (!empty($plans) && is_array($plans)) ? $plans : array();
                do_action('woocommerce_membership_subscription_membership_ids', $order_item_id, $plans, $order_id);
                return true;
            }
        }

        return $cancel;
    }

    /**
     * Cancel membership deactivation if subscriptions plugin defines its support for memberships
     *
     * @access public
     * @param bool $cancel
     * @param int $order_id
     * @param int $order_item_id
     * @param mixed $order_item
     * @param int $product_id
     * @return bool
     */
    public function cancel_deactivation($cancel, $order_id, $order_item_id, $order_item, $product_id)
    {
        if (apply_filters('woocommerce_membership_subscription_support', false)) {
            if (apply_filters('woocommerce_membership_product_is_subscription', false, $product_id)) {
                return true;
            }
        }

        return $cancel;
    }

    /**
     * Activate membership
     *
     * @access public
     * @param int $user_id
     * @param array $plan_ids
     * @return void
     */
    public function activate($user_id, $plan_ids = array())
    {
        if (is_array($plan_ids)) {
            foreach ($plan_ids as $plan_id) {
                WooCommerce_Membership_Plan::add_member($plan_id, $user_id);
            }
        }
    }

    /**
     * Deactivate membership
     *
     * @access public
     * @param int $user_id
     * @param array $plan_ids
     * @return void
     */
    public function deactivate($user_id, $plan_ids = array())
    {
        if (is_array($plan_ids)) {
            foreach ($plan_ids as $plan_id) {
                WooCommerce_Membership_Plan::remove_member($plan_id, $user_id);
            }
        }
    }

}

new WooCommerce_Membership_Subscription();

}
