<?php

/**
 * View for any post with meta box Restrict Access
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="rpwcm_post_membership">
    <div class="rpwcm_post_membership_field">
        <select id="_rpwcm_post_restriction_method" name="_rpwcm_post_restriction_method" class="rpwcm_post_restriction_method">
            <optgroup label="<?php _e('No Restriction', 'woocommerce-membership'); ?>">
                <option value="none" <?php echo ($method == 'none' ? 'selected="selected"' : ''); ?>><?php _e('No Restriction', 'woocommerce-membership'); ?></option>
            </optgroup>
            <optgroup label="<?php _e('Members Only', 'woocommerce-membership'); ?>">
                <option value="all_members" <?php echo ($method == 'all_members' ? 'selected="selected"' : ''); ?>><?php _e('All Members', 'woocommerce-membership'); ?></option>
                <option value="members_with_plans" <?php echo ($method == 'members_with_plans' ? 'selected="selected"' : ''); ?>><?php _e('Members With Specific Plans', 'woocommerce-membership'); ?></option>
            </optgroup>
            <optgroup label="<?php _e('Non-Members Only', 'woocommerce-membership'); ?>">
                <option value="non_members" <?php echo ($method == 'non_members' ? 'selected="selected"' : ''); ?>><?php _e('All Non-Members', 'woocommerce-membership'); ?></option>
                <option value="users_without_plans" <?php echo ($method == 'users_without_plans' ? 'selected="selected"' : ''); ?>><?php _e('Users Without Specific Plans', 'woocommerce-membership'); ?></option>
            </optgroup>
        </select>
    </div>
    <div class="rpwcm_show_if_restrict_access_by_plan">
        <div class="rpwcm_post_membership_field">
            <?php WooCommerce_Membership::render_field_multiselect(array('name' => '_rpwcm_only_caps', 'class' => 'rpwcm_only_plans', 'values' => $plans, 'selected' => $selected)); ?>
        </div>
        <p style="margin-bottom: 0;">
            <?php printf(__('Control your membership plans %shere%s.', 'woocommerce-membership'), '<a href="' . admin_url('edit.php?post_type=membership_plan') . '">', '</a>'); ?>
        </p>
    </div>
</div>
