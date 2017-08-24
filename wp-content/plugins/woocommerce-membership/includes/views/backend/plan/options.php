<?php

/**
 * View for WooCommerce Membership Plan Edit page Plan Options block
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="rpwcm_plan_options">
    <div class="rpwcm_options_checkbox">
        <input type="checkbox" id="rpwcm_plan_add_new_users_automatically" name="rpwcm_plan_add_new_users_automatically" class="rpwcm_field_add_new_users_automatically" value="1" <?php checked((isset($plan->add_new_users_automatically) && $plan->add_new_users_automatically)); ?>>
        <?php _e('Add all new users automatically', 'woocommerce-membership') ?>
    </div>
</div>

<div style="clear: both;"></div>
