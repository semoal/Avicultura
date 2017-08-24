<?php

/**
 * View for Membership Plan Edit page Members block
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<?php if (!empty($members)): ?>

    <table class="rpwcm_membership_plan_item_list">
        <thead>
            <tr>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_name"><?php _e('Member Name', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_email"><?php _e('Email Address', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_since"><?php _e('Member Since', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_expires"><?php _e('Membership Expires', 'woocommerce-membership'); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($members as $member): ?>
                <tr>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_name">
                        <?php echo WooCommerce_Membership_User::get_user_full_name_link($member->ID); ?>
                    </td>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_email">
                        <a href="mailto:<?php echo $member->user_email; ?>"><?php echo $member->user_email; ?></a>
                    </td>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_since">
                        <?php $current_member_timestamp = RightPress_WC_Legacy::customer_get_meta($member->ID, '_rpwcm_' . $plan->key . '_since', true); ?>
                        <?php echo !empty($current_member_timestamp) ? RightPress_Helper::get_adjusted_datetime($current_member_timestamp) : ''; ?>
                    </td>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_expires">

                        <?php $expiration_time = RightPress_WC_Legacy::customer_get_meta($member->ID, '_rpwcm_' . $plan->key . '_expires', true); ?>

                        <input type="hidden" name="rpwcm_date">
                        <input type="hidden" name="rpwcm_default_date" value="<?php echo ($expiration_time ? RightPress_Helper::get_adjusted_datetime($expiration_time, 'Y-m-d') : ''); ?>">
                        <input type="hidden" name="rpwcm_user_id" value="<?php echo $member->ID; ?>">
                        <input type="hidden" name="rpwcm_plan_key" value="<?php echo $plan->key; ?>">
                        <input type="hidden" name="rpwcm_plan_id" value="<?php echo $plan->id; ?>">

                        <a class="rpwcm_membership_plan_expiration_date_change_link" href="" title="<?php _e('Change expiration date', 'woocommerce-membership'); ?>">
                            <?php
                                if ($expiration_time) {
                                    echo '<span class="rpwcm_expiration_date">' . RightPress_Helper::get_adjusted_datetime($expiration_time) . '</span>';
                                }
                                else {
                                    echo '<span class="rpwcm_expiration_date rpwcm_nothing_to_display">' . __('Never', 'woocommerce-membership') . '</span>';
                                }
                            ?>
                        </a>

                        <span class="rpwcm_membership_plan_remove_item"><a href="<?php echo admin_url('?rpwcm_remove_member&plan=' . $post->ID . '&member=' . $member->ID); ?>" title="<?php _e('Remove Member Manually', 'woocommerce-membership'); ?>"><i class="fa fa-times"></i></a></span>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>
        <?php _e('No members found.', 'woocommerce-membership'); ?>
        <input type="hidden" name="rpwcm_plan_key" value="<?php echo (isset($plan->key) ? $plan->key : ''); ?>">
    </p>

<?php endif; ?>

<?php if (isset($plan->key)): ?>
    <div class="rpwcm_membership_plan_members_footer_container">

        <div class="rpwcm_membership_plan_members_footer_left">
            <?php if (!empty($members)): ?>
                <a href="<?php echo $csv_link; ?>"><?php _e('Export to CSV', 'woocommerce-membership'); ?></a>&nbsp;
            <?php endif; ?>
        </div>

        <div class="rpwcm_membership_plan_members_footer_right">
            <input type="hidden" id="rpwcm_admin_url" name="rpwcm_admin_url" value="<?php echo admin_url('post.php?post=' . $plan->id . '&action=edit&rpwcm_search='); ?>">
            <input type="search" id="rpwcm_member_search_field" name="rpwcm_search" value="<?php echo $search_query; ?>">
            <input type="button" class="button" id="rpwcm_member_search_button" value="<?php _e('Member Search', 'woocommerce-membership'); ?>">
        </div>

        <div class="rpwcm_membership_plan_members_footer_middle">
            <?php echo $pagination_title . $paginate_links; ?>
        </div>
        <div style="clear:both;"></div>
    </div>
<?php endif; ?>
