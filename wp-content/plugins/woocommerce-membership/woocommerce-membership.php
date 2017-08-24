<?php

/**
 * Plugin Name: WooCommerce Membership
 * Plugin URI: http://www.rightpress.net/woocommerce-membership
 * Description: Sell online memberships with WooCommerce and create members-only areas of your website
 * Version: 2.2.2
 * Author: RightPress
 * Author URI: http://www.rightpress.net
 * Requires at least: 3.6
 * Tested up to: 4.7
 *
 * Text Domain: woocommerce-membership
 * Domain Path: /languages
 *
 * @package WooCommerce_Membership
 * @category Core
 * @author RightPress
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Constants
define('RPWCM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RPWCM_PLUGIN_URL', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
define('RPWCM_VERSION', '2.2.2');
define('RPWCM_OPTIONS_VERSION', '1');
define('RPWCM_SUPPORT_PHP', '5.3');
define('RPWCM_SUPPORT_WP', '3.6');
define('RPWCM_SUPPORT_WC', '2.3');

if (!class_exists('WooCommerce_Membership')) {

/**
 * Main plugin class
 *
 * @package WooCommerce_Membership
 * @author RightPress
 */
class WooCommerce_Membership
{
    // WARNING: ONLY CHANGE THIS IF YOU KNOW WHAT WILL HAPPEN (AND IF YOU DON'T, THERE'S NO NEED TO CHANGE THIS)
    public static $debug = false;
    // WARNING: ONLY CHANGE THIS IF YOU KNOW WHAT WILL HAPPEN (AND IF YOU DON'T, THERE'S NO NEED TO CHANGE THIS)

    // Singleton instance
    private static $instance = false;

    /**
     * Singleton control
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Class constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Load translation
        load_textdomain('woocommerce-membership', WP_LANG_DIR . '/woocommerce-membership/woocommerce-membership-' . apply_filters('plugin_locale', get_locale(), 'woocommerce-membership') . '.mo');
        load_plugin_textdomain('woocommerce-membership', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        // Some code needs to be executed after all plugins are loaded
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'));
    }

    /**
     * Executed after plugins are loaded
     *
     * @access public
     * @return void
     */
    public function on_plugins_loaded()
    {
        // Load helper class
        include_once RPWCM_PLUGIN_PATH . 'includes/classes/libraries/rightpress-helper.class.php';
        include_once RPWCM_PLUGIN_PATH . 'includes/classes/libraries/rightpress-wc-meta.class.php';
        include_once RPWCM_PLUGIN_PATH . 'includes/classes/libraries/rightpress-wc-legacy.class.php';

        // Additional Plugins page links
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugins_page_links'));

        // Check environment
        if (!self::check_environment()) {
            return;
        }

        // Load includes
        foreach (glob(RPWCM_PLUGIN_PATH . 'includes/*.inc.php') as $filename)
        {
            include $filename;
        }

        // Load classes
        foreach (glob(RPWCM_PLUGIN_PATH . 'includes/classes/*.class.php') as $filename)
        {
            include $filename;
        }

        // Initialize automatic updates
        require_once(plugin_dir_path(__FILE__) . 'includes/classes/libraries/rightpress-updates.class.php');
        RightPress_Updates_8746370::init(__FILE__, RPWCM_VERSION);

        // Initialize plugin configuration
        $this->settings = rpwcm_plugin_settings();

        // Load/parse plugin settings
        $this->opt = $this->get_options();

        // Hook to WordPress 'init' action
        add_action('init', array($this, 'on_init'), 99);

        // Admin-only hooks
        if (is_admin() && !defined('DOING_AJAX')) {

            // Add settings page menu link
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'plugin_options_setup'));

            // Check if website is duplicate
            add_action('init', array($this, 'maybe_save_main_site_url'), 1);
            add_action('admin_notices', array($this, 'url_mismatch_notification'));

            // Load backend assets conditionally
            add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_assets'));

