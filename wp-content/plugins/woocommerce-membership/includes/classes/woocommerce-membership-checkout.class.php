<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WooCommerce Checkout
 *
 * @class WooCommerce_Membership_Checkout
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Checkout')) {

class WooCommerce_Membership_Checkout
{

    /**
     * Constructor class
     *
     * @access public
     * @param mixed $id
     * @return void
     */
    public function __construct($id = null)
    {

    }

    /**
     * Check if cart contains membership product
     *
     * @access public
     * @return bool
     */
    public static function cart_contains_membership()
    {
        global $woocommerce;

        if (!empty($woocommerce->cart->cart_contents)) {
            foreach ($woocommerce->cart->cart_contents as $item) {
                if (WooCommerce_Membership_Product::is_membership($item['variation_id'] ? $item['variation_id'] : $item['product_id'])) {
                    return true;
                }
            }
        }

        return false;
    }

}

new WooCommerce_Membership_Checkout();

}
