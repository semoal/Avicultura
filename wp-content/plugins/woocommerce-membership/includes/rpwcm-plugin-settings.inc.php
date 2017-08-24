<?php

/*
 * Returns settings for this plugin
 *
 * @return array
 */
if (!function_exists('rpwcm_plugin_settings')) {
function rpwcm_plugin_settings()
{
    return array(
        'general' => array(
            'title' => __('General', 'woocommerce-membership'),
            'icon' => '<i class="fa fa-cogs" style="font-size: 0.8em;"></i>',
            'children' => array(
                'posts_settings' => array(
                    'title' => __('General Restriction Settings', 'woocommerce-membership'),
                    'children' => array(
                        'redirect_url' => array(
                            'title' => __('Redirect URL for restricted posts', 'woocommerce-membership'),
                            'type' => 'text',
                            'default' => '',
                            'validation' => array(
                                'rule' => 'url',
                                'empty' => true
                            ),
                            'hint' => __('<p>Leave blank to show regular 404 page.</p>', 'woocommerce-membership'),
                        ),
                    ),
                ),
                'products_settings' => array(
                    'title' => __('Product Restriction Settings', 'woocommerce-membership'),
                    'children' => array(
                        'restrict_product' => array(
                            'title' => __('Product access restriction method', 'woocommerce-membership'),
                            'type' => 'dropdown',
                            'default' => 0,
                            'validation' => array(
                                'rule' => 'option',
                                'empty' => false
                            ),
                            'values' => array(
                                '0' => __('Restrict access completely', 'woocommerce-membership'),
                                '1' => __('Make product not purchasable', 'woocommerce-membership'),
                            ),
                            'hint' => __('<p>How to restrict access to products. If first option is selected, products will be treated like any other posts or pages.</p>', 'woocommerce-membership'),
                        ),
                    ),
                ),
                'reminders' => array(
                    'title' => __('Membership Expiration Reminders', 'woocommerce-membership'),
                    'children' => array(
                        'reminders_enabled' => array(
                            'title' => __('Enable membership expiration reminders', 'woocommerce-membership'),
                            'type' => 'checkbox',
                            'default' => 0,
                            'validation' => array(
                                'rule' => 'bool',
                                'empty' => false
                            ),
                            'hint' => __('<p></p>', 'woocommerce-membership'),
                        ),
                        'reminders_days' => array(
                            'title' => __('Send reminders before', 'woocommerce-membership'),
                            'after' => __('day(s) (separate values by comma)', 'woocommerce-membership'),
                            'type' => 'text',
                            'default' => '',
                            'validation' => array(
                                'rule' => 'number',
                                'empty' => true
                            ),
                            'hint' => __('<p></p>', 'woocommerce-membership'),
                        ),
                    ),
                ),
                'other' => array(
                    'title' => __('Other Settings', 'woocommerce-membership'),
                    'children' => array(
                        'admin_access' => array(
                            'title' => __('Admin can access restricted content', 'woocommerce-membership'),
                            'type' => 'checkbox',
                            'default' => 1,
                            'validation' => array(
                                'rule' => 'bool',
                                'empty' => false
                            ),
                            'hint' => __('<p></p>', 'woocommerce-membership'),
                        ),
                    ),
                ),
            ),
        ),
        'urls' => array(
            'title' => __('URL Restriction', 'woocommerce-membership'),
            'icon' => '<i class="fa fa-times-circle" style="font-size: 0.95em;"></i>',
            'children' => array(
                'urls_restricted' => array(
                    'title' => __('Restrict Access By URL', 'woocommerce-membership'),
                    'children' => array(
                    ),
                ),
            ),
        ),
    );
}
}
