<?php

/**
 * View for Users page custom bulk actions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<h1 class="rpwcm_bulk_grant_access_header"><?php _e('Grant Access', 'woocommerce-membership'); ?></h1>
<form class="rpwcm_bulk_grant_access" method="post">
    <div class="rpwcm_bulk_grant_access_row">
        <?php _e('This will grant membership access to the following users:', 'woocommerce-membership'); ?> <?php echo implode(', ', WooCommerce_Membership_User::get_list_of_users_by_ids($user_ids)); ?>.
    </div>
    <div class="rpwcm_bulk_grant_access_row">
        <?php WooCommerce_Membership_Form_Builder::select(array(
            'id'        => 'rpwcm_bulk_grant_access_plan',
            'name'      => 'rpwcm_bulk_grant_access_plan',
            'class'     => 'rpwcm_bulk_grant_access_plan',
            'label'     => __('Membership Plan', 'woocommerce-membership'),
            'options'   => WooCommerce_Membership_Plan::get_list_of_all_plans(),
        )); ?>
    </div>
    <div class="rpwcm_bulk_grant_access_row">
        <?php WooCommerce_Membership_Form_Builder::text(array(
            'id'            => 'rpwcm_bulk_grant_access_expiration',
            'name'          => 'rpwcm_bulk_grant_access_expiration',
            'class'         => 'rpwcm_bulk_grant_access_expiration',
            'label'         => __('Expiration Date', 'woocommerce-membership'),
            'placeholder'   => __('will not expire', 'woocommerce-membership'),
        )); ?>
    </div>
    <?php foreach($user_ids as $user_id): ?>
        <input type="hidden" name="rpwcm_bulk_grant_access_user_ids[]" value="<?php echo $user_id; ?>">
    <?php endforeach; ?>
    <input type="hidden" name="rpwcm_bulk_grant_access_return_url" value="<?php echo $return_url; ?>">
    <div class="rpwcm_bulk_grant_access_submit_row">
        <button type="submit" class="button button-primary" title="<?php _e('Submit', 'woocommerce-membership'); ?>"><?php _e('Submit', 'woocommerce-membership'); ?></button>
    </div>
</form>
