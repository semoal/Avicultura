<?php

/**
 * View for WooCommerce Variation Edit page Membership Plan selection field
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="show_if_rpwcm_variable">
    <div>
        <p class="form-row form-row-first">
            <label for="_rpwcm_plans"><?php _e('Membership Plans:', 'woocommerce-membership'); ?></label>
            <?php WooCommerce_Membership::render_field_multiselect(array('name' => '_rpwcm_plans[' . $loop . ']', 'class' => 'rpwcm_field_plans', 'values' => $values, 'selected' => $selected)); ?>
        </p>
        <p class="form-row form-row-last">
            <label for="_rpwcm_expiration_value"><?php _e('Membership Expires In', 'woocommerce-membership'); ?></label>
            <input type="text" class="input-text rpwcm_product_page_half_width" name="_rpwcm_expiration_value[<?php echo $loop ?>]" placeholder="<?php _e('will not expire', 'woocommerce-membership'); ?>" value="<?php echo $expiration_value; ?>">
            <select name="_rpwcm_expiration_unit[<?php echo $loop ?>]" class="select rpwcm_product_page_half_width" style="margin-left: 0.5%;">
                <?php foreach (WooCommerce_Membership::get_time_units() as $unit_key => $unit): ?>
                    <option value="<?php echo $unit_key; ?>" <?php echo $expiration_unit == $unit_key ? 'selected="selected"' : ''; ?>><?php echo call_user_func($unit['translation_callback'], $unit_key, 2); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
    </div>
</div>