            // ... and load some assets on all pages
            add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_assets_all'), 99);
        }

        // Other hooks
        add_action('add_meta_boxes', array($this, 'remove_meta_boxes'), 99, 2);

        // Allow shop manager to access settings
        add_filter('option_page_capability_rpwcm_opt_group_general', array($this, 'get_admin_capability'));
        add_filter('option_page_capability_rpwcm_opt_group_urls', array($this, 'get_admin_capability'));
    }

    /**
     * Add settings link on plugins page
     *
     * @access public
     * @param array $links
     * @return void
     */
    public function plugins_page_links($links)
    {
        // Support
        $settings_link = '<a href="http://url.rightpress.net/woocommerce-membership-help" target="_blank">'.__('Support', 'woocommerce-membership').'</a>';
        array_unshift($links, $settings_link);

        // Settings
        if (self::check_environment()) {
            $settings_link = '<a href="edit.php?post_type=membership_plan&page=rpwcm_settings">'.__('Settings', 'woocommerce-membership').'</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * WordPress 'init'
     *
     * @access public
     * @return void
     */
    public function on_init()
    {
        // Display granted memberships on frontend single order view page (not implemented yet)
        /*add_action(
            apply_filters('woocommerce_membership_order_view_hook', 'woocommerce_order_details_after_order_table'),
            array($this, 'display_frontend_order_granted_memberships'),
            apply_filters('woocommerce_membership_order_view_position', 9)
        );*/
    }

    /**
     * Extract some options from plugin settings array
     *
     * @access public
     * @param string $name
     * @param bool $split_by_page
     * @return array
     */
    public function options($name, $split_by_page = false)
    {
        $results = array();

        // Iterate over settings array and extract values
        foreach ($this->settings as $page => $page_value) {
            $page_options = array();

            foreach ($page_value['children'] as $section => $section_value) {
                foreach ($section_value['children'] as $field => $field_value) {
                    if (isset($field_value[$name])) {
                        $page_options['rpwcm_' . $field] = $field_value[$name];
                    }
                }
            }

            // Add default keys that are not present in plugin settings array
            if ($name == 'default' && $page == 'urls') {
                $page_options['rpwcm_block_urls'] = array();
            }

            $results[preg_replace('/_/', '-', $page)] = $page_options;
        }

        $final_results = array();

        if (!$split_by_page) {
            foreach ($results as $value) {
                $final_results = array_merge($final_results, $value);
            }
        }
        else {
            $final_results = $results;
        }

        return $final_results;
    }

    /**
     * Get options saved to database or default options if no options saved
     *
     * @access public
     * @return array
     */
    public function get_options()
    {
        // Get options from database
        $saved_options = get_option('rpwcm_options', array());

        // Get current version (for major updates in future)
        if (!empty($saved_options)) {
            if (isset($saved_options[RPWCM_OPTIONS_VERSION])) {
                $saved_options = $saved_options[RPWCM_OPTIONS_VERSION];
            }
            else {
                // Migrate options here if needed...
            }
        }

        if (is_array($saved_options)) {
            return array_merge($this->options('default'), $saved_options);
        }
        else {
            return $this->options('default');
        }
    }

    /**
     * Get single option
     *
     * @access public
     * @param string $key
     * @return mixed
     */
    public static function opt($key)
    {
        $rpwcm = self::get_instance();
        return isset($rpwcm->opt['rpwcm_' . $key]) ? $rpwcm->opt['rpwcm_' . $key] : false;
    }

    /**
     * Return option
     * Warning: do not use in WooCommerce_Membership class constructor!
     *
     * @access public
     * @param string $key
     * @return string|bool
     */
    public static function option($key)
    {
        $woocommerce_membership = WooCommerce_Membership::get_instance();
        return isset($woocommerce_membership->opt['rpwcm_' . $key]) ? $woocommerce_membership->opt['rpwcm_' . $key] : false;
    }

    /*
     * Update single option
     *
     * @access public
     * @return bool
     */
    public function update_option($key, $value)
    {
        $this->opt[$key] = $value;
        return update_option('rpwcm_options', $this->opt);
    }

    /**
     * Add admin menu items
     *
     * @access public
     * @return void
     */
    public function add_admin_menu()
    {
        // Settings
        add_submenu_page(
            'edit.php?post_type=membership_plan',
            __('Settings', 'woocommerce-membership'),
            __('Settings', 'woocommerce-membership'),
            WooCommerce_Membership::get_admin_capability('settings'),
            'rpwcm_settings',
            array($this, 'set_up_settings_pages')
        );

        // Bulk Grant Access
        add_submenu_page(
            null,
            __('Bulk Grant Access', 'woocommerce-membership'),
            __('Bulk Grant Access', 'woocommerce-membership'),
            WooCommerce_Membership::get_admin_capability('bulk_grant_access'),
            'rpwcm_bulk_grant_access',
            array($this, 'set_up_bulk_grant_access_page')
        );
    }

    /**
     * Register our settings fields with WordPress
     *
     * @access public
     * @return void
     */
    public function plugin_options_setup()
    {
        // Check if current user can manage plugin options
        if (!WooCommerce_Membership::is_authorized('settings')) {
            return;
        }

        // Iterate over tabs
        foreach ($this->settings as $tab_key => $tab) {

            register_setting(
                'rpwcm_opt_group_' . $tab_key,
                'rpwcm_options',
                array($this, 'options_validate')
            );

            // Iterate over sections
            foreach ($tab['children'] as $section_key => $section) {

                add_settings_section(
                    $section_key,
                    $section['title'],
                    array($this, 'render_section_info'),
                    'rpwcm-admin-' . str_replace('_', '-', $tab_key)
                );

                // Iterate over fields
                foreach ($section['children'] as $field_key => $field) {
                    add_settings_field(
                        'rpwcm_' . $field_key,
                        $field['title'],
                        array('WooCommerce_Membership', 'render_field_' . $field['type']),
                        'rpwcm-admin-' . str_replace('_', '-', $tab_key),
                        $section_key,
                        array(
                            'name'      => 'rpwcm_' . $field_key,
                            'options'   => $this->opt,
                            'values'    => isset($field['values']) ? $field['values'] : '',
                            'after'     => isset($field['after']) ? $field['after'] : '',
                        )
                    );
                }
            }
        }
    }

    /**
     * Render section info
     *
     * @access public
     * @param array $section
     * @return void
     */
    public function render_section_info($section)
    {
        if ($section['id'] == 'urls_restricted') {
            include RPWCM_PLUGIN_PATH . 'includes/views/backend/settings/url_restriction.php';
        }
    }

    /**
     * Render checkbox field
     *
     * @access public
     * @return void
     */
    public static function render_field_checkbox($args = array())
    {
        printf(
            '<input type="checkbox" id="%s" name="rpwcm_options[%s]" value="1" %s />%s',
            $args['name'],
            $args['name'],
            checked($args['options'][$args['name']], true, false),
            !empty($args['after']) ? '&nbsp;&nbsp;' . $args['after'] : ''
        );
    }

    /**
     * Render text field
     *
     * @access public
     * @param array $args
     * @return void
     */
    public static function render_field_text($args = array())
    {
        printf(
            '<input type="text" id="%s" name="rpwcm_options[%s]" value="%s" class="%s" />%s',
            $args['name'],
            $args['name'],
            $args['options'][$args['name']],
            'rpwcm_field_width',
            !empty($args['after']) ? '&nbsp;&nbsp;' . $args['after'] : ''
        );
    }

    /**
     * Render multiselect field
     *
     * @access public
     * @return void
     */
    public static function render_field_multiselect($args = array())
    {
        printf('<select multiple name="%s[]" id="%s" class="rpwcm_field_multiselect %s">', $args['name'], $args['name'], $args['class']);

        foreach ($args['values'] as $value_key => $value) {
            printf('<option value="%s" %s>%s</option>', $value_key, (in_array($value_key, $args['selected']) ? 'selected="selected"' : ''), $value);
        }

        echo '</select>';
    }

    /**
     * Render a dropdown
     *
     * @access public
     * @param array $args
     * @return void
     */
    public static function render_field_dropdown($args = array())
    {
        printf(
            '<select id="%s" name="rpwcm_options[%s]" class="rpwcm_field_dropdown rpwcm_field_width">',
            $args['name'],
            $args['name']
        );

        foreach ($args['values'] as $key => $name) {
            printf(
                '<option value="%s" %s>%s</option>',
                $key,
                selected($key, $args['options'][$args['name']], false),
                $name
            );
        }

        echo '</select>';
    }

    /**
     * Validate saved options
     *
     * @access public
     * @param array $input
     * @return void
     */
    public function options_validate($input)
    {
        $output = $this->opt;

        if (empty($_POST['current_tab']) || !isset($this->settings[$_POST['current_tab']])) {
            return $output;
        }

        $errors = array();

        // Validate url restriction rules
        if (!empty($input['rpwcm_block_urls']) && is_array($input['rpwcm_block_urls'])) {

            // Reset existing config in output array
            $output['rpwcm_block_urls'] = array();

            // Iterate over rules
            foreach ($input['rpwcm_block_urls'] as $rule) {

                try {

                    $current_rule = array();

                    // URL
                    if (empty($rule['url']) || !is_string($rule['url'])) {
                        throw new Exception(__('URL must be filled in.', 'woocommerce-membership'));
                    }
                    else {
                        $current_rule['url'] = $rule['url'];
                    }

                    // Method
                    if (empty($rule['method']) || !in_array($rule['method'], array('all_members', 'members_with_plans', 'non_members', 'users_without_plans'))) {
                        throw new Exception(__('Method must be selected.', 'woocommerce-membership'));
                    }
                    else {
                        $current_rule['method'] = $rule['method'];
                    }

                    // Plans
                    if (in_array($rule['method'], array('members_with_plans', 'users_without_plans'))) {
                        if (empty($rule['plans']) || !is_array($rule['plans'])) {
                            throw new Exception(__('At least one plan must be selected for methods Members With Plans and Users Without Plans.', 'woocommerce-membership'));
                        }
                        else {
                            $current_rule['plans'] = $rule['plans'];
                        }
                    }
                    else {
                        $current_rule['plans'] = array();
                    }

                    // Store this rule configuration
                    if (!empty($current_rule)) {
                        $output['rpwcm_block_urls'][] = $current_rule;
                    }

                } catch (Exception $e) {

                    // Add notice about misconfigured rule
                    add_settings_error(
                        'rpwcm',
                        'field_not_valid',
                        $e->getMessage() . ' ' . __('Misconfigured rules were discarded.', 'woocommerce-membership')
                    );
                }
            }
        }
        else if ($_POST['current_tab'] === 'urls') {
            $output['rpwcm_block_urls'] = array();
        }

        // Iterate over fields and validate new values
        foreach ($this->settings[$_POST['current_tab']]['children'] as $section_key => $section) {
            foreach ($section['children'] as $field_key => $field) {

                $current_field_key = 'rpwcm_' . $field_key;

                switch($field['validation']['rule']) {

                    // Checkbox
                    case 'bool':
                        $input[$current_field_key] = (!isset($input[$current_field_key]) || $input[$current_field_key] == '') ? '0' : $input[$current_field_key];
                        if (in_array($input[$current_field_key], array('0', '1')) || ($input[$current_field_key] == '' && $field['validation']['empty'] == true)) {
                            $output[$current_field_key] = $input[$current_field_key];
                        }
                        else {
                            array_push($errors, array('setting' => $current_field_key, 'code' => 'bool', 'title' => $field['title']));
                        }
                        break;

                    // Number
                    case 'number':
                        if (is_numeric($input[$current_field_key]) || ($input[$current_field_key] == '' && $field['validation']['empty'] == true)) {
                            $output[$current_field_key] = $input[$current_field_key];
                        }
                        else if ($current_field_key == 'rpwcm_reminders_days') {
                            $reminder_days = explode(',', trim($input[$current_field_key], ','));

                            $is_ok = true;

                            foreach ($reminder_days as $reminder_day) {
                                if (!is_numeric($reminder_day)) {
                                    $is_ok = false;
                                    break;
                                }
                            }

                            if ($is_ok) {
                                $output[$current_field_key] = trim($input[$current_field_key], ',');
                            }
                            else {
                                array_push($errors, array('setting' => $current_field_key, 'code' => 'number', 'title' => $field['title']));
                            }
                        }
                        else {
                            array_push($errors, array('setting' => $current_field_key, 'code' => 'number', 'title' => $field['title']));
                        }
                        break;

                    // Option
                    case 'option':
                        if (isset($input[$current_field_key]) && (isset($field['values'][$input[$current_field_key]]) || ($input[$current_field_key] == '' && $field['validation']['empty'] == true))) {
                            $output[$current_field_key] = $input[$current_field_key];
                        }
                        else if (!isset($input[$current_field_key])) {
                            $output[$current_field_key] = '';
                        }
                        else {
                            array_push($errors, array('setting' => $current_field_key, 'code' => 'option', 'title' => $field['title']));
                        }
                        break;

                    // Validate URLs
                    case 'url':
                        // FILTER_VALIDATE_URL for filter_var() does not work as expected
                        if (isset($input[$current_field_key]) && ($input[$current_field_key] == '' && $field['validation']['empty'] != true)) {
                            array_push($errors, array('setting' => $current_field_key, 'code' => 'url'));
                        }
                        else if (!isset($input[$current_field_key])) {
                            $output[$current_field_key] = '';
                        }
                        else {
                            $output[$current_field_key] = esc_attr(trim($input[$current_field_key]));
                        }
                        break;

                    // Text input
                    default:
                        if (isset($input[$current_field_key]) && ($input[$current_field_key] == '' && $field['validation']['empty'] != true)) {
                            array_push($errors, array('setting' => $current_field_key, 'code' => 'string'));
                        }
                        else if (!isset($input[$current_field_key])) {
                            $output[$current_field_key] = '';
                        }
                        else {
                            $output[$current_field_key] = esc_attr(trim($input[$current_field_key]));
                        }
                        break;
                }
            }
        }

        // Display settings updated message
        add_settings_error(
            'rpwcm',
            'rpwcm_' . 'settings_updated',
            __('Your settings have been saved.', 'woocommerce-membership'),
            'updated'
        );

        // Display errors
        foreach ($errors as $error) {
            $reverted = __('Reverted to a previous value.', 'woocommerce-membership');

            $messages = array(
                'number' => __('must be numeric', 'woocommerce-membership') . '. ' . $reverted,
                'bool' => __('must be either 0 or 1', 'woocommerce-membership') . '. ' . $reverted,
                'option' => __('is not allowed', 'woocommerce-membership') . '. ' . $reverted,
                'email' => __('is not a valid email address', 'woocommerce-membership') . '. ' . $reverted,
                'url' => __('is not a valid URL', 'woocommerce-membership') . '. ' . $reverted,
                'string' => __('is not a valid text string', 'woocommerce-membership') . '. ' . $reverted,
            );

            add_settings_error(
                'rpwcm',
                $error['code'],
                __('Value of', 'woocommerce-membership') . ' "' . $error['title'] . '" ' . $messages[$error['code']]
            );
        }

        return $output;
    }

    /**
     * Set up settings pages
     *
     * @access public
     * @return void
     */
    public function set_up_settings_pages()
    {
        // Get current page & tab ids
        $current_tab = $this->get_current_settings_tab();

        // Open form container
        echo '<div class="wrap woocommerce"><form method="post" action="options.php" enctype="multipart/form-data">';

        // Print notices
        settings_errors('rpwcm');

        // Print header
        include RPWCM_PLUGIN_PATH . 'includes/views/backend/settings/header.php';

        // Print settings page content
        include RPWCM_PLUGIN_PATH . 'includes/views/backend/settings/fields.php';

        // Print footer
        include RPWCM_PLUGIN_PATH . 'includes/views/backend/settings/footer.php';

        // Close form container
        echo '</form></div>';

        // Print templates
        if ($current_tab == 'urls') {
            include RPWCM_PLUGIN_PATH . 'includes/views/backend/settings/url_restriction_templates.php';
        }
    }

    /**
     * Set up bulk grant access page
     *
     * @access public
     * @return void
     */
    public function set_up_bulk_grant_access_page()
    {
        // Get return url
        $return_url = !empty($_GET['return_url']) ? $_GET['return_url'] : admin_url('users.php');

        // Get user ids
        $user_ids = !empty($_GET['user_ids']) ? $_GET['user_ids'] : array();

        // No user ids?
        if (empty($user_ids)) {
            exit;
        }

        // Include view
        include RPWCM_PLUGIN_PATH . 'includes/views/backend/users/bulk_grant_access.php';
    }

    /**
     * Get current settings tab
     *
     * @access public
     * @return string
     */
    public function get_current_settings_tab()
    {
        // Check if we know tab identifier
        if (isset($_GET['tab']) && isset($this->settings[$_GET['tab']])) {
            $tab = $_GET['tab'];
        }
        else {
            $keys = array_keys($this->settings);
            $tab = array_shift($keys);
        }

        return $tab;
    }

    /**
     * Load backend assets conditionally
     *
     * @access public
     * @return bool
     */
    public function enqueue_backend_assets()
    {
        // Check if we are on our own page
        global $typenow;

        if ($typenow != 'membership_plan') {
            return;
        }

        // Our own scripts and styles
        wp_register_script('rpwcm-backend-scripts', RPWCM_PLUGIN_URL . '/assets/js/backend.js', array('jquery'), RPWCM_VERSION);
        wp_register_style('rpwcm-backend-styles', RPWCM_PLUGIN_URL . '/assets/css/backend.css', array(), RPWCM_VERSION);

        // Pass variables
        wp_localize_script('rpwcm-backend-scripts', 'rpwcm_backend_vars', array(
            'never_text'    => __('Never', 'woocommerce-membership'),
            'close_text'    => __('Close', 'woocommerce-membership'),
            'block_urls'    => self::option('block_urls'),
        ));

        // Scripts
        wp_enqueue_script('rpwcm-backend-scripts');

        // Styles
        wp_enqueue_style('rpwcm-backend-styles');

        // Datepicker
        wp_enqueue_script('jquery-ui-datepicker');

        // Datepicker styles
        wp_register_style('rpwcm-jquery-ui', RPWCM_PLUGIN_URL . '/assets/css/jquery-ui.css', array(), '1.10.3');
        wp_enqueue_style('rpwcm-jquery-ui');
    }

    /**
     * Load backend assets on all pages
     *
     * @access public
     * @return bool
     */
    public function enqueue_backend_assets_all()
    {
        // Our own scripts and styles
        wp_register_style('rpwcm-backend-styles-all', RPWCM_PLUGIN_URL . '/assets/css/backend-all.css', array(), RPWCM_VERSION);
        wp_register_script('rpwcm-backend-scripts-all', RPWCM_PLUGIN_URL . '/assets/js/backend-all.js', array('jquery'), RPWCM_VERSION);

        // Font awesome (icons)
        wp_register_style('rpwcm-font-awesome', RPWCM_PLUGIN_URL . '/assets/font-awesome/css/font-awesome.min.css', array(), '4.1');

        // Pass variables to Javascript
        $localize = array(
            'empty_plan_title'                  => __('name not set', 'woocommerce-membership'),
            'empty_plan_key'                    => __('key not set', 'woocommerce-membership'),
            'title_membership_product'          => __('Membership product', 'woocommerce-membership'),
            'title_plans_placeholder'           => __('Select Plans', 'woocommerce-membership'),
            'title_plans_placeholder_single'    => __('Select Plan', 'woocommerce-membership'),
            'title_users_placeholder'           => __('Select User', 'woocommerce-membership'),
        );

        global $typenow;
        global $post;

        if ($typenow == 'membership_plan' && $post && isset($post->ID)) {
            $plan = WooCommerce_Membership_Plan::cache($post->ID);

            if ($plan) {
                $localize['membership_plan_exists'] = !empty($plan->key) ? 1 : 0;
            }
        }

        wp_localize_script('rpwcm-backend-scripts-all', 'rpwcm_vars', $localize);

        // Scripts
        wp_enqueue_script('rpwcm-backend-scripts-all');

        // Styles
        wp_enqueue_style('rpwcm-backend-styles-all');
        wp_enqueue_style('rpwcm-font-awesome');

        // Disable auto-save
        global $typenow;
        if ($typenow == 'membership_plan') {
            wp_dequeue_script('autosave');
        }

        // Select2
        $this->enqueue_select2();
    }

    /**
     * Enqueue Select2
     *
     * @access public
     * @return void
     */
    public function enqueue_select2()
    {
        // Load conditionally
        $screen = get_current_screen();

        if (!in_array($screen->base, array('post', 'membership_plan_page_rpwcm_settings'))) {
            return;
        }

        // Select2
        wp_enqueue_script('rpwcm-select2-scripts', RPWCM_PLUGIN_URL . '/assets/select2/js/select2.min.js', array('jquery'), '4.0.3');
        wp_enqueue_script('rpwcm-select2-rp', RPWCM_PLUGIN_URL . '/assets/js/rp-select2.js', array(), RPWCM_VERSION);
        wp_enqueue_style('rpwcm-select2-styles', RPWCM_PLUGIN_URL . '/assets/select2/css/select2.min.css', array(), '4.0.3');

        // Print scripts before WordPress takes care of it automatically (helps load our version of Select2 before any other plugin does it)
        add_action('wp_print_scripts', array($this, 'print_select2'));
    }

    /**
     * Print Select2 scripts
     *
     * @access public
     * @return void
     */
    public function print_select2()
    {
        remove_action('wp_print_scripts', array($this, 'print_select2'));
        wp_print_scripts('rpwcm-select2-scripts');
        wp_print_scripts('rpwcm-select2-rp');
    }

    /**
     * Remove meta boxes from own pages
     *
     * @access public
     * @param string $post_type
     * @param object $post
     * @return void
     */
    public function remove_meta_boxes($post_type, $post)
    {
        // Remove third party metaboxes from own pages
        if ($post_type == 'membership_plan') {
            $meta_boxes_to_leave = apply_filters('woocommerce_membership_third_party_meta_boxes_to_leave', array());

            foreach (self::get_meta_boxes() as $context => $meta_boxes_by_context) {
                foreach ($meta_boxes_by_context as $subcontext => $meta_boxes_by_subcontext) {
                    foreach ($meta_boxes_by_subcontext as $meta_box_id => $meta_box) {
                        if (!in_array($meta_box_id, $meta_boxes_to_leave)) {
                            remove_meta_box($meta_box_id, $post_type, $context);
                        }
                    }
                }
            }
        }
    }

    /**
     * Get list of meta boxes for current screent
     *
     * @access public
     * @return array
     */
    public static function get_meta_boxes()
    {
        global $wp_meta_boxes;

        $screen = get_current_screen();
        $page = $screen->id;

        return $wp_meta_boxes[$page];
    }

    /**
     * Define and return time units
     *
     * @access public
     * @return array
     */
    public static function get_time_units()
    {
        return apply_filters('woocommerce_membership_time_units', array(
            'day'   => array(
                'seconds'               => 86400,
                'translation_callback'  => array('WooCommerce_Membership', 'translate_time_unit'),
            ),
            'week'  => array(
                'seconds'               => 604800,
                'translation_callback'  => array('WooCommerce_Membership', 'translate_time_unit'),
            ),
            'month' => array(
                'seconds'               => 2592000,
                'translation_callback'  => array('WooCommerce_Membership', 'translate_time_unit'),
            ),
            'year'  => array(
                'seconds'               => 31536000,
                'translation_callback'  => array('WooCommerce_Membership', 'translate_time_unit'),
            ),
        ));
    }

    /**
     * Translate time unit (doing this way to allow developers
     * to use their own time units AND to translate them to
     * exoting languages that have complex plurals)
     *
     * @access public
     * @param string $unit
     * @param int $value
     * @return string
     */
    public static function translate_time_unit($unit, $value)
    {
        switch ($unit) {
            case 'day':
                return _n('day', 'days', $value, 'woocommerce-membership');
                break;
            case 'week':
                return _n('week', 'weeks', $value, 'woocommerce-membership');
                break;
            case 'month':
                return _n('month', 'months', $value, 'woocommerce-membership');
                break;
            case 'year':
                return _n('year', 'years', $value, 'woocommerce-membership');
                break;
            default:
                break;
        }
    }

    /**
     * Maybe display URL mismatch notification
     *
     * @access public
     * @return void
     */
    public function url_mismatch_notification()
    {
        if (!WooCommerce_Membership::is_authorized('notices')) {
            return;
        }

        $current_url = $this->get_main_site_url();

        if (!empty($_POST['rpwcm_url_mismatch_action'])) {
            if ($_POST['rpwcm_url_mismatch_action'] == 'change') {
                $this->save_main_site_url($this->get_main_site_url());
            }
            else if ($_POST['rpwcm_url_mismatch_action'] == 'ignore') {
                $this->update_option('rpwcm_ignore_url_mismatch', $current_url);
            }
        }
        else if (!self::is_main_site() && (empty($this->opt['rpwcm_ignore_url_mismatch']) || $this->opt['rpwcm_ignore_url_mismatch'] != $current_url)) {

            // Do not display notification on a demo site
            if (!RightPress_Helper::is_demo()) {
                include RPWCM_PLUGIN_PATH . 'includes/views/backend/admin/url-mismatch-notification.php';
            }
        }
    }

    /**
     * Maybe save main site URL
     *
     * @access public
     * @return void
     */
    public function maybe_save_main_site_url()
    {
        if (empty($this->opt['rpwcm_main_site_url'])) {
            $this->save_main_site_url($this->get_main_site_url());
        }
    }

    /**
     * Save main site URL so we can disable some actions on development/staging websites
     *
     * @access public
     * @param string $url
     * @return void
     */
    public function save_main_site_url($url)
    {
        $this->update_option('rpwcm_main_site_url', $url);
    }

    /**
     * Get main site URL with placeholder in the middle
     *
     * @access public
     * @return string
     */
    public function get_main_site_url()
    {
        $current_site_url = get_site_url();
        return substr_replace($current_site_url, '%%%RPWCM%%%', strlen($current_site_url) / 2, 0);
    }

    /**
     * Check if this is main site - some actions must be cancelled on development/staging websites
     *
     * @access public
     * @return bool
     */
    public static function is_main_site()
    {
        $is_main_site = false;

        $woocommerce_membership = self::get_instance();
        $current_site_url = get_site_url();

        // Make sure we have saved original URL, otherwise treat as duplicate site
        if (!empty($woocommerce_membership->opt['rpwcm_main_site_url'])) {
            $main_site_url = set_url_scheme(str_replace('%%%RPWCM%%%', '', $woocommerce_membership->opt['rpwcm_main_site_url']));
            $is_main_site = $current_site_url == apply_filters('woocommerce_membership_site_url', $main_site_url) ? true : false;
        }

        return apply_filters('woocommerce_membership_is_main_site', $is_main_site);
    }

    /**
     * Check if current user is authorized to access plugin's settings etc
     *
     * @access public
     * @param string $context
     * @return bool
     */
    public static function is_authorized($context = '')
    {
        return current_user_can(WooCommerce_Membership::get_admin_capability($context));
    }

    /**
     * Get admin capability
     *
     * @access public
     * @param string $context
     * @return string
     */
    public static function get_admin_capability($context = '')
    {
        return apply_filters('woocommerce_membership_capability', 'manage_woocommerce', $context);
    }

    /**
     * Check if environment meets requirements
     *
     * @access public
     * @return bool
     */
    public static function check_environment()
    {
        $is_ok = true;

        // Check PHP version
        if (!version_compare(PHP_VERSION, RPWCM_SUPPORT_PHP, '>=')) {

            // Add notice
            add_action('admin_notices', array('WooCommerce_Membership', 'php_version_notice'));

            // Do not proceed as RightPress Helper requires PHP 5.3 for itself
            return false;
        }

        // Check WordPress version
        if (!RightPress_Helper::wp_version_gte(RPWCM_SUPPORT_WP)) {
            add_action('admin_notices', array('WooCommerce_Membership', 'wp_version_notice'));
            $is_ok = false;
        }

        // Check if WooCommerce is enabled
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array('WooCommerce_Membership', 'wc_disabled_notice'));
            $is_ok = false;
        }
        else if (!RightPress_Helper::wc_version_gte(RPWCM_SUPPORT_WC)) {
            add_action('admin_notices', array('WooCommerce_Membership', 'wc_version_notice'));
            $is_ok = false;
        }

        return $is_ok;
    }

    /**
     * Display PHP version notice
     *
     * @access public
     * @return void
     */
    public static function php_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Membership</strong> requires PHP %s or later. Please update PHP on your server to use this plugin.', 'woocommerce-membership'), RPWCM_SUPPORT_PHP) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'woocommerce-membership'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'woocommerce-membership') . '</a>') . '</p></div>';
    }

    /**
     * Display WP version notice
     *
     * @access public
     * @return void
     */
    public static function wp_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Membership</strong> requires WordPress version %s or later. Please update WordPress to use this plugin.', 'woocommerce-membership'), RPWCM_SUPPORT_WP) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'woocommerce-membership'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'woocommerce-membership') . '</a>') . '</p></div>';
    }

    /**
     * Display WC disabled notice
     *
     * @access public
     * @return void
     */
    public static function wc_disabled_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Membership</strong> requires WooCommerce to be active. You can download WooCommerce %s.', 'woocommerce-membership'), '<a href="http://url.rightpress.net/woocommerce-download-page">' . __('here', 'woocommerce-membership') . '</a>') . ' ' . sprintf(__('If you have any questions, please contact %s.', 'woocommerce-membership'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'woocommerce-membership') . '</a>') . '</p></div>';
    }

    /**
     * Display WC version notice
     *
     * @access public
     * @return void
     */
    public static function wc_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooCommerce Membership</strong> requires WooCommerce version %s or later. Please update WooCommerce to use this plugin.', 'woocommerce-membership'), RPWCM_SUPPORT_WC) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'woocommerce-membership'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'woocommerce-membership') . '</a>') . '</p></div>';
    }


}

WooCommerce_Membership::get_instance();

}
