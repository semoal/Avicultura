<?php

/**
 * View for WooCommerce Product Edit page Membership Plan selection field
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="options_group show_if_rpwcm_simple">
    <p class="form-field _rpwcm_plans_field">
        <label for="_rpwcm_plans"><?php _e('Membership Plans', 'woocommerce-membership'); ?></label>
        <?php WooCommerce_Membership::render_field_multiselect(array(
            'name'      => '_rpwcm_plans',
            'class'     => 'rpwcm_field_plans',
            'values'    => $values,
            'selected'  => $selected,
        )); ?>
    </p>
    <p class="form-field _rpwcm_expiration_field">
        <label for="_rpwcm_expiration_value"><?php _e('Membership Expires In', 'woocommerce-membership'); ?></label>
        <input type="text" class="input-text rpwcm_product_page_half_width" id="_rpwcm_expiration_value" name="_rpwcm_expiration_value" placeholder="<?php _e('will not expire', 'woocommerce-membership'); ?>" value="<?php echo $expiration_value; ?>">
        <select id="_rpwcm_expiration_unit" name="_rpwcm_expiration_unit" class="select rpwcm_product_page_half_width" style="margin-left: 3px;">
            <?php foreach (WooCommerce_Membership::get_time_units() as $unit_key => $unit): ?>
                <option value="<?php echo $unit_key; ?>" <?php echo $expiration_unit == $unit_key ? 'selected="selected"' : ''; ?>><?php echo call_user_func($unit['translation_callback'], $unit_key, 2); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
</div>
