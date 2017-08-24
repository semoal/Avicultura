<?php

/**
 * View for WooCommerce Membership Plan Edit page Plan Actions block
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="rpwcm_plan_actions">
    <select name="rpwcm_plan_actions">
        <?php foreach ($actions as $action_key => $action_title): ?>
            <option value="<?php echo $action_key; ?>"><?php echo $action_title; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="rpwcm_plan_actions_footer submitbox">
    <?php if (isset($plan->key)): ?>
        <div class="rpwcm_plan_delete">
            <?php if (current_user_can('delete_post', $plan->id)): ?>
                <a class="submitdelete deletion" href="<?php echo esc_url(get_delete_post_link($plan->id)); ?>"><?php echo (!EMPTY_TRASH_DAYS ? __('Delete Permanently', 'woocommerce-membership') : __('Move to Trash', 'woocommerce-membership')); ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <button type="submit" class="button button-primary" title="<?php _e('Submit', 'woocommerce-membership'); ?>" name="rpwcm_plan_button" value="actions"><?php _e('Submit', 'woocommerce-membership'); ?></button>
</div>
<div style="clear: both;"></div>
