<?php

/**
 * View for Membership Plan Edit page main plan details block
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<style type="text/css">
    #submitdiv {
        display: none;
    }
</style>

<div id="membership_plan_data">
    <h2>
        <?php echo '<span class="membership_plan_details_title membership_plan_details_title_' . (isset($plan->name) ? 'exists' : 'does_not_exist') . '">' . (isset($plan->name) ? $plan->name : __('name not set', 'woocommerce-membership')) . '</span>'; ?>
        <?php if (isset($plan->status)): ?>
            <span class="membership_plan_edit_page_status membership_plan_status_<?php echo $plan_statuses[$plan->status]['title']; ?>"><?php echo $plan_statuses[$plan->status]['title']; ?></span>
        <?php endif; ?>
    </h2>

    <p class="membership_plan_subheading">
        <?php echo __('Key:', 'woocommerce-membership') . ' <span class="membership_plan_details_key membership_plan_details_key_' . (isset($plan->key) ? 'exists' : 'does_not_exist') . '">' . (isset($plan->key) ? '<code>' . $plan->key . '</code>' : __('key not set', 'woocommerce-membership')) . '</span>'; ?>
    </p>
</div>
