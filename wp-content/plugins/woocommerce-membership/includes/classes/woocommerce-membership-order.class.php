<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WooCommerce Orders
 *
 * @class WooCommerce_Membership_Order
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Order')) {

class WooCommerce_Membership_Order
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
        // Save plan configuration on the checkout
        if (RightPress_Helper::wc_version_gte('3.0')) {
            add_action('woocommerce_checkout_create_order_line_item', array($this, 'save_order_item_plans'), 10, 4);
        }
        else {
            add_action('woocommerce_add_order_item_meta', array($this, 'save_order_item_plans_legacy'), 10, 2);
        }

        add_filter('woocommerce_hidden_order_itemmeta', array($this, 'hide_membership_plans'));

        // Grant membership on these WooCommerce actions
        add_action('woocommerce_payment_complete', array($this, 'order_paid'), 9);
        add_action('woocommerce_order_status_processing', array($this, 'order_paid'), 9);
        add_action('woocommerce_order_status_completed', array($this, 'order_paid'), 9);

        // Remove membership on these WooCommerce actions
        add_action('woocommerce_order_status_cancelled', array($this, 'order_cancelled'));
        add_action('woocommerce_order_status_refunded', array($this, 'order_cancelled'));
        add_action('woocommerce_order_status_failed', array($this, 'order_cancelled'));

        // Trashed, untrashed and deleted
        add_action('before_delete_post', array($this, 'post_deleted'));
        add_action('trashed_post', array($this, 'post_trashed'));
        add_action('untrashed_post', array($this, 'post_untrashed'));

        // Handling product downloads
        add_filter('woocommerce_get_item_downloads', array($this, 'filter_download_links'), 10, 3);
        add_filter('woocommerce_customer_get_downloadable_products', array($this, 'filter_downloadable_products'));
        add_action('woocommerce_download_product', array($this, 'maybe_prevent_file_download'), 10, 6);
    }
    /**
     * Save membership plan IDs to order item meta
     *
     * Legacy method for pre WC 3.0 compatibility
     *
     * @access public
     * @param object $order_item
     * @param string $cart_item_key
     * @param array $values
     * @param object $order
     * @return void
     */
    public function save_order_item_plans($order_item, $cart_item_key, $values, $order)
    {
        $product_id = RightPress_WC_Legacy::order_item_get_product_id($order_item);
        $variation_id = RightPress_WC_Legacy::order_item_get_variation_id($order_item);
        $id = !empty($variation_id) ? $variation_id : $product_id;

        // Check if it's a membership product
        if (WooCommerce_Membership_Product::is_membership($id)) {
            foreach (WooCommerce_Membership_Product::get_membership_plans($id, 'enabled') as $plan_id => $plan) {
                $order_item->add_meta_data('_rpwcm_plans', $plan_id);
            }
        }
    }

    /**
     * Save membership plan IDs to order item meta
     *
     * Legacy method for pre WC 3.0 compatibility
     *
     * @access public
     * @param int $item_id
     * @param array $cart_item
     * @return void
     */
    public function save_order_item_plans_legacy($item_id, $cart_item)
    {
        $id = !empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id'];

        // Check if it's a membership product
        if (WooCommerce_Membership_Product::is_membership($id)) {
            foreach (WooCommerce_Membership_Product::get_membership_plans($id, 'enabled') as $plan_id => $plan) {
                wc_add_order_item_meta($item_id, '_rpwcm_plans', $plan_id);
            }
        }
    }

    /**
     * Hide membership keys on order items list
     *
     * @access public
     * @param array $keys
     * @return array
     */
    public function hide_membership_plans($keys)
    {
        $keys[] = '_rpwcm_plans';
        return $keys;
    }

    /**
     * Order paid - grant membership
     *
     * @access public
     * @param int $order_id
     * @return void
     */
    public function order_paid($order_id)
    {
        // Initialize order object
        $order = wc_get_order($order_id);

        // Check if memberships have not been granted already
        if (RightPress_WC_Legacy::order_get_meta($order, '_rpwcm_membership_granted', true)) {
            return;
        }
        else {
            RightPress_WC_Legacy::order_update_meta_data($order, '_rpwcm_membership_granted', '1');
        }

        // Get customer id
        $user_id = RightPress_WC_Legacy::order_get_customer_id($order);

        foreach ($order->get_items() as $item_id => $item) {

            // Get plans
            $plans = RightPress_WC_Legacy::order_item_get_meta($item, '_rpwcm_plans', false);

            // Only proceed if we have any plan IDs set
            if (!empty($plans) && is_array($plans)) {

                // Get correct ID
                $product_id = RightPress_WC_Legacy::order_item_get_product_id($item);
                $variation_id = RightPress_WC_Legacy::order_item_get_variation_id($item);
                $id = !empty($variation_id) ? $variation_id : $product_id;

                // Load product
                $product = wc_get_product($id);

                // Allow other plugins to cancel membership activation
                if (!apply_filters('woocommerce_membership_cancel_activation', false, $order_id, $item_id, $item, $id)) {

                    // Schedule expiration
                    $expiration_value = RightPress_WC_Legacy::product_get_meta($product, '_rpwcm_expiration_value', true);
                    $expiration_unit  = RightPress_WC_Legacy::product_get_meta($product, '_rpwcm_expiration_unit', true);

                    if (!empty($expiration_value) && !empty($expiration_unit)) {
                        $expiration_time  = WooCommerce_Membership_Plan::get_time_in_future($expiration_value, $expiration_unit);
                    }
                    else {
                        $expiration_time = null;
                    }

                    // Grant access now
                    foreach ($plans as $plan_id) {

                        // Get plan key
                        $plan_key = get_post_meta($plan_id, 'key', true);

                        // Check if expiration was already set for this order and plan
                        $plan_expiration_set = RightPress_WC_Legacy::order_get_meta($order, '_rpwcm_' . $plan_key . '_expiration', true);

                        // Check if user is already a member
                        if (WooCommerce_Membership_User::is_member($user_id, $plan_id)) {

                            // Get current expiration time
                            $current_expiration_time = RightPress_WC_Legacy::customer_get_meta($user_id, '_rpwcm_' . $plan_key . '_expires', true);

                            // Add new expiration time only if it wasn't set for this order and plan
                            if (!empty($expiration_time) && !empty($current_expiration_time) && empty($plan_expiration_set)) {

                                // Calculate new expiration time
                                $new_expiration_time = $current_expiration_time + $expiration_time - time();

                                // Update expiration date of user
                                RightPress_WC_Legacy::customer_update_meta_data($user_id, '_rpwcm_' . $plan_key . '_expires', $new_expiration_time);

                                // Postpone expiration event
                                WooCommerce_Membership_Scheduler::schedule_expiration($plan_id, $user_id, $new_expiration_time);
                            }
                        }
                        else {

                            // Add member
                            WooCommerce_Membership_Plan::add_member($plan_id, $user_id, $expiration_time);

                            // Schedule expiration if set
                            if ($expiration_time) {
                                WooCommerce_Membership_Scheduler::schedule_expiration($plan_id, $user_id, $expiration_time);
                            }
                        }

                        // If expiration was set, save this in order meta to prevent double scheduling for one order
                        if ($expiration_time && !$plan_expiration_set) {
                            RightPress_WC_Legacy::order_update_meta_data($order, '_rpwcm_' . $plan_key . '_expiration', 1);
                        }

                        // Schedule reminders only if subscription is not used
                        if (!apply_filters('woocommerce_membership_subscription_support', false)) {
                            if (!apply_filters('woocommerce_membership_product_is_subscription', false, $id)) {
                                WooCommerce_Membership_Scheduler::schedule_reminders($plan_id, $user_id);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Order cancelled - remove membership
     *
     * @access public
     * @param int $order_id
     * @return void
     */
    public function order_cancelled($order_id)
    {
        $order = wc_get_order($order_id);

        foreach ($order->get_items() as $item_id => $item) {

            // Get plans
            $plans = RightPress_WC_Legacy::order_item_get_meta($item, '_rpwcm_plans', false);

            // Only proceed if we have any plan IDs set
            if (!empty($plans) && is_array($plans)) {

                // Get correct ID
                $product_id = RightPress_WC_Legacy::order_item_get_product_id($item);
                $variation_id = RightPress_WC_Legacy::order_item_get_variation_id($item);
                $id = !empty($variation_id) ? $variation_id : $product_id;

                // Allow other plugins to cancel membership deactivation
                if (!apply_filters('woocommerce_membership_cancel_deactivation', false, $order_id, $item_id, $item, $id)) {

                    // Remove access now
                    foreach ($plans as $plan_id) {
                        WooCommerce_Membership_Plan::remove_member($plan_id, RightPress_WC_Legacy::order_get_customer_id($order));
                    }
                }
            }
        }
    }

    /**
     * Get array of membership plan objects from WooCommerce Order ID
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    public static function get_membership_plans_from_order_id($order_id)
    {
        $memberships = array();

        if ($order = wc_get_order($order_id)) {
            foreach ($order->get_items() as $item) {

                // Get correct ID
                $product_id = RightPress_WC_Legacy::order_item_get_product_id($item);
                $variation_id = RightPress_WC_Legacy::order_item_get_variation_id($item);
                $id = !empty($variation_id) ? $variation_id : $product_id;

                if ($product_membership_ids = WooCommerce_Membership_Product::get_membership_plans($id)) {
                    foreach ($product_membership_ids as $product_membership_id) {
                        if (!isset($memberships[$product_membership_id])) {
                            $memberships[$product_membership_id] = WooCommerce_Membership_Plan::cache($product_membership_id);
                        }
                    }
                }
            }
        }

        return $memberships;
    }

    /**
     * Display granted membership plans on single order view page
     * Currently this function is not used, added for later versions
     *
     * @access public
     * @param object $order
     * @return void
     */
    public function display_frontend_order_granted_plans($order)
    {
        $plans = WooCommerce_Membership_Order::get_membership_plans_from_order_id(RightPress_WC_Legacy::order_get_id($order));

        if (!empty($plans) && apply_filters('woocommerce_membership_display_order_granted_plans', true)) {
            RightPress_Helper::include_template('myaccount/membership-list', RPWCM_PLUGIN_PATH, 'woocommerce-membership', array(
                'plans' => $plans,
                'title' => __('My Memberships', 'woocommerce-membership'),
            ));
        }
    }

    /**
     * Order deleted
     *
     * @access public
     * @param int $post_id
     * @return void
     */
    public function post_deleted($post_id)
    {
        global $post_type;

        // WC31: Orders will no longer be posts
        if ($post_type == 'shop_order') {
            $this->order_cancelled($post_id);
        }
    }

    /**
     * Order trashed
     *
     * @access public
     * @param int $post_id
     * @return void
     */
    public function post_trashed($post_id)
    {
        global $post_type;

        // WC31: Orders will no longer be posts
        if ($post_type == 'shop_order') {
            $this->order_cancelled($post_id);
        }
    }

    /**
     * Order untrashed
     *
     * @access public
     * @param int $post_id
     * @return void
     */
    public function post_untrashed($post_id)
    {
        global $post_type;

        // WC31: Orders will no longer be posts
        if ($post_type == 'shop_order') {

            $order = wc_get_order($post_id);

            if (in_array(RightPress_WC_Legacy::order_get_status($order), array('processing', 'completed'))) {
                $this->order_paid($post_id);
            }
        }
    }

    /**
     * Filter download links on Order page
     *
     * @access public
     * @param array $files
     * @param mixed $item
     * @param object $order
     * @return array
     */
    public function filter_download_links($files, $item, $order)
    {
        // Iterate over files
        foreach ($files as $file_key => $file) {

            // Parse product id from file download url
            $parts = parse_url($file['download_url']);
            parse_str($parts['query'], $query);
            $product_id = $query['download_file'];

            // Check if user has access to this product
            if (!WooCommerce_Membership_User::has_access_to_product_downloads($product_id)) {
                unset($files[$file_key]);
            }
        }

        return $files;
    }

    /**
     * Filter download links on My Account page
     *
     * @access public
     * @param array $downloads
     * @return array
     */
    public function filter_downloadable_products($downloads)
    {
        // Iterate over downloads and check if user has access to them
        foreach ($downloads as $download_key => $download) {
            if (!WooCommerce_Membership_User::has_access_to_product_downloads($download['product_id'])) {
                unset($downloads[$download_key]);
            }
        }

        return $downloads;
    }

    /**
     * Maybe prevent actual file download
     *
     * @access public
     * @param string $email
     * @param string $order_key
     * @param int $product_id
     * @param int $user_id
     * @param int $download_id
     * @param int $order_id
     * @return void
     */
    public function maybe_prevent_file_download($email, $order_key, $product_id, $user_id, $download_id, $order_id)
    {
        // Check if user has access to this product files
        if (!WooCommerce_Membership_User::has_access_to_product_downloads($product_id)) {

            // Add notice
            RightPress_Helper::wc_add_notice(__('You no longer have access to this file.', 'woocommerce-membership'), 'error');

            // Redirect to My Account
            wp_redirect(get_permalink(wc_get_page_id('myaccount')));
            exit;
        }
    }

}

new WooCommerce_Membership_Order();

}
