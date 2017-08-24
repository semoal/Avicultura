/**
 * WooCommerce Membership Plugin Backend Scripts (loaded on all pages)
 */
jQuery(document).ready(function() {

    /**
     * Update Membership Plan details dynamically as user types
     */
    if (jQuery('#membership_plan_data').length) {
        jQuery('#title').keyup(function () {
            var new_value = jQuery(this).val();

            // Update title
            jQuery('.membership_plan_details_title').each(function () {
                jQuery(this).html(new_value === '' ? rpwcm_vars.empty_plan_title : new_value);
                jQuery(this).removeClass(new_value === '' ? 'membership_plan_details_title_exists' : 'membership_plan_details_title_does_not_exist');
                jQuery(this).addClass(new_value === '' ? 'membership_plan_details_title_does_not_exist' : 'membership_plan_details_title_exists');
            });

            // Get and display key
            if (rpwcm_vars.membership_plan_exists === '0') {
                jQuery.post(
                    ajaxurl,
                    {
                        'action': 'get_membership_plan_key',
                        'data': new_value
                    },
                    function(response) {
                        var result = jQuery.parseJSON(response);
                        var new_key = result.error === 0 ? result.title : '';

                        // Update key
                        jQuery('.membership_plan_details_key').each(function () {
                            jQuery(this).html(new_key === '' ? rpwcm_vars.empty_plan_key : '<code>' + new_key + '</code>');
                            jQuery(this).removeClass(new_key === '' ? 'membership_plan_details_key_exists' : 'membership_plan_details_key_does_not_exist');
                            jQuery(this).addClass(new_key === '' ? 'membership_plan_details_key_does_not_exist' : 'membership_plan_details_key_exists');
                        });
                    }
                );
            }
        });
    }

    /**
     * Toggle membership settings fields for simple product
     */
    function toggle_rpwcm_simple_product_fields() {
        if (jQuery('select#product-type').val() === 'simple') {
            if (jQuery('input#_rpwcm').is(':checked')) {
                jQuery('.show_if_rpwcm_simple').show();
            }
            else {
                jQuery('.show_if_rpwcm_simple').hide();
            }
        }
        else {
            jQuery('.show_if_rpwcm_simple').hide();
        }
    }

    toggle_rpwcm_simple_product_fields();

    jQuery('body').bind('woocommerce-product-type-change',function() {
        toggle_rpwcm_simple_product_fields();
    });

    jQuery('input#_rpwcm').change(function() {
        toggle_rpwcm_simple_product_fields();
    });

    /**
     * Toggle membership settings fields for variable product
     */
    function toggle_rpwcm_variable_product_fields() {
        if (jQuery('select#product-type').val() === 'variable') {
            jQuery('input._rpwcm_variable').each(function() {

                var variation_fields = jQuery(this).closest('div.woocommerce_variation').find('div.show_if_rpwcm_variable');

                if (jQuery(this).is(':checked')) {

                    // Display membership options
                    variation_fields.each(function() {
                        jQuery(this).show();
                    });

                    // Write "Membership" on variable product handle (if not present)
                    if (jQuery(this).closest('div.woocommerce_variation').find('.rpwcm_variable_product_handle_icon').length == 0) {
                        jQuery(this).closest('div.woocommerce_variation').find('h3').first().find('select').last().after('<i style="margin-left: 10px;" class="fa fa-group rpwcm_variable_product_handle_icon" title="' + rpwcm_vars.title_membership_product + '"></i>');
                    }
                }
                else {

                    // Hide membership options
                    variation_fields.each(function() {
                        jQuery(this).hide();
                    });

                    // Remove "Membership" from variable product handle
                    jQuery(this).closest('div.woocommerce_variation').find('.rpwcm_variable_product_handle_icon').remove();
                }
            });
        }
    }

    toggle_rpwcm_variable_product_fields();

    jQuery('input._rpwcm_variable').each(function() {
        jQuery(this).change(function() {
            toggle_rpwcm_variable_product_fields();
        });
    });

    // Make sure method is applied when variations loaded via AJAX in WC 2.4+
    jQuery(document).on('change', '#variable_product_options', function() {
        toggle_rpwcm_variable_product_fields();
        select2_to_variation_plans_field();
    });
    jQuery(document).on('click', '._rpwcm_variable', function() {
        toggle_rpwcm_variable_product_fields();
        select2_to_variation_plans_field();
    });

    jQuery('#variable_product_options').on('woocommerce_variations_added', function() {
        toggle_rpwcm_variable_product_fields();

        jQuery('input._rpwcm_variable').last().each(function() {
            jQuery(this).change(function() {
                toggle_rpwcm_variable_product_fields();
            });

            jQuery(this).closest('.woocommerce_variation').find('.rpwcm_field_plans').each(function () {
                RP_Select2.call(jQuery(this), {
                    placeholder: rpwcm_vars.title_plans_placeholder,
                    width: '25%'
                });
            });
        });
    });

    /**
     * "Select2" multiselect fields
     */
    jQuery('.rpwcm_field_plans, .rpwcm_only_plans').each(function () {

        var width = jQuery(this).hasClass('rpwcm_field_plans') ? '50%' : '100%';

        RP_Select2.call(jQuery(this), {
            placeholder: rpwcm_vars.title_plans_placeholder,
            width: width
        });
    });

    /**
     * "Select2" grant access select field and add AJAX search
     */
    jQuery('.rpwcm_field_grant_access_to_user').each(function() {

        // Get plan key
        var plan_key = jQuery('input[name=rpwcm_plan_key]').val();

        RP_Select2.call(jQuery(this), {
            ajax: {
              url: ajaxurl,
              type: 'POST',
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  q: params.term,
                  plan_key: plan_key,
                  type: 'post',
                  action: 'rpwcm_user_search'
                };
              },
              cache: true
            },
            escapeMarkup: function (markup) { return markup; },
            minimumInputLength: 4,
            placeholder: rpwcm_vars.title_users_placeholder,
            width: '100%'
        });
    });

    /**
     * "Select2" linked plan select field
     */
    jQuery('.rpwcm_field_add_linked_plan').each(function () {
        RP_Select2.call(jQuery(this), {
            placeholder: rpwcm_vars.title_plans_placeholder_single,
            width: '300px'
        });
    });

    /**
     * "Select2" function for variation fields (for WC 2.4+)
     */
    function select2_to_variation_plans_field() {
        jQuery('input._rpwcm_variable').closest('.woocommerce_variation').find('select.rpwcm_field_plans').each(function () {
            if (!jQuery(this).data('select2')) {
                RP_Select2.call(jQuery(this), {
                    placeholder: rpwcm_vars.title_plans_placeholder,
                    width: '100%'
                });
            }
        });
    }

    /**
     * Hide access restriction fields
     */
    function rpwcm_show_hide_access_restriction_fields(update)
    {
        var new_value = jQuery('#_rpwcm_post_restriction_method').val();
        if (new_value == 'members_with_plans' || new_value == 'users_without_plans') {
            if (update) {
                RP_Select2.call(jQuery('#_rpwcm_only_caps'), 'val', '');
            }
            jQuery('.rpwcm_show_if_restrict_access_by_plan').show();
        }
        else {
            if (update) {
                RP_Select2.call(jQuery('#_rpwcm_only_caps'), 'val', '');
            }
            jQuery('.rpwcm_show_if_restrict_access_by_plan').hide();
        }
    }
    jQuery('#_rpwcm_post_restriction_method').change(function() {
        rpwcm_show_hide_access_restriction_fields(true);
    });
    rpwcm_show_hide_access_restriction_fields(false);

});
