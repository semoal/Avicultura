<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WordPress posts (including pages and custom post types)
 *
 * WC31: Restricting access to products will no longer work this way after WC moves products from WP post storage to some other storage
 *
 * @class WooCommerce_Membership_Post
 * @package WooCommerce_Membership
 * @author RightPress
 */
if (!class_exists('WooCommerce_Membership_Post')) {

class WooCommerce_Membership_Post
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
        // Backend
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 1, 2);
        add_action('save_post', array($this, 'save_post'), 9, 2);
        add_filter('manage_posts_columns', array($this, 'add_membership_column'));
        add_filter('manage_pages_columns', array($this, 'add_membership_column'));
        add_action('manage_posts_custom_column', array($this, 'add_membership_column_value'), 10, 2);
        add_action('manage_pages_custom_column', array($this, 'add_membership_column_value'), 10, 2);

        // Bulk/Quick edit
        add_action('bulk_edit_custom_box', array($this, 'bulk_edit'), 10, 2);
        // Quick Edit not yet usable in version 1.2 (problems passing existing values)
        // add_action('quick_edit_custom_box', array($this, 'quick_edit'), 10, 2);

        // Frontend
        add_action('template_redirect', array($this, 'do_redirect'));
        add_action('template_redirect', array($this, 'perform_requested_url_block'));
        add_filter('the_posts', array($this, 'filter_posts'));
        add_filter('get_pages', array($this, 'filter_posts'));
        add_filter('the_content', array($this, 'filter_content'));
        add_filter('get_the_excerpt', array($this, 'filter_content'));
        add_filter('wp_get_nav_menu_items', array($this, 'filter_menu'), 10, 3);
        add_filter('posts_where', array($this, 'expand_posts_where'));

        // Shortcodes
        add_shortcode('woocommerce_members_only', array($this, 'shortcode_members_only'));
        add_shortcode('woocommerce_non_members_only', array($this, 'shortcode_non_members_only'));
        add_shortcode('woocommerce_members_content_list', array($this, 'shortcode_members_only_content_list'));
        add_shortcode('woocommerce_member_active_plans', array($this, 'shortcode_show_active_plans'));
        add_shortcode('woocommerce_member_all_plans', array($this, 'shortcode_show_all_plans'));
        add_shortcode('woocommerce_member_active_plans_expire', array($this, 'shortcode_show_active_plans_expire'));
        add_shortcode('woocommerce_member_all_plans_expire', array($this, 'shortcode_show_all_plans_expire'));
        add_shortcode('woocommerce_member_active_plans_number', array($this, 'shortcode_show_active_plans_number'));
        add_shortcode('woocommerce_member_all_plans_number', array($this, 'shortcode_show_all_plans_number'));
        add_shortcode('woocommerce_member_plan_expire_left', array($this, 'shortcode_plan_expire_left'));
    }

    /**
     * Add meta boxes
     *
     * @access public
     * @param string $post_type
     * @param object $post
     * @return void
     */
    public function add_meta_boxes($post_type, $post)
    {
        // Add content access metabox to all post types
        if (WooCommerce_Membership_Plan::get_list_of_all_plans()) {
            // WC31: Orders, products etc will no longer be posts
            if (!in_array($post_type, array('membership_plan', 'shop_order', 'shop_coupon', 'product_variation'))) {
                if (!apply_filters('woocommerce_membership_skip_post_type', false, $post_type)) {
                    add_meta_box(
                        'rpwcm_post_membership',
                        __('Restrict Access', 'woocommerce-membership'),
                        array($this, 'render_meta_box'),
                        $post_type,
                        'side',
                        'high'
                    );
                }
            }
        }
    }

    /**
     * Render content access restriction meta box
     *
     * @access public
     * @param mixed $post
     * @return void
     */
    public function render_meta_box($post)
    {
        global $type_now;

        // Get restriction method
        $method = get_post_meta($post->ID, '_rpwcm_post_restriction_method', true);

        // Get membership plan list
        $plans = WooCommerce_Membership_Plan::get_list_of_all_plan_keys();

        // Get preselected options
        $selected = get_post_meta($post->ID, '_rpwcm_only_caps');

        // Load view
        include RPWCM_PLUGIN_PATH . 'includes/views/backend/post/restrict-access.php';
    }

    /**
     * Save post meta box
     *
     * @access public
     * @param int $post_id
     * @param object $post
     * @return void
     */
    public function save_post($post_id, $post)
    {
        // Check if required properties were passed in
        if (empty($post_id) || empty($post)) {
            return;
        }

        // Make sure user has permissions to edit this post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Make sure it is not a draft save action
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || is_int(wp_is_post_autosave($post)) || is_int(wp_is_post_revision($post))) {
            return;
        }

        // Make sure post type is allowed
        // WC31: Orders, products etc will no longer be posts
        if (in_array($post->post_type, array('membership_plan', 'shop_order', 'shop_coupon', 'product_variation'))) {
            return;
        }
        if (apply_filters('woocommerce_membership_skip_post_type', false, $post->post_type)) {
            return;
        }

        // Get all plan keys
        $plans = WooCommerce_Membership_Plan::get_list_of_all_plan_keys();

        // Get values for bulk and quick edit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $add_caps = !empty($_POST['rpwcm_quick_add_plans']) ? $_POST['rpwcm_quick_add_plans'] : array();
            $remove_caps = !empty($_POST['rpwcm_quick_remove_plans']) ? $_POST['rpwcm_quick_remove_plans'] : array();
            $method = !empty($_POST['_rpwcm_post_restriction_method_quick']) ? $_POST['_rpwcm_post_restriction_method_quick'] : 'no_change';

        }
        else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $add_caps = !empty($_GET['rpwcm_bulk_add_plans']) ? $_GET['rpwcm_bulk_add_plans'] : array();
            $remove_caps = !empty($_GET['rpwcm_bulk_remove_plans']) ? $_GET['rpwcm_bulk_remove_plans'] : array();
            $method = !empty($_GET['_rpwcm_post_restriction_method_bulk']) ? $_GET['_rpwcm_post_restriction_method_bulk'] : 'no_change';
        }

        // Handle bulk and quick edit
        if (!empty($add_caps) || !empty($remove_caps) || isset($_POST['_rpwcm_post_restriction_method_quick']) || isset($_GET['_rpwcm_post_restriction_method_bulk'])) {

            // Get existing caps for this post
            $post_caps = RightPress_Helper::unwrap_post_meta(get_post_meta($post_id, '_rpwcm_only_caps'));

            // Check method
            $method = in_array($method, array('no_change', 'none', 'all_members', 'members_with_plans', 'non_members', 'users_without_plans')) ? $method : 'none';

            // Update method
            if ($method != 'no_change') {
                update_post_meta($post_id, '_rpwcm_post_restriction_method', $method);
            }

            // Need caps?
            if (in_array($method, array('no_change', 'members_with_plans', 'users_without_plans'))) {

                // Add caps
                foreach ($add_caps as $cap) {
                    if (isset($plans[$cap]) && !in_array($cap, $post_caps)) {
                        add_post_meta($post_id, '_rpwcm_only_caps', $cap);
                    }
                }

                // Remove caps
                foreach ($remove_caps as $cap) {
                    if (in_array($cap, $post_caps)) {
                        delete_post_meta($post_id, '_rpwcm_only_caps', $cap);
                    }
                }
            }

            // Delete all caps
            else {
                delete_post_meta($post_id, '_rpwcm_only_caps');
            }
        }

        // Handle regular edit
        else {

            // Make sure the correct post ID was passed from form
            if (empty($_POST['post_ID']) || $_POST['post_ID'] != $post_id) {
                return;
            }

            // Get restriction method
            $method = !empty($_POST['_rpwcm_post_restriction_method']) ? $_POST['_rpwcm_post_restriction_method'] : 'none';
            $method = in_array($method, array('none', 'all_members', 'members_with_plans', 'non_members', 'users_without_plans')) ? $method : 'none';

            // Update method
            update_post_meta($post_id, '_rpwcm_post_restriction_method', $method);

            // Delete all existing values
            delete_post_meta($post_id, '_rpwcm_only_caps');

            // Add new values
            if (in_array($method, array('members_with_plans', 'users_without_plans')) && !empty($_POST['_rpwcm_only_caps'])) {
                foreach ($_POST['_rpwcm_only_caps'] as $cap) {
                    if (isset($plans[$cap])) {
                        add_post_meta($post_id, '_rpwcm_only_caps', $cap);
                    }
                }
            }
        }
    }

    /**
     * Check if user has access to post
     *
     * @access public
     * @param int $post_id
     * @param int $user_id
     * @return bool
     */
    public static function user_has_access_to_post($post_id, $user_id = null)
    {
        // Get access restriction method
        $method = get_post_meta($post_id, '_rpwcm_post_restriction_method', true);

        // Get keys
        $plan_keys = get_post_meta($post_id, '_rpwcm_only_caps');

        // Empty means that we don't restrict access to this post
        if (empty($method) || $method == 'none') {
            return true;
        }

        // Not logged in and show to members-only?
        if (!is_user_logged_in() && in_array($method, array('all_members', 'members_with_plans'))) {
            return false;
        }

        // Not logged in and show to non-members-only?
        else if (!is_user_logged_in() && in_array($method, array('non_members', 'users_without_plans'))) {
            return true;
        }

        // Get user ID if one has not been passed
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        // Get user
        $user = get_user_by('id', $user_id);

        // Can't get user?
        if (!$user) {
            return true;
        }

        // Get user capabilities
        $user_capabilities = WooCommerce_Membership_User::get_user_capabilities($user);
        $user_capabilities = WooCommerce_Membership_Plan::enabled_keys_only($user_capabilities);

        // All members
        if ($method == 'all_members' && !empty($user_capabilities)) {
            return true;
        }

        // All non-members
        if ($method == 'non_members' && empty($user_capabilities)) {
            return true;
        }

        // Members with specific plans
        if ($method == 'members_with_plans') {
            foreach ($plan_keys as $plan_key) {
                if (in_array($plan_key, $user_capabilities)) {
                    return true;
                }
            }
        }

        // Users without specific plans
        if ($method == 'users_without_plans') {
            $match_found = false;

            foreach ($plan_keys as $plan_key) {
                if (in_array($plan_key, $user_capabilities)) {
                    $match_found = true;
                }
            }

            if (!$match_found) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if we need to grant this user access because of specific area of site or specific role
     *
     * @access public
     * @return bool
     */
    public static function skip_admin()
    {
        if (is_admin() || WooCommerce_Membership_User::user_has_role(apply_filters('woocommerce_membership_grant_access_roles', array('administrator')))) {
            return (!WooCommerce_Membership::$debug && WooCommerce_Membership::opt('admin_access'));
        }

        return false;
    }

    /**
     * Filter out posts that user does not have access to
     *
     * @access public
     * @param array $posts
     * @return array
     */
    public function filter_posts($posts)
    {
        // Skip admin
        if (self::skip_admin()) {
            return $posts;
        }

        // Iterate over posts
        foreach ($posts as $post_key => $post) {

            // If it's a product page, make sure we need to hide it
            // WC31: Products will no longer be posts
            if ($post->post_type == 'product' && WooCommerce_Membership_Product::show_restricted_product()) {
                continue;
            }

            // If it's a singular post/page, make sure we don't have a redirect configured
            if (is_singular() && self::redirect_restricted_posts()) {
                continue;
            }

            // Check if user has access to current post
            if (!self::user_has_access_to_post($post->ID)) {

                // Filter out restricted post
                unset($posts[$post_key]);
            }
        }

        // Return filtered posts
        return $posts;
    }

    /**
     * Filter content (post content and except)
     *
     * @access public
     * @param string $content
     * @return string
     */
    public function filter_content($content)
    {
        global $post;

        // Current user has access to content?
        // WC31: Products will no longer be posts
        if (self::skip_admin() || !isset($post->ID) || self::user_has_access_to_post($post->ID) || $post->post_type == 'product') {
            return $content;
        }

        return '';
    }

    /**
     * Filter menu items that user has not access to
     *
     * @access public
     * @param array $items
     * @param object $menu
     * @param array $args
     * @return array
     */
    public function filter_menu($items, $menu, $args)
    {
        // Skip admin
        if (self::skip_admin()) {
            return $items;
        }

        // Check each menu item
        foreach ($items as $item_key => $item) {
            if (!self::user_has_access_to_post($item->object_id)) {
                unset($items[$item_key]);
            }
        }

        // Return filtered items
        return $items;
    }

    /*
     * Redirect user with no access to specific page (if configured in settings)
     *
     * @access public
     * @return void
     */
    public function do_redirect()
    {
        // Ignore product pages and non-singular pages
        if (!is_singular()) {
            return;
        }

        // Do not redirect if user has access to current post
        if (self::skip_admin() || self::user_has_access_to_post(get_the_ID())) {
            return;
        }

        // If it's product, maybe we need to show it?
        // WC31: Products will no longer be posts
        if (get_post_type() == 'product' && WooCommerce_Membership_Product::show_restricted_product()) {
            return;
        }

        // Check if we need to redirect restricted posts (error 404 will be displayed instead)
        if (!self::redirect_restricted_posts()) {
            return;
        }

        // Redirect to custom URL
        wp_redirect(self::get_redirect_url());
        exit();
    }

    /**
     * Check if user needs to be redirected to custom URL from restricted posts
     *
     * @access public
     * @return bool
     */
    public static function redirect_restricted_posts()
    {
        return self::get_redirect_url() ? true : false;
    }

    /**
     * Get redirect url
     *
     * @access public
     * @return mixed
     */
    public static function get_redirect_url()
    {
        return WooCommerce_Membership::opt('redirect_url');
    }

    /**
     * Check if visitor access to a specific URL is allowed by URL rules
     *
     * @access public
     * @return bool
     */
    public static function url_access_allowed()
    {
        // Get both requested and blocked URLs and compare them
        $request_url            = RightPress_Helper::get_request_url();
        $url_restriction_rules  = WooCommerce_Membership::opt('block_urls');

        // Skip admin
        if (self::skip_admin()) {
            return true;
        }

        // Track if URL was matched (even if visitor does not have access)
        $url_matched = false;

        // Iterate over restriction rules
        foreach ($url_restriction_rules as $url_restriction_rule) {

            // Check if restriction rule is valid
            if (empty($url_restriction_rule['url']) || empty($url_restriction_rule['method'])) {
                continue;
            }

            // Get restriction method
            $method = $url_restriction_rule['method'];

            // Check if plan keys are set for those methods that require them
            if (in_array($method, array('members_with_plans', 'users_without_plans')) && empty($url_restriction_rule['plans'])) {
                continue;
            }

            // Prepare the url
            $the_url = str_replace('*', '%RIGHTPRESS%', $url_restriction_rule['url']);
            $the_url = preg_quote($the_url);
            $the_url = str_replace('%RIGHTPRESS%', '.*', $the_url);

            // Check if URL is matched
            if (preg_match('[' . $the_url . ']i', $request_url) !== 1) {
                continue;
            }

            // URL was matched
            $url_matched = true;

            // Checking the guest user
            if (!is_user_logged_in()) {

                // Guests are not allowed to access members-only content
                if (in_array($method, array('all_members', 'members_with_plans'))) {
                    continue;
                }
                // Guests are allowed to access non-members-only content
                else if (in_array($method, array('non_members', 'users_without_plans'))) {
                    return true;
                }
            }

            // Load user
            $user = get_user_by('id', get_current_user_id());

            // Check if user was loaded
            if (!$user) {
                continue;
            }

            // Get enabled keys for current user
            $enabled_keys = WooCommerce_Membership_User::get_enabled_keys($user);

            // Check simple methods
            if (($method === 'all_members' && !empty($enabled_keys)) || ($method === 'non_members' && empty($enabled_keys))) {
                return true;
            }

            // Check plan methods
            if (in_array($method, array('members_with_plans', 'users_without_plans'))) {

                // Iterate plan keys and check if at least one membership plan matches
                $match_found = false;

                foreach ($url_restriction_rule['plans'] as $plan_key) {
                    if (in_array($plan_key, $enabled_keys)) {
                        $match_found = true;
                        break;
                    }
                }

                // Allow access based on matches
                if ($method == 'members_with_plans' && $match_found) {
                    return true;
                }
                else if ($method == 'users_without_plans' && !$match_found) {
                    return true;
                }
            }
        }

        // Visitor profile does not match any URL restriction rule
        return !$url_matched;
    }

    /**
     * Perform blocking of the requested URL
     *
     * @access public
     * @return bool
     */
    public function perform_requested_url_block()
    {
        // First check if it needs blocking
        if (self::url_access_allowed()) {
            return;
        }

        // If we have redirect URL, use it
        if (self::redirect_restricted_posts()) {
            $url = self::get_redirect_url();
        }
        // Or use main page instead
        else {
            $url = get_bloginfo('url');
        }

        // Redirect user
        wp_redirect($url);
        exit();
    }

    /**
     * Fetch only those posts that user has access to
     *
     * @access public
     * @param string $where
     * @return string
     */
    public function expand_posts_where($where)
    {
        if (isset($GLOBALS['rpwcm_getting_enabled_keys']) && $GLOBALS['rpwcm_getting_enabled_keys']) {
            return $where;
        }

        global $wpdb;

        if (self::skip_admin()) {
            return $where;
        }

        // Don't filter out posts if redirect is active
        if (is_singular() && self::redirect_restricted_posts()) {
            return $where;
        }

        // Do not hide products when we only need to restrict the add to cart action
        // WC31: Products will no longer be posts
        if (strstr($where, "post_type = 'product'") && WooCommerce_Membership_Product::show_restricted_product()) {
            return $where;
        }

        // Get enabled plan keys of user
        $capabilities = WooCommerce_Membership_User::get_enabled_keys();

        // Hold pieces of query string
        $pieces = array();

        // No method set
        $pieces[] = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE ID NOT IN (SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_post_restriction_method\')';

        // Method: None
        $pieces[] = 'SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_post_restriction_method\' AND ' . $wpdb->postmeta . '.meta_value = \'none\'';

        // Method: All Members
        if (!empty($capabilities)) {
            $pieces[] = 'SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_post_restriction_method\' AND ' . $wpdb->postmeta . '.meta_value = \'all_members\'';
        }

        // Method: Members With Plans
        if (!empty($capabilities)) {
            $inner = '(SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_post_restriction_method\' AND ' . $wpdb->postmeta . '.meta_value = \'members_with_plans\')';
            $pieces[] = sprintf('SELECT post_id AS ID FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_only_caps\' AND ' . $wpdb->postmeta . '.meta_value IN (\'%s\') AND ' . $wpdb->postmeta . '.post_id IN ' . $inner, implode('\',\'', $capabilities));
        }

        // Method: Non Members
        if (empty($capabilities)) {
            $pieces[] = 'SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_post_restriction_method\' AND ' . $wpdb->postmeta . '.meta_value = \'non_members\'';
        }

        // Method: Users Without Plans
        if (empty($capabilities)) {
            $pieces[] = 'SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_post_restriction_method\' AND ' . $wpdb->postmeta . '.meta_value = \'users_without_plans\'';
        }
        else {
            $inner = '(SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_post_restriction_method\' AND ' . $wpdb->postmeta . '.meta_value = \'users_without_plans\')';
            $pieces[] = sprintf('SELECT post_id AS ID FROM ' . $wpdb->postmeta . ' WHERE ' . $wpdb->postmeta . '.meta_key = \'_rpwcm_only_caps\' AND ' . $wpdb->postmeta . '.meta_value NOT IN (\'%s\') AND ' . $wpdb->postmeta . '.post_id IN ' . $inner, implode('\',\'', $capabilities));
        }

        // Combine all pieces
        $where .= ' AND ' . $wpdb->posts . '.ID IN (' . join(' UNION ', $pieces) . ')';

        return $where;
    }

    /**
     * Shortcode to display content to members only
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @param bool $non_members
     * @return string
     */
    public function shortcode_members_only($atts, $content = '')
    {
        return self::shortcode_members($atts, $content, true);
    }

    /**
     * Shortcode to display content to non-members only
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode_non_members_only($atts, $content = '')
    {
        return self::shortcode_members($atts, $content, false);
    }

    /**
     * Shortcode logic for both member and non-member shortcodes
     * This function is also used by woocommerce_members_only() and woocommerce_non_members_only() functions
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @param bool $members
     * @param bool $is_function
     * @return string
     */
    public static function shortcode_members($atts, $content, $members, $is_function = false)
    {
        // Get plans from attributes
        if ($is_function) {
            $keys = $atts;
        }
        else {
            $atts = shortcode_atts(array('key' => '', 'keys' => ''), $atts);
            $keys = !empty($atts['keys']) ? array_map('trim', explode(',', $atts['keys'])) : array(trim($atts['key']));
            $keys = array_filter($keys, 'strlen');
        }

        $shortcode = $members ? 'woocommerce_non_members_only' : 'woocommerce_members_only';

        // Shortcode placed but no plan keys set? Get all plan keys (i.e. accept any member or non-member)
        if (empty($keys)) {
            $keys = array_keys(WooCommerce_Membership_Plan::get_list_of_all_plan_keys());
        }

        // Get enabled plan keys of user
        $capabilities = WooCommerce_Membership_User::get_enabled_keys();

        // Check if user has any of the defined membership plan keys (capabilities) set
        $display = array_intersect($keys, $capabilities) ? true : false;

        // Inverse for non-members
        $display = $members ? $display : !$display;

        // Display to admins by default in any way
        $display = self::skip_admin() ? true : $display;

        // Allow developers to override
        $display = apply_filters('woocommerce_membership_display_shortcode_content', $display, $shortcode, $keys, $content);

        return $display ? do_shortcode($content) : '';
    }

    /**
     * Shortcode to display all closed content to member of a plan
     *
     * @access public
     * @return string
     */
    public static function shortcode_members_only_content_list()
    {
        // Only logged in is allowed
        if (!is_user_logged_in()) {
            return false;
        }

        // Get enabled plan keys of user
        $enabled_keys = WooCommerce_Membership_User::get_enabled_keys();

        // Shorten the methods list if user has no plans
        if (empty($enabled_keys)) {
            $methods = array('non_members', 'users_without_plans');
        }
        // Otherwise only 'non_members' is unneeded
        else {
            $methods = array('all_members', 'members_with_plans', 'users_without_plans');
        }

        // Query all restricted posts of any types
        $query = new WP_Query(array(
            'post_type'         => 'any',
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'meta_query'        => array(
                array(
                    'key'       => '_rpwcm_post_restriction_method',
                    'value'     => $methods,
                    'compare'   => 'IN',
                ),
            ),
        ));

        $result = '<ul class="rpwcm_members_content_list">';

        // Iterate through found posts and check user access
        foreach ($query->posts as $id) {

            if (self::user_has_access_to_post($id)) {
                $result .= '<li><a href="' . get_permalink($id) . '">' . get_the_title($id) . '</a></li>';
            }
        }

        $result .= '</ul>';

        return $result;
    }

    /**
     * Shortcode to display all active plans of a member
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode_show_active_plans($atts = '', $content = '')
    {
        return self::shortcode_show_plans($atts, $content, true, false);
    }

    /**
     * Shortcode to display all active plans of a member with expire date
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode_show_active_plans_expire($atts = '', $content = '')
    {
        return self::shortcode_show_plans($atts, $content, true, true);
    }

    /**
     * Shortcode to display all plans of a member
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode_show_all_plans($atts = '', $content = '')
    {
        return self::shortcode_show_plans($atts, $content, false, false);
    }

    /**
     * Shortcode to display all plans of a member with expire date
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode_show_all_plans_expire($atts = '', $content = '')
    {
        return self::shortcode_show_plans($atts, $content, false, true);
    }

    /**
     * Shortcode logic to display plans of a member
     * Used by 4 shortcodes above
     *
     * @access public
     * @param array $atts
     * @return string
     */
    public static function shortcode_show_plans($atts, $content, $active_only, $show_expire)
    {
        // Only logged in is allowed
        if (!is_user_logged_in()) {
            return false;
        }

        $user_id = get_current_user_id();

        // Get enabled plan keys of user
        if ($active_only) {
            $plan_keys = WooCommerce_Membership_User::get_enabled_keys();
        }
        // Or get all plan keys
        else {
            $plan_keys = WooCommerce_Membership_User::get_all_keys();
        }

        $result = '<ul class="rpwcm_members_plans_list">';

        foreach ($plan_keys as $plan_key) {

            $plan = WooCommerce_Membership_Plan::get_by_key($plan_key);

            // Get the name of plan
            $plan_name = WooCommerce_Membership_Plan::get_plan_name($plan);

            $result .= '<li>' . $plan_name;

            // Add the date if needed
            if ($show_expire) {
                $expire_date = RightPress_WC_Legacy::customer_get_meta($user_id, '_rpwcm_' . $plan_key . '_expires', true);

                if (!empty($expire_date)) {
                    $result .= __(' — expires on: ', 'woocommerce-membership') . RightPress_Helper::get_adjusted_datetime($expire_date);
                }
            }

            $result .= '</li>';
        }

        $result .= '</ul>';

        return $result;
    }

    /**
     * Shortcode to display number of active plans of a member
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode_show_active_plans_number($atts = '', $content = '')
    {
        return self::shortcode_show_plans_number($atts, $content, true);
    }

    /**
     * Shortcode to display number of all plans of a member
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode_show_all_plans_number($atts = '', $content = '')
    {
        return self::shortcode_show_plans_number($atts, $content, false);
    }

    /**
     * Shortcode logic to display the number of member's plans
     * Used by 2 shortcodes above
     *
     * @access public
     * @param array $atts
     * @return string
     */
    public static function shortcode_show_plans_number($atts, $content, $active_only)
    {
        // Only logged in is allowed
        if (!is_user_logged_in()) {
            return false;
        }

        // Get enabled plan keys of user
        if ($active_only) {
            $plan_keys = WooCommerce_Membership_User::get_enabled_keys();
        }
        // Or get all plan keys
        else {
            $plan_keys = WooCommerce_Membership_User::get_all_keys();
        }

        return count($plan_keys);
    }

    /**
     * Shortcode to show the time left to expire for a plan
     *
     * @access public
     * @param array $atts
     * @return string
     */
    public static function shortcode_plan_expire_left($atts, $content = '')
    {
        // Only logged in is allowed
        if (!is_user_logged_in()) {
            return false;
        }

        $user_id = get_current_user_id();

        $shortcode_atts = shortcode_atts(array(
            'plan' => '',
            'key'  => '',
        ), $atts);

        // If no plan key is specified
        if (empty($shortcode_atts['key']) && empty($shortcode_atts['plan'])) {
            return false;
        }

        $plan_key = !empty($shortcode_atts['plan']) ? $shortcode_atts['plan'] : $shortcode_atts['key'];

        // Get the plan
        $plan = WooCommerce_Membership_Plan::get_by_key($plan_key);

        // Get expire date
        $expire_date = RightPress_WC_Legacy::customer_get_meta($user_id, '_rpwcm_' . $plan_key . '_expires', true);

        // If no plan or expire date exists
        if (!$plan || empty($expire_date)) {
            return false;
        }

        // Get the name of plan
        $plan_name = WooCommerce_Membership_Plan::get_plan_name($plan);

        if ($expire_date > time()) {
            $time_left = $expire_date - time();
        }
        else {
            return '"' . $plan_name . '"' . __(' is already expired.', 'woocommerce-membership');
        }

        // Days left
        if ($time_left > 86400) {
            $amount = number_format(($time_left / 86400), 2) . __(' days.', 'woocommerce-membership');
        }
        // Hours
        else if ($time_left > 3600) {
            $amount = number_format(($time_left / 3600), 2) . __(' hours.', 'woocommerce-membership');
        }
        // Minutes
        else {
            $amount = number_format(($time_left / 60), 2) . __(' minutes.', 'woocommerce-membership');
        }

        return '"' . $plan_name . '"' . __(' plan expires in ', 'woocommerce-membership') . $amount;
    }

    /**
     * Add membership column to post lists
     *
     * @access public
     * @param array $columns
     * @return array
     */
    public function add_membership_column($columns)
    {
        global $post_type;

        // WC31: Orders, products etc will no longer be posts
        if (!in_array($post_type, array('membership_plan', 'shop_order', 'shop_coupon', 'product_variation'))) {
            if (!apply_filters('woocommerce_membership_skip_post_type', false, $post_type)) {

                // WC31: Products will no longer be posts
                $data_tip = $post_type == 'product' ? 'data-tip="' . __('Members-only', 'woocommerce-membership') . '"' : '';

                $columns = array_merge($columns, array(
                    'rpwcm_membership' => '<span class="rpwcm_post_list_header_icon tips" ' . $data_tip . ' title="' . __('Members-only', 'woocommerce-membership') . '">' . __('Members-Only Content', 'woocommerce-membership') . '</span>',
                ));
            }
        }

        return $columns;
    }

    /**
     * Manage list column values
     *
     * @access public
     * @param array $column
     * @param int $post_id
     * @return void
     */
    public function add_membership_column_value($column, $post_id)
    {
        if ($column == 'rpwcm_membership') {
            $method = get_post_meta($post_id, '_rpwcm_post_restriction_method', true);

            if (!empty($method) && in_array($method, array('all_members', 'members_with_plans', 'non_members', 'users_without_plans'))) {

                switch ($method) {
                    case 'all_members':
                        $message = __('Access is restricted to members only', 'woocommerce-membership');
                        break;
                    case 'members_with_plans':
                        $message = __('Access is restricted to members with specific plans only', 'woocommerce-membership');
                        break;
                    case 'non_members':
                        $message = __('Access is restricted to non-members only', 'woocommerce-membership');
                        break;
                    case 'users_without_plans':
                        $message = __('Access is restricted to users without specific membership plans only', 'woocommerce-membership');
                        break;
                    default:
                        break;
                }

                global $post_type;
                // WC31: Products will no longer be posts
                $data_tip = $post_type == 'product' ? 'data-tip="' . $message . '"' : '';

                echo '<i class="fa fa-lock rpwcm_post_list_icon tips" ' . $data_tip . ' title="' . $message . '"></i>';
            }
        }
    }

    /**
     * Bulk edit
     *
     * @access public
     * @param string $column
     * @param string $post_type
     * @return void
     */
    public function bulk_edit($column, $post_type) {
        if ($column == 'rpwcm_membership') {
            // WC31: Orders, products etc will no longer be posts
            if (!in_array($post_type, array('membership_plan', 'shop_order', 'shop_coupon', 'product_variation'))) {
                if (!apply_filters('woocommerce_membership_skip_post_type', false, $post_type)) {
                    include RPWCM_PLUGIN_PATH . 'includes/views/backend/post/bulk-edit.php';
                }
            }
        }
    }

    /**
     * Quick edit
     *
     * @access public
     * @param string $column
     * @param string $post_type
     * @return void
     */
    public function quick_edit($column, $post_type) {
        if ($column == 'rpwcm_membership') {
            // WC31: Orders, products etc will no longer be posts
            if (!in_array($post_type, array('membership_plan', 'shop_order', 'shop_coupon', 'product_variation'))) {
                if (!apply_filters('woocommerce_membership_skip_post_type', false, $post_type)) {
                    include RPWCM_PLUGIN_PATH . 'includes/views/backend/post/quick-edit.php';
                }
            }
        }
    }

}

new WooCommerce_Membership_Post();

}
