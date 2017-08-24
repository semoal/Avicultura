<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WordPress User
 *
 * @class WooCommerce_Membership_User
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_User')) {

class WooCommerce_Membership_User
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
        // Enforce user registration
        add_action('woocommerce_before_checkout_form', array($this, 'enforce_user_registration'), 99);
        add_action('woocommerce_before_checkout_process', array($this, 'enforce_user_registration'), 99);
        add_filter('wc_checkout_params', array($this, 'enforce_user_registration_js'), 99);

        // Add capability to grant access to multiple users at once
        add_action('admin_footer-users.php', array($this, 'add_users_bulk_action'));
        add_action('load-users.php', array($this, 'process_users_bulk_action'));

        // Add new user to membership plans
        add_action('user_register', array($this, 'add_new_user'));

        // Intercept export to CSV call
        if (isset($_GET['rpwcm_members_csv'])) {
            add_action('init', array($this, 'push_members_csv'));
        }
    }

    /**
     * Allow no guest checkout when membership product is in cart
     *
     * @access public
     * @param object $checkout
     * @return void
     */
    public function enforce_user_registration($checkout)
    {
        // User already registered?
        if (is_user_logged_in()) {
            return;
        }

        if (!$checkout) {
            global $woocommerce;
            $checkout = &$woocommerce->checkout;
        }

        // Only proceed if cart contains membership
        if (WooCommerce_Membership_Checkout::cart_contains_membership()) {

            // Enable registration
            $checkout->enable_signup = true;

            // Enforce registration
            $checkout->enable_guest_checkout = false;

            // Must create account
            $checkout->must_create_account = true;
        }
    }

    /**
     * Allow no guest checkout (Javascript part)
     *
     * @access public
     * @param array $properties
     * @return array
     */
    public function enforce_user_registration_js($properties)
    {
        // User already registered?
        if (is_user_logged_in()) {
            return $properties;
        }

        // No membership in cart?
        if (!WooCommerce_Membership_Checkout::cart_contains_membership()) {
            return $properties;
        }

        $properties['option_guest_checkout'] = 'no';

        return $properties;
    }

    /**
     * Get link to user profile with a full name
     *
     * @access public
     * @param int $user_id
     * @param string $name
     * @return string
     */
    public static function get_user_full_name_link($user_id, $name = '')
    {
        $name = !empty($name) ? $name : self::get_user_full_name($user_id);
        return '<a href="user-edit.php?user_id=' . $user_id . '">' . $name . '</a>';
    }

    /**
     * Get user full name from database
     *
     * @access public
     * @param int $user_id
     * @return string
     */
    public static function get_user_full_name($user_id)
    {
        $name = __('Unknown', 'woocommerce-membership');

        if ($user = get_userdata($user_id)) {
            $first_name = get_the_author_meta('first_name', $user_id);
            $last_name = get_the_author_meta('last_name', $user_id);

            if ($first_name || $last_name) {
                $name = join(' ', array($first_name, $last_name));
            }
            else {
                $name = $user->display_name;
            }
        }

        return $name;
    }

    /**
     * Check if user has at least one of the provided roles
     *
     * @access public
     * @param array $roles
     * @param int|object|null $user
     * @return bool
     */
    public static function user_has_role($roles, $user = null)
    {
        // Get user
        if (!is_object($user)) {
            $user = empty($user) ? wp_get_current_user() : get_userdata($user);
        }

        // No user?
        if (empty($user)) {
            return false;
        }

        return array_intersect($roles, (array) $user->roles) ? true : false;
    }

    /**
     * Get all user capabilities
     *
     * @access public
     * @param int|object|null $user
     * @return array
     */
    public static function get_user_capabilities($user = null)
    {
        $capabilities = array();

        // Get user
        if (!is_object($user)) {
            $user = empty($user) ? wp_get_current_user() : get_userdata($user);
        }

        // No user?
        if (empty($user)) {
            return array();
        }

        // Extract capabilities
        foreach ($user->allcaps as $cap_key => $cap) {
            if ($cap) {
                $capabilities[] = $cap_key;
            }
        }

        return (array) apply_filters('woocommerce_membership_user_capabilities', $capabilities, $user);
    }

    /**
     * Get enabled plan keys of user
     *
     * @access public
     * @param mixed $user
     * @return array
     */
    public static function get_enabled_keys($user = null)
    {
        $capabilities = self::get_user_capabilities($user);
        return WooCommerce_Membership_Plan::enabled_keys_only($capabilities);
    }

    /**
     * Check if user is a member of given plan
     *
     * @access public
     * @param int $user_id
     * @param int $plan_id
     * @return array
     */
    public static function is_member($user_id, $plan_id)
    {
        // Get user
        $user = get_userdata($user_id);

        // Get plan key
        $plan_key = get_post_meta($plan_id, 'key', true);

        // Get user keys
        $enabled_keys = self::get_enabled_keys($user);

        return array_intersect($enabled_keys, (array) $plan_key) ? true : false;
    }

    /**
     * Get all plan keys of user
     *
     * @access public
     * @return array
     */
    public static function get_all_keys()
    {
        $capabilities = WooCommerce_Membership_User::get_user_capabilities();
        $all_plan_keys = WooCommerce_Membership_Plan::get_list_of_all_plan_keys();
        $user_plans = array();

        foreach (array_keys($all_plan_keys) as $plan_key) {

            if (in_array($plan_key, $capabilities)) {
                $user_plans[] = $plan_key;
            }
        }

        return $user_plans;
    }

    /**
     * Get list of all users
     *
     * @access public
     * @return array
     */
    public static function get_all_users()
    {
        $users = array('' => '');

        foreach(get_users() as $user) {
            $users[$user->ID] = '#' . $user->ID . ' - ' . $user->user_email;
        }

        return $users;
    }

    /**
     * Generate a CSV file containing members of specific plan and push it to browser
     *
     * @access public
     * @return void
     */
    public function push_members_csv()
    {
        // Check if current user can download a list of members
        if (!WooCommerce_Membership::is_authorized('csv_export')) {
            return;
        }

        $plan_key = $_GET['rpwcm_members_csv'];
        $search_query = isset($_GET['search']) ? $_GET['search'] : false;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        $users_amount = isset($_GET['users']) ? $_GET['users'] : 0;

        // Check if valid plan key was passed in
        if (!WooCommerce_Membership_Plan::key_exists($plan_key)) {
            return;
        }

        // Compose file name
        $filename = 'Members_' . $plan_key . '_' . date('Y-m-d') . '.csv';

        // Send headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Disable caching
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Open writable stream
        $output = fopen('php://output', 'w');

        // Output CSV headers
        fputcsv($output, array(
            __('User ID', 'woocommerce-membership'),
            __('Username', 'woocommerce-membership'),
            __('Full Name', 'woocommerce-membership'),
            __('Email', 'woocommerce-membership'),
            __('Member Since', 'woocommerce-membership'),
            __('Membership Expires', 'woocommerce-membership'),
        ));

        // Output members
        foreach (self::get_members_list_for_csv($plan_key, $search_query, $offset, $users_amount) as $member) {
            fputcsv($output, $member);
        }

        // Close stream
        fclose($output);
        exit;
    }

    /**
     * Get list of members of specific membership plan ready to be used in CSV export
     *
     * @access public
     * @param string $plan_key
     * @param string $search_query
     * @param int $offset
     * @param int $users_amount
     * @return void
     */
    public static function get_members_list_for_csv($plan_key, $search_query = false, $offset = 0, $users_amount = 0)
    {
        $members = array();
        $fields = array('user_login');

        // Maybe get users from search
        if ($search_query) {
            $members_raw = WooCommerce_Membership_Plan::members_search($plan_key, $search_query, $fields, $offset, $users_amount);
        }
        // Get users normally
        else {
            $members_raw = WooCommerce_Membership_Plan::get_members_list($plan_key, $fields, $offset, $users_amount);
        }

        // Get list of members and iterate over it
        foreach ($members_raw as $member) {

            // Get full name
            $full_name = self::get_user_full_name($member->ID);

            // Get Member Since date
            $member_since = RightPress_WC_Legacy::customer_get_meta($member->ID, '_rpwcm_' . $plan_key . '_since', true);
            $member_since = !empty($member_since) ? RightPress_Helper::get_adjusted_datetime($member_since) : '';

            // Get Member Expires date
            if ($expires = RightPress_WC_Legacy::customer_get_meta($member->ID, '_rpwcm_' . $plan_key . '_expires', true)) {
                $expires = !empty($expires) ? RightPress_Helper::get_adjusted_datetime($expires) : '';
            }
            else {
                $expires = __('Never', 'woocommerce-membership');
            }

            $members[] = array(
                $member->ID,
                $member->user_login,
                $full_name,
                $member->user_email,
                $member_since,
                $expires,
            );
        }

        return $members;
    }

    /**
     * Check if user has access to product downloads
     *
     * @access public
     * @param int $product_id
     * @return bool
     */
    public static function has_access_to_product_downloads($product_id)
    {
        // Stop here if product is not a membership product
        if (!WooCommerce_Membership_Product::is_membership($product_id)) {
            return true;
        }

        // Stop here if membership product is also a subscription product
        if (apply_filters('woocommerce_membership_subscription_support', false)) {
            if (apply_filters('woocommerce_membership_product_is_subscription', false, $product_id)) {
                return true;
            }
        }

        // Skip admin user
        if (WooCommerce_Membership_Post::skip_admin()) {
            return true;
        }

        // Get corresponding membership plan ids
        $plan_ids = WooCommerce_Membership_Product::get_membership_plan_ids($product_id);

        // Iterate over membership plan ids
        foreach ($plan_ids as $plan_id) {

            // Check if current user is member of a given plan
            if (self::is_member(get_current_user_id(), $plan_id)) {
                return true;
            }
        }

        // No active plans for this product found
        return false;
    }

    /**
     * Add bulk actions to users list
     *
     * @access public
     * @param array $actions
     * @return void
     */
    public function add_users_bulk_action($actions)
    {
        // Check if user performing this action is authorized
        if (WooCommerce_Membership::is_authorized('user_bulk_action')) {

            // Load view
            include RPWCM_PLUGIN_PATH . 'includes/views/backend/users/bulk_actions.php';
        }
    }

    /**
     * Process bulk actions
     *
     * @access public
     * @param array $actions
     * @return void
     */
    public function process_users_bulk_action()
    {
        // Get bulk action
        $wp_list_table = _get_list_table('WP_Users_List_Table');
        $action = $wp_list_table->current_action();

        // Action unsupported
        if (!in_array($action, array('rpwcm_grant_access'))) {
            return;
        }

        // Get return URL
        $return_url = remove_query_arg(array(
            'action', 'action2', 'tags_input', 'post_author', 'comment_status',
            'ping_status', '_status', 'post', 'bulk_edit', 'post_view'
        ), wp_get_referer());

        $return_url = $return_url ? $return_url : admin_url('users.php');

        // Add page number
        $pagenum = $wp_list_table->get_pagenum();
        $return_url = add_query_arg('paged', $pagenum, $return_url);

        // Security check
        check_admin_referer('bulk-users');

        // Get users ids
        if (isset($_REQUEST['users'])) {
            $user_ids = array_map('intval', (array) $_REQUEST['users']);
        }

        // No user ids?
        if (empty($user_ids)) {
            wp_redirect($return_url);
            exit;
        }

        // Redirect user to plan selection page
        wp_redirect(add_query_arg(array(
            'return_url'    => $return_url,
            'user_ids'      => $user_ids,
        ), admin_url('edit.php?post_type=membership_plan&page=rpwcm_bulk_grant_access')));
        exit;
    }

    /**
     * Get list of users by user ids
     *
     * @access public
     * @param array $user_ids
     * @return array
     */
    public static function get_list_of_users_by_ids($user_ids)
    {
        $list = array();
        $user_ids = (array) $user_ids;

        foreach ($user_ids as $user_id) {
            $user = get_userdata($user_id);

            if ($user) {
                $list[$user_id] = '#' . $user_id . ' - ' . $user->user_email;
            }
        }

        return $list;
    }

    /**
     * Add new user to membership plans
     *
     * @access public
     * @param int $user_id
     * @return void
     */
    public function add_new_user($user_id)
    {
        // Get membership plans that have this option enabled
        $query = new WP_Query(array(
            'post_type'         => 'membership_plan',
            'post_status'       => array('publish', 'pending', 'draft', 'future', 'private'),
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'meta_query'        => array(
                array(
                    'key'       => 'add_new_users_automatically',
                    'value'     => '1',
                    'compare'   => '=',
                ),
            ),
            'tax_query' => array(
                array(
                    'taxonomy'  => 'plan_status',
                    'field'     => 'slug',
                    'terms'     => 'enabled',
                ),
            ),
        ));

        // Iterate over plan ids
        foreach ($query->posts as $plan_id) {

            // Add member to plan
            WooCommerce_Membership_Plan::add_member($plan_id, $user_id);
        }
    }

}

new WooCommerce_Membership_User();

}
