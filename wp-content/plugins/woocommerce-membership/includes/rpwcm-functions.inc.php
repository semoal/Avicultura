<?php

/*
 * Global functions for this plugin
 */

if (!function_exists('woocommerce_members_only')) {

    /**
     * Display part of the template to members only
     *
     * @param array $plans
     * @return bool
     */
    function woocommerce_members_only($plans = array())
    {
        return WooCommerce_Membership_Post::shortcode_members($plans, 'function_woocommerce_members_only', true, true) ? true : false;
    }
}

if (!function_exists('woocommerce_non_members_only')) {

    /**
     * Display part of the template to non-members only
     *
     * @param array $plans
     * @return bool
     */
    function woocommerce_non_members_only($plans = array())
    {
        return WooCommerce_Membership_Post::shortcode_members($plans, 'function_woocommerce_non_members_only', false, true) ? true : false;
    }
}

if (!function_exists('woocommerce_members_content_list')) {

    /**
     * Display list of allowed posts for current user
     *
     * @return void
     */
    function woocommerce_members_content_list()
    {
        return WooCommerce_Membership_Post::shortcode_members_only_content_list();
    }
}

if (!function_exists('woocommerce_member_active_plans')) {

    /**
     * Display list of active plans for current user
     *
     * @return void
     */
    function woocommerce_member_active_plans()
    {
        return WooCommerce_Membership_Post::shortcode_show_plans('', '', true, false);
    }
}

if (!function_exists('woocommerce_member_all_plans')) {

    /**
     * Display list of all plans for current user
     *
     * @return void
     */
    function woocommerce_member_all_plans()
    {
        return WooCommerce_Membership_Post::shortcode_show_plans('', '', false, false);
    }
}

if (!function_exists('woocommerce_member_active_plans_expire')) {

    /**
     * Display list of active plans for current user with expire date
     *
     * @return void
     */
    function woocommerce_member_active_plans_expire()
    {
        return WooCommerce_Membership_Post::shortcode_show_plans('', '', true, true);
    }
}

if (!function_exists('woocommerce_member_all_plans_expire')) {

    /**
     * Display list of all plans for current user with expire date
     *
     * @return void
     */
    function woocommerce_member_all_plans_expire()
    {
        return WooCommerce_Membership_Post::shortcode_show_plans('', '', false, true);
    }
}

if (!function_exists('woocommerce_member_active_plans_number')) {

    /**
     * Display number of active plans of a member
     *
     * @return void
     */
    function woocommerce_member_active_plans_number()
    {
        return WooCommerce_Membership_Post::shortcode_show_plans_number('', '', true);
    }
}

if (!function_exists('woocommerce_member_all_plans_number')) {

    /**
     * Display number of all plans of a member
     *
     * @return void
     */
    function woocommerce_member_all_plans_number()
    {
        return WooCommerce_Membership_Post::shortcode_show_plans_number('', '', false);
    }
}

if (!function_exists('woocommerce_member_plan_expire_left')) {

    /**
     * Display the time left to expire for selected plan of current user
     *
     * @return void
     */
    function woocommerce_member_plan_expire_left($plan = array('plan' => ''))
    {
        return WooCommerce_Membership_Post::shortcode_plan_expire_left($plan);
    }
}
