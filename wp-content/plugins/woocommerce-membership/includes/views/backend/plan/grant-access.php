<?php

/**
 * View for WooCommerce Membership Plan Edit page Grant Access Manually box
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="rpwcm_plan_grant_access">
    <select id="rpwcm_plan_grant_access_to_user" name="rpwcm_plan_grant_access_to_user" class="select rpwcm_field_grant_access_to_user">
        <option value=""></option>
    </select>
</div>
<div class="rpwcm_plan_grant_access_footer submitbox">
    <button type="submit" class="button" title="<?php _e('Grant Access', 'woocommerce-membership'); ?>" name="rpwcm_plan_button" value="members"><?php _e('Grant Access', 'woocommerce-membership'); ?></button>
</div>
<div style="clear: both;"></div>
