<?php

/**
 * View for Settings page
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="rpwcm_settings">
    <div class="rpwcm_settings_container">
        <input type="hidden" name="current_tab" value="<?php echo $current_tab; ?>" />
        <?php settings_fields('rpwcm_opt_group_' . preg_replace('/-/', '_', $current_tab)); ?>
        <?php do_settings_sections('rpwcm-admin-' . $current_tab); ?>
        <div></div>
        <?php submit_button(); ?>
    </div>
</div>
