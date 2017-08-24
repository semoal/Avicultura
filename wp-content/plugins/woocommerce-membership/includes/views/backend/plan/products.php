<?php

/**
 * View for Membership Plan Edit page Related Products block
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<?php if (!empty($products)): ?>

    <table class="rpwcm_membership_plan_item_list">
        <thead>
            <tr>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_product"><?php _e('Product Name', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_type"><?php _e('Type', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_since"><?php _e('Current Price', 'woocommerce-membership'); ?></th>
                <th class="rpwcm_fourth_width rpwcm_membership_plan_item_list_expires"><?php _e('Expiration Term', 'woocommerce-membership'); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($products as $product_id => $product): ?>
                <tr>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_product">
                        <?php // WC31: Products will no longer be posts ?>
                        <?php RightPress_Helper::print_link_to_post($product['main_id'], $product['title']); ?>
                    </td>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_type">
                        <?php echo $product['type']; ?>
                    </td>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_since">
                        <?php $product = wc_get_product($product_id); ?>
                        <?php if ($product): ?>
                            <?php echo wc_price($product->get_price()); ?>
                        <?php endif; ?>
                    </td>
                    <td class="rpwcm_fourth_width rpwcm_membership_plan_item_list_expires">
                        <?php
                            $expiration_value = RightPress_WC_Legacy::product_get_meta($product, '_rpwcm_expiration_value', true);
                            $expiration_unit  = RightPress_WC_Legacy::product_get_meta($product, '_rpwcm_expiration_unit', true);

                            if (!empty($expiration_value) && !empty($expiration_unit)) {
                                $time_units = WooCommerce_Membership::get_time_units();

                                echo $expiration_value . ' ';

                                if (isset($time_units[$expiration_unit])) {
                                    echo call_user_func($time_units[$expiration_unit]['translation_callback'], $expiration_unit, $expiration_value);
                                }
                                else {
                                    echo $expiration_unit;
                                }
                            }
                            else {
                                echo '<span class="rpwcm_nothing_to_display">' . __('None', 'woocommerce-membership') . '</span>';
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>
        <?php _e('No related products found.', 'woocommerce-membership'); ?>
    </p>

<?php endif; ?>
