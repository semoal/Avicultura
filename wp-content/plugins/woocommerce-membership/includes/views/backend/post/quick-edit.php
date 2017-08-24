<fieldset class="inline-edit-col-left">
    <div class="inline-edit-col">
        <h4><?php _e('Member Access', 'woocommerce-membership'); ?></h4>

        <label class="inline-edit-rpwcm-quick">
            <span class="title"><?php _e('Access Restriction Method', 'woocommerce-membership'); ?></span>
            <select id="_rpwcm_post_restriction_method_quick" name="_rpwcm_post_restriction_method_quick" class="rpwcm_post_restriction_method_quick">
                <optgroup label="<?php _e('No Restriction', 'woocommerce-membership'); ?>">
                    <option value="none" <?php echo ($method == 'none' ? 'selected="selected"' : ''); ?>><?php _e('No Restriction', 'woocommerce-membership'); ?></option>
                </optgroup>
                <optgroup label="<?php _e('Members Only', 'woocommerce-membership'); ?>">
                    <option value="all_members" <?php echo ($method == 'all_members' ? 'selected="selected"' : ''); ?>><?php _e('All Members', 'woocommerce-membership'); ?></option>
                    <option value="members_with_plans" <?php echo ($method == 'members_with_plans' ? 'selected="selected"' : ''); ?>><?php _e('Members With Specific Plans', 'woocommerce-membership'); ?></option>
                </optgroup>
                <optgroup label="<?php _e('Non-Members Only', 'woocommerce-membership'); ?>">
                    <option value="non_members" <?php echo ($method == 'non_members' ? 'selected="selected"' : ''); ?>><?php _e('All Non-Members', 'woocommerce-membership'); ?></option>
                    <option value="users_without_plans" <?php echo ($method == 'users_without_plans' ? 'selected="selected"' : ''); ?>><?php _e('Users Without Specific Plans', 'woocommerce-membership'); ?></option>
                </optgroup>
            </select>
        </label>

        <label class="inline-edit-rpwcm-quick">
            <span class="title"><?php _e('Add Membership Plans To Access Control Field', 'woocommerce-membership'); ?></span>
            <?php WooCommerce_Membership::render_field_multiselect(array('name' => 'rpwcm_quick_add_plans', 'class' => 'rpwcm_quick_add_plans', 'values' => WooCommerce_Membership_Plan::get_list_of_all_plan_keys(), 'selected' => array())); ?>
        </label>

        <label class="inline-edit-rpwcm-quick">
            <span class="title"><?php _e('Remove Membership Plans From Access Control Field', 'woocommerce-membership'); ?></span>
            <?php WooCommerce_Membership::render_field_multiselect(array('name' => 'rpwcm_quick_remove_plans', 'class' => 'rpwcm_quick_remove_plans', 'values' => WooCommerce_Membership_Plan::get_list_of_all_plan_keys(), 'selected' => array())); ?>
        </label>
    </div>
</fieldset>
