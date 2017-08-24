<?php

/*
 * Add shortcodes to Visual Composer
 */

if (class_exists('WPBakeryShortCode')) {


    /*
     * Members/Non-Members Only Content
     */

    $vc_members_only = array(
        'name'        => __('Members Content', 'woocommerce-membership'),
        'description' => __('Members only content.', 'woocommerce-membership'),
        'base'        => 'woocommerce_members_only',
        'icon'        => RPWCM_PLUGIN_URL . '/assets/images/rpwcm-vc-icon.png',
        'category'    => __('Content', 'woocommerce-membership'),
        'params'      => array(
            array(
                'type'        => 'textfield',
                'holder'      => 'div',
                'class'       => '',
                'heading'     => __('Plan Key', 'woocommerce-membership'),
                'param_name'  => 'key',
                'value'       => '',
                'description' => __('Enter one plan key', 'woocommerce-membership')
            ),
            array(
                'type'        => 'textfield',
                'holder'      => 'div',
                'class'       => '',
                'heading'     => __('Multiple Plan Keys', 'woocommerce-membership'),
                'param_name'  => 'keys',
                'value'       => '',
                'description' => __('Enter multiple keys, separated by comma.', 'woocommerce-membership')
            ),
            array(
                'type'        => 'textarea_html',
                'holder'      => 'div',
                'class'       => '',
                'heading'     => __('Content', 'woocommerce-membership'),
                'param_name'  => 'content',
                'value'       => __('<p>Special content placeholder.</p>', 'woocommerce-membership'),
                'description' => __('Content enclosed in the shortcode.', 'woocommerce-membership'),
            ),
        ),
    );

    $vc_non_members_only = array_merge(
        $vc_members_only,
        array(
            'name'        => __('Non-Members Content', 'woocommerce-membership'),
            'base'        => 'woocommerce_non_members_only',
            'description' => __('Non-Members only content.', 'woocommerce-membership'),
        )
    );

    vc_map($vc_members_only);
    vc_map($vc_non_members_only);


    /*
     * Member's Content List
     */

    $vc_members_content_list = array(
        'name'        => __('Member\'s Content List', 'woocommerce-membership'),
        'base'        => 'woocommerce_members_content_list',
        'icon'        => RPWCM_PLUGIN_URL . '/assets/images/rpwcm-vc-icon.png',
        'description' => __('Members-only allowed content list.', 'woocommerce-membership'),
        'category'    => __('Content', 'woocommerce-membership'),
        'params'      => array(),
        'show_settings_on_create' => false,
    );

    vc_map($vc_members_content_list);


    /*
     * Member's Active/All Plans
     */

    $vc_member_active_plans = array(
        'name'        => __('Active Plans', 'woocommerce-membership'),
        'base'        => 'woocommerce_member_active_plans',
        'icon'        => RPWCM_PLUGIN_URL . '/assets/images/rpwcm-vc-icon.png',
        'description' => __('Member\'s Active Plans list.', 'woocommerce-membership'),
        'category'    => __('Content', 'woocommerce-membership'),
        'params'      => array(),
        'show_settings_on_create' => false,
    );

    $vc_member_all_plans = array_merge(
        $vc_member_active_plans,
        array(
            'name'        => __('All Plans', 'woocommerce-membership'),
            'base'        => 'woocommerce_member_all_plans',
            'description' => __('Member\'s All Plans list.', 'woocommerce-membership'),
        )
    );

    vc_map($vc_member_active_plans);
    vc_map($vc_member_all_plans);


    /*
     * Member's Active/All Plans with expire date
     */

    $vc_member_active_plans_expire = array(
        'name'        => __('Active Plans (+expire)', 'woocommerce-membership'),
        'base'        => 'woocommerce_member_active_plans_expire',
        'icon'        => RPWCM_PLUGIN_URL . '/assets/images/rpwcm-vc-icon.png',
        'description' => __('Member\'s Active Plans list with expire date.', 'woocommerce-membership'),
        'category'    => __('Content', 'woocommerce-membership'),
        'params'      => array(),
        'show_settings_on_create' => false,
    );

    $vc_member_all_plans_expire = array_merge(
        $vc_member_active_plans_expire,
        array(
            'name'        => __('All Plans (+expire)', 'woocommerce-membership'),
            'base'        => 'woocommerce_member_all_plans_expire',
            'description' => __('Member\'s All Plans list with expire date.', 'woocommerce-membership'),
        )
    );

    vc_map($vc_member_active_plans_expire);
    vc_map($vc_member_all_plans_expire);


    /*
     * Member's Active/All Plans number
     */

    $vc_member_active_plans_number = array(
        'name'        => __('Active Plans Number', 'woocommerce-membership'),
        'base'        => 'woocommerce_member_active_plans_number',
        'icon'        => RPWCM_PLUGIN_URL . '/assets/images/rpwcm-vc-icon.png',
        'description' => __('Member\'s Active Plans number.', 'woocommerce-membership'),
        'category'    => __('Content', 'woocommerce-membership'),
        'params'      => array(),
        'show_settings_on_create' => false,
    );

    $vc_member_all_plans_number = array_merge(
        $vc_member_active_plans_number,
        array(
            'name'        => __('All Plans Number', 'woocommerce-membership'),
            'base'        => 'woocommerce_member_all_plans_number',
            'description' => __('Member\'s All Plans number.', 'woocommerce-membership'),
        )
    );

    vc_map($vc_member_active_plans_number);
    vc_map($vc_member_all_plans_number);


    /*
     * Time left before expiration of plan
     */

    $vc_plan_expire_left = array(
        'name'        => __('Plan Time Left', 'woocommerce-membership'),
        'base'        => 'woocommerce_member_plan_expire_left',
        'icon'        => RPWCM_PLUGIN_URL . '/assets/images/rpwcm-vc-icon.png',
        'category'    => __('Content', 'woocommerce-membership'),
        'description' => __('Time left before expiration of plan.', 'woocommerce-membership'),
        'params'      => array(
            array(
                'type'        => 'textfield',
                'holder'      => 'div',
                'class'       => '',
                'heading'     => __('Plan Key', 'woocommerce-membership'),
                'param_name'  => 'key',
                'value'       => '',
                'description' => __('Enter one plan key', 'woocommerce-membership')
            ),
        ),
    );

    vc_map($vc_plan_expire_left);

}
