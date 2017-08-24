<?php

/**
 * View for Membership Plan Edit page Linked Plans block
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<?php if (!empty($plan->linked_plans)): ?>

    <table class="rpwcm_membership_plan_item_list">
        <thead>
            <tr>
                <th class="rpwcm_third_width rpwcm_membership_plan_item_list_plan_name"><?php _e('Plan Name', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_third_width rpwcm_membership_plan_item_list_plan_key"><?php _e('Plan Key', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_third_width rpwcm_membership_plan_item_list_plan_time"><?php _e('Grant Access After', 'woocommerce-membership'); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($plan->linked_plans as $linked_plan_id => $linked_plan): ?>
                <tr>
                    <td class="rpwcm_third_width rpwcm_membership_plan_item_list_plan_name">
                        <?php echo $linked_plan['name']; ?>
                    </td>
                    <td class="rpwcm_third_width rpwcm_membership_plan_item_list_plan_key">
                        <?php echo '<code>' . $linked_plan['key'] . '</code>'; ?>
                    </td>
                    <td class="rpwcm_third_width rpwcm_membership_plan_item_list_plan_time">
                        <input type="text" class="input-text rpwcm_field_linked_plan_time_value" id="_rpwcm_linked_plan_time_value_<?php echo $linked_plan_id; ?>" name="_rpwcm_linked_plan_time_value[<?php echo $linked_plan_id; ?>]" placeholder="<?php _e('0', 'woocommerce-membership'); ?>" value="<?php echo $linked_plan['time_value']; ?>">
                        <select id="_rpwcm_linked_plan_time_unit_<?php echo $linked_plan_id; ?>" name="_rpwcm_linked_plan_time_unit[<?php echo $linked_plan_id; ?>]" class="select rpwcm_field_linked_plan_time_unit" style="margin-left: 3px;">
                            <?php foreach (WooCommerce_Membership::get_time_units() as $unit_key => $unit): ?>
                                <option value="<?php echo $unit_key; ?>" <?php echo $linked_plan['time_unit'] == $unit_key ? 'selected="selected"' : ''; ?>><?php echo call_user_func($unit['translation_callback'], $unit_key, 2); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <span class="rpwcm_membership_plan_remove_item"><a href="<?php echo admin_url('?rpwcm_remove_linked_plan&parent_plan=' . $post->ID . '&linked_plan=' . $linked_plan_id); ?>" title="<?php _e('Remove Linked Plan', 'woocommerce-membership'); ?>"><i class="fa fa-times"></i></a></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>
        <?php _e('No linked plans found.', 'woocommerce-membership'); ?>
    </p>

<?php endif; ?>

<?php if (isset($plan->key)): ?>
    <div class="rpwcm_membership_plan_linked_plans_footer_container">
        <div class="rpwcm_membership_plan_linked_plans_footer">
            <select id="rpwcm_plan_add_linked_plan" name="rpwcm_plan_add_linked_plan" class="select rpwcm_field_add_linked_plan" style="margin-left: 3px;">
                <option value=""></option>
                <?php foreach (WooCommerce_Membership_Plan::get_list_of_all_plans() as $plan_id => $plan_name): ?>
                    <?php if (!in_array($plan_id, array_merge(array_keys($plan->linked_plans), WooCommerce_Membership_Plan::get_parent_plans($plan->id), array($plan->id)))): ?>
                        <option value="<?php echo $plan_id; ?>"><?php echo $plan_name; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="button" title="<?php _e('Link Plan', 'woocommerce-membership'); ?>" name="rpwcm_plan_button" value="linked_plans"><?php _e('Link Plan', 'woocommerce-membership'); ?></button>
        </div>
        <div style="clear:both;"></div>
    </div>
<?php endif; ?>
