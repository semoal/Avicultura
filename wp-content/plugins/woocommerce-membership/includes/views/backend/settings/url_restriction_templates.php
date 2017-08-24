<?php

/**
 * View for URL Restriction templates
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="rpwcm_url_restriction_templates" style="display: none">

    <!-- NOTHING TO DISPLAY -->
    <div id="rpwcm_url_restriction_template_no_rules">
        <div id="rpwcm_url_restriction_no_rules"><?php _e('No URL restriction rules configured.', 'woocommerce-membership'); ?></div>
    </div>

    <!-- RULE WRAPPER -->
    <div id="rpwcm_url_restriction_template_rule_wrapper">
        <div id="rpwcm_url_restriction_rule_wrapper"></div>
    </div>

    <!-- RULE -->
    <div id="rpwcm_url_restriction_template_rule">
        <div class="rpwcm_url_restriction_rule">

            <div class="rpwcm_url_restriction_rule_fields">

                <!-- URL -->
                <div class="rpwcm_url_restriction_field_url_wrapper">
                    <input type="text" id="rpwcm_block_urls_url_{i}" name="rpwcm_options[rpwcm_block_urls][{i}][url]" value="" class="rpwcm_url_restriction_field_url" placeholder="<?php _e('Full or partial URL (wildcards * supported)', 'woocommerce-membership'); ?>" />
                </div>

                <!-- Method -->
                <div class="rpwcm_url_restriction_field_method_wrapper">
                    <select id="rpwcm_block_urls_method_{i}" name="rpwcm_options[rpwcm_block_urls][{i}][method]" class="rpwcm_url_restriction_field_method">
                        <optgroup label="<?php _e('Allow Access to Members Only', 'woocommerce-membership'); ?>">
                            <option value="all_members"><?php _e('All Members', 'woocommerce-membership'); ?></option>
                            <option value="members_with_plans"><?php _e('Members With Specific Plans', 'woocommerce-membership'); ?></option>
                        </optgroup>
                        <optgroup label="<?php _e('Allow Access to Non-Members Only', 'woocommerce-membership'); ?>">
                            <option value="non_members"><?php _e('All Non-Members', 'woocommerce-membership'); ?></option>
                            <option value="users_without_plans"><?php _e('Users Without Specific Plans', 'woocommerce-membership'); ?></option>
                        </optgroup>
                    </select>
                </div>

                <!-- Plans -->
                <div class="rpwcm_url_restriction_field_plans_wrapper">
                    <select multiple id="rpwcm_block_urls_plans_{i}" name="rpwcm_options[rpwcm_block_urls][{i}][plans][]" class="rpwcm_url_restriction_field_plans">
                        <?php foreach (WooCommerce_Membership_Plan::get_list_of_all_plan_keys() as $key => $title): ?>
                            <option value="<?php echo $key; ?>"><?php echo $title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="clear: both;"></div>

            </div>

            <!-- Remove -->
            <div class="rpwcm_url_restriction_remove">
                <div class="rpwcm_url_restriction_remove_handle">
                    <i class="fa fa-times"></i>
                </div>
            </div>
        </div>
    </div>

</div>
